<?php
/*
  formValidation.php
  since 12 Oct 2016
  date 12 Oct 2016
  Define fields of form and check their validation
  The fields will be written into (fucking) SQL database  
  +) Firstname: required
  +) Lastname: required
  +) Seatnumber: required -> this stupid field must be read from somewhere else
  +) Email: optional
  Some fields are just to show up
  +) Comment
  +) Gender
*/
 ?> 

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  First name: <input type="text" name="firstName" value="<?php echo $firstName;?>">
  <span class="error">* <?php echo $firstNameErr;?></span>
  <br><br>
  Last name: <input type="text" name="lastName" value="<?php echo $lastName;?>">
  <span class="error">* <?php echo $lastNameErr;?></span>
  <br><br>
  Seat number: <input type="text" name="seatNum" value="<?php echo $seatNum;?>">
  <span class="error">* <?php echo $seatNumErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <br><br>
  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <br><br>
  Gender:
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<?php
// define variables and set to empty values
$firstName = $lastName = $seatNum = $email = $comment = $gender;
$firstNameErr = $lastNameErr = $seatNumErr =  "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["firstName"])) {
    $firstNameErr = "First name is required";
  } 
  else {
    $firstName = test_input($_POST["firstName"]);
    // check if firstName only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z -]*$/",$firstName)) {
      $firstNameErr = "Only letters and white space allowed";
    }
  }

  if (empty($_POST["lastName"])) {
    $firstNameErr = "Last name is required";
  } 
  else {
    $lastName = test_input($_POST["lastName"]);
    // check if lastName only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z -]*$/",$lastName)) {
      $lastNameErr = "Only letters and white space allowed";
    }
  }

  if (empty($_POST["seatNum"])) {
    $seatNum = "";
  } else {
    $seatNum = test_input($_POST["seatNum"]);
    // check if seat number contains only number or/and letters
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$seatNum)) {
      $seatNumErr = "Invalid URL";
    }
  }

  if (empty($_POST["email"])) {
    $email = "";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }   


  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $gender = "";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
