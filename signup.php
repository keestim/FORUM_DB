<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Signup Page"/>
  <meta name="keywords" content="HTML, CSS, PHP"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="style/style.css" rel="stylesheet" />
  <title>Sign Up</title>
</head>
<body>

<?php
require_once("settings.php");
DatabaseExists($conn);
include('header.inc');


if (isset($_POST["submit"])){

   $first_name = $_POST["first_name"];
   $last_name = $_POST["last_name"];
   $dob = $_POST["dob"];

   $display_name = $_POST["display_name"];
   $email = $_POST["email"];
   $password = $_POST['password'];
   $confirm_password = $_POST["confirm_password"];

   $query = "INSERT INTO users (user_first_name, user_last_name, user_display_name, user_email, user_password, user_dob)
   VALUES ('$first_name', '$last_name', '$display_name', '$email', '$password', '$dob')";
   $result = mysqli_query($conn, $query);

   if(mysqli_connect_errno()){
      echo mysqli_connect_error();
   }
   else {
      header("Location: ./login.php");
   }
}
?>

<h1>Sign Up</h1>

<form method="post" name ="signup" action="">
   <h2>First Name</h2>
   <input type="text" name="first_name"/>
   <h2>Last Name</h2>
   <input type="text" name="last_name"/>
   <h2>DOB</h2>
   <input type="date" name="dob"/>

   <hr/>

   <h2>Display Name</h2>
   <input type="text" name="display_name"/>
   <h2>Email</h2>
   <input type="email" name="email"/>
   <h2>Password</h2>
   <input type="password" name="password"/>
   <h2>Confirm Password</h2>
   <input type="password" name="confirm_password"/>
   <br/>
   <br/>
   <input type="submit" name="submit">
</form>

</body>
</html>
