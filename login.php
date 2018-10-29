<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Login Page"/>
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
   $username = $_POST["username"];
   $password = $_POST["password"];

   $query = "SELECT * FROM users WHERE (user_display_name = '$username'
            AND user_password = '$password')
            OR (user_email = '$username' AND user_password = '$password')";

   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result)==0){
      echo "Wrong Username or Password Entered!";
   }
   else {
      while ($row = mysqli_fetch_assoc($result)){
         //if there is a result returned it must be correct
         $_SESSION["id"] = $row['user_id'];
         header("Location: index.php");
      }
   }
}
?>

<h1>Login</h1>
<div class="post_summary">
   <form method="post" name ="login" action="">
      <h2>Display Name/Email</h2>
      <input name="username" type="text"/>
      <h2>Password</h2>
      <input name="password" type="password"/>

      <br/>
      <br/>

      <input type="submit" name="submit">
   </form>
   <br/>
<a href="signup.php">SIGN UP</a>
</div>

</body>
</html>
