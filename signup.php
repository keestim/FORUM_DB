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
  $query_error = false;
  $err_message = "";

  if (isset($_POST["submit"])){

     $first_name = $_POST["first_name"];
     $first_name = check_isset($first_name);
     if (!preg_match("/([A-Z]|[a-z]){1,}/", $first_name)){
       $err_message .= "Please only use letters for the first name!  <br/>";
     }


     $last_name = $_POST["last_name"];
     $last_name = check_isset($last_name);
     if (!preg_match("/([A-Z]|[a-z]){1,}/", $last_name)){
       $err_message .= "Please only use letters for the last name! <br/>";
     }

     $dob = $_POST["dob"];
     $dob = check_isset($dob);

     $display_name = $_POST["display_name"];
     $display_name = check_isset($display_name);
     if (!preg_match("/([A-Z]|[a-z]|[0-9]|[\_]|[\-]){4,50}/", $display_name)){
       $err_message .= "Please only use letters, hyphens and under scores for the display_name!  <br/>";
     }

     $email = $_POST["email"];
     $email = check_isset($email);
     if (!preg_match("/^([0-9]|[A-Z]|[a-z]|[\.]|[\-]|[\_]){1,}[@]([A-Z]|[a-z]|[0-9]|[\.]|[\-]|[\_]){1,}([\.]([A-Z]|[a-z]|[0-9]|[\.]|[\-]|[\_]){1,}){1,4}$/", $email)){
       $err_message .= "Please follow the correct format for an email address </br>";
     }

     $password = $_POST['password'];
     $password = check_isset($password);
     if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,100}/', $password)){
       $err_message .= "Please have atleast a lower case letter, a upper case letter, a number and a total number of characters of atleast 8 in your password  <br/>";
     }

     $confirm_password = $_POST["confirm_password"];
     $confirm_password = check_isset($confirm_password);
     if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,100}/', $confirm_password)){
       $err_message .= "Please have atleast a lower case letter, a upper case letter, a number and a total number of characters of atleast 8 in your password  <br/>";
     }

     if ($password != $confirm_password){
       $err_message .= "Make sure that both your entered passwords match  <br/>";
     }

     if ($err_message > ""){
       echo $err_message;
     }
     else{
      $query = "SELECT COUNT(user_display_name) FROM users WHERE user_display_name = '$display_name'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0){
        while ($output = mysqli_fetch_assoc($result)){
           $num = $output['COUNT(user_display_name)'];
        }
      }

      if (intval($num) == 0){
         $query = "INSERT INTO users (user_first_name, user_last_name, user_display_name, user_email, user_password, user_dob)
         VALUES ('$first_name', '$last_name', '$display_name', '$email', '$password', '$dob')";
         $result = mysqli_query($conn, $query);
         if (mysqli_error($conn) > ""){
           $query_error = true;
         }

         if ($query_error){
           mysqli_rollback($conn);
         }
         else {
           mysqli_commit($conn);
         }

         if(mysqli_connect_errno()){
            echo mysqli_connect_error();
         }
         else {
            header("Location: ./login.php");
         }
      }
      else {
        echo "Your choosen display name is already taken, please choose another one";
      }
     }
  }
  ?>

<h1>Sign Up</h1>
<div class = "post_summary">
   <form method="post" name ="signup" action="">
      <h2>First Name</h2>
      <input type="text" name="first_name" pattern="([A-Z]|[a-z]){1,}" required='required'/>
      <h2>Last Name</h2>
      <input type="text" name="last_name" pattern="([A-Z]|[a-z]){1,}" required='required'/>
      <h2>DOB</h2>
      <input type="date" name="dob" required='required'/>

      <hr/>

      <h2>Display Name</h2>
      <input type="text" pattern="([A-Z]|[a-z]|[0-9]|[_]|[-]){4,50}" name="display_name" required='required'/>
      <h2>Email</h2>
      <input type="text" pattern="^([0-9]|[A-Z]|[a-z]|[\.]|[\-]|[\_]){1,}[@]([A-Z]|[a-z]|[0-9]|[\.]|[\-]|[\_]){1,}([\.]([A-Z]|[a-z]|[0-9]|[\.]|[\-]|[\_]){1,}){1,4}$" name="email"  required='required'/>
      <p>Please Use 8 characters (minimum), a Upper Case letter, a Lower Case Letter and a Number in your password (NO SPECIAL CHARACTERS)</p>
      <h2>Password</h2>
      <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,100}$" name="password"  required='required'/>
      <h2>Confirm Password</h2>
      <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,100}$" name="confirm_password"  required='required'/>
      <br/>
      <br/>
      <input type="submit" name="submit">
   </form>
</div>

</body>
</html>
