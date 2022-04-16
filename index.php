<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbaseName = "studentsdata";
$studentTable = 'students';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbaseName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create A function To Filter User Input.
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Create Variables For Value As Well As Errors
$name = $email = $phone = $address = $age = $language = $gender = "";
$nameErr = $emailErr = $phoneErr = $addressErr = $ageErr = $languageErr = $genderErr = $isExist = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name Validation
    if (empty($_POST["fname"])) {
        $nameErr = "Name Can Not Be Empty.";
    } else {
        $name = test_input($_POST["fname"]);
        if (!preg_match("/^[a-zA-z ]*$/", $name)) {
            $nameErr = "Name Format Invalid, Can Contain Only Letters.";
        }
    }

    // Email Validation
    if (empty($_POST["email"])) {
        $emailErr = "Email Can Not Be Empty.";
    } else {
        $email = test_input($_POST["email"]);
        if (!preg_match("/^([a-zA-Z0-9\.]+@[a-zA-Z]+(\.)[a-zA-Z]{2,3})$/", $email)) {
            $emailErr = "Email Format Invalid, Can Be exampe@example.com.";
        }
    }
    // Phone Validation
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone Can Not Be Empty.";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!is_numeric($phone)) {
            $phoneErr = "Phone Format Invalid, Can Contain Only Number.";
        }
    }

    // Address Validation
    if (empty($_POST["address"])) {
        $addressErr = "Address Can Not Be Empty.";
    } else {
        $address = test_input($_POST["address"]);
    }

    // Age Validation
    if (empty($_POST["age"])) {
        $ageErr = "Age Can Not Be Empty.";
    } else {
        $age = test_input($_POST["age"]);
        if (!($age >= 10 && $age <= 30 && is_numeric($age))) {
            $ageErr = "Age Can Contain Only Number Between 10:30.";
        }
    }
    // Language Validation
    if (empty($_POST["language"])) {
        $languageErr = "Language Can Not Be Empty.";
    } else {
        $language = test_input($_POST["language"]);
        if (!preg_match("/^[a-zA-z ]*$/", $language)) {
            $languageErr = "Language Format Invalid, Can Contain Only Letters.";
        }
    }

    // Gender Validation
    if (!empty($_POST["gender"])) {
        $gender = test_input($_POST["gender"]);
    }else {
        $genderErr = "Please Select Choice.";
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST"
    and ($nameErr == "" && $emailErr == "" && $phoneErr == "" && $addressErr == ""
    && $ageErr == "" && $languageErr == "" && $genderErr == "")) {
    $result = $conn->query("SELECT email,phoneNumber from $studentTable WHERE email = '$email'");
    if ($result->num_rows == 1){
        $isExist = "This User Is Exists";
    }else{
        $query = "INSERT INTO $studentTable(fullName, email, phoneNumber, address, age, languageUser,gender) 
        VALUES('$name', '$email', '$phone', '$address', '$age', '$language', '$gender')";
        $run = $conn->query($query);
        header("Location:index.php");
        exit;
    }
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forms</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <div class="title">
      Registration
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
      <div class="user-details">
        <div class="input-box">
          <span class="details">
            Full Name
          </span>
          <input type="text" name="fname" placeholder="Enter your name">
          <p class="error">
              <?php echo $nameErr; ?>
          </p>
        </div>
        <div class="input-box">
          <span class="details">
            Email
          </span>
          <input type="text" name="email" placeholder="Enter your email">
          <p class="error">
            <?php echo $emailErr; ?>
          </p>
        </div>
        <div class="input-box">
          <span class="details">
            Phone Number
          </span>
          <input type="text" name="phone" placeholder="Enter your number" >
          <p class="error">
            <?php echo $phoneErr; ?>
          </p>
        </div>
        <div class="input-box">
          <span class="details">
            Address
          </span>
          <input type="text" name="address" placeholder="Enter your Address">
          <p class="error"><?php echo $addressErr; ?></p>
        </div>
        <div class="input-box">
          <span class="details">
            Age
          </span>
          <input type="text" name="age" placeholder="Enter your Age">
          <p class="error"><?php echo $ageErr; ?></p>
        </div>
         <div class="input-box">
          <span class="details">
            Language
          </span>
          <input type="text" name="language" placeholder="Enter your Language">
          <p class="error"><?php echo $languageErr; ?></p>
        </div>
      </div>
      <div class="gender-details">
        <input type="radio"  value="male" name="gender" id="dot-1">
        <input type="radio" value="female" name="gender" id="dot-2">
        <span class="gender-title">Gender</span>
        <p class="error"><?php echo $genderErr; ?></p>
        <div class="category">
          <label for="dot-1">
            <span class="dot one"></span>
            <span type="radio" class="gender">Male</span>
          </label>
          <label for="dot-2">
            <span class="dot two"></span>
            <span type="radio" class="gender">Female</span>
          </label>
        </div>
      </div>
        <p class="error">
            <?php echo $isExist; ?>
        </p>
      <div class="button">
        <input type="submit" name="submit" value="Register">
      </div>
    </form>
  </div>
</body>
</html>