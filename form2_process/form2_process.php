<?php
$nameErr = $postalErr = $dobErr = $emailErr = $passwordErr = $countryErr = "";
$name = $postal = $dob = $email = $password = $country = "";

$isValid = false;

$countries = ["United States", "Canada", "United Kingdom", "Australia", "Bangladesh"];

function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  /*----------Full Name Validation----------*/
  if (empty($_POST["name"])) {
    $nameErr = "Enter your full name";
  } else {
    $name = cleanInput($_POST["name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      $nameErr = "Use letters,spaces, hyphens and apostrophes only";
    }
    elseif (strlen($name) < 3){
        $nameErr = " Name must be at least 3 charecters";
    }
  }

  /*------------Postal code : required, numeric, 4-10 digits-------*/
  if (empty($_POST["postal"])) {
    $postalErr = "Enter your postal code";
  } else {
    $postal = cleanInput($_POST["postal"]);
    if (!preg_match("/^[0-9]{4,10}$/", $postal)) {
      $postalErr = "Postal code must be 4-10 digits";
    }
    }

    /*------------Date of Birth : required, real date, past, age 18+-------*/
if (empty($_POST["dob"])) {
        $dobErr = "Enter your date of birth";
    } else {
        $dob   = cleanInput($_POST["dob"]);
        $today = new DateTime();
        $birth = DateTime::createFromFormat("Y-m-d", $dob);

        if (!$birth || $birth->format("Y-m-d") !== $dob) {
            $dobErr = "Enter a valid date in YYYY-MM-DD format";
        } elseif ($birth > $today) {
            $dobErr = "Date of birth cannot be in the future";
        } elseif ($birth->diff($today)->y < 18) {
            $dobErr = "You must be at least 18 years old to register";
        }
    }
    
    /*------------Email : required, valid format-------*/
    if (empty($_POST["email"])) {
        $emailErr = "Enter your email address";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Enter a valid email address, e.g., jane@example.com";
        }
    }
    /*------------Password : required, 8+ chars, letters + Numbers-------- Not cleaned and never senrt back to the browser.------*/
    if (empty($_POST["password"])) {
        $passwordErr = "Enter a password";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        } elseif (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
            $passwordErr = "Password must contain both letters and numbers";
        }
    }
    /*------------Country : required, must be in the list-------*/
    if (empty($_POST["country"])) {
        $countryErr = "Select your country";
    } else {
        $country = cleanInput($_POST["country"]);
        if (!in_array($country, $countries)) {
            $countryErr = "Select a valid country from the list";
        }
    }

    $isValid = !$nameErr && !$postalErr && !$dobErr && !$emailErr && !$passwordErr && !$countryErr;
}
