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


if (isset($_POST["submit"])){

   $first_name = $_POST["first_name"];
   $first_name = check_isset($first_name);

   $last_name = $_POST["last_name"];
   $last_name = check_isset($last_name);

   $dob = $_POST["dob"];
   $dob = check_isset($dob);

   $display_name = $_POST["display_name"];
   $display_name = check_isset($display_name);

   $email = $_POST["email"];
   $email = check_isset($email);

   $password = $_POST['password'];
   $password = check_isset($password);

   $confirm_password = $_POST["confirm_password"];
   $confirm_password = check_isset($confirm_password);

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
<div class = "post_summary">
   <form method="post" name ="signup" action="">
      <h2>First Name</h2>
      <input type="text" name="first_name" pattern="([A-Z]|[a-z]){1,}" required='required'/>
      <h2>Last Name</h2>
      <input type="text" name="last_name" required='required'/>
      <h2>DOB</h2>
      <input type="date" name="dob" pattern="([A-Z]|[a-z]){1,}" required='required'/>

      <hr/>

      <h2>Display Name</h2>
      <input type="text" pattern="([A-Z]|[a-z]|[_]|[,]){4,}" name="display_name" required='required'/>
      <h2>Email</h2>
      <input type="text" pattern="^([A-Z]|[a-z]|[\.]|[\-]){1,}[@]([A-Z]|[a-z]|[\.]|[\-]){1,}([\.]([A-Z]|[a-z]|[\.]|[\-]){1,}){1,2}$" name="email"  required='required'/>
      <h2>Password</h2>
      <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" name="password"  required='required'/>
      <h2>Confirm Password</h2>
      <input type="password" patter"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" name="confirm_password"  required='required'/>
      <br/>
      <br/>
      <input type="submit" name="submit">
   </form>
</div>

</body>
</html>
