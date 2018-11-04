<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Home Page"/>
  <meta name="keywords" content="HTML, CSS"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="style/style.css" rel="stylesheet" />
  <title>Home</title>
</head>
<body>

<?php
   require_once("settings.php");
   IsLoggedIn($conn);
   DatabaseExists($conn);
   include('header.inc');

   $user_id = $_SESSION['id'];

   if (isset($_POST['update_password'])){
     $query = "SELECT * FROM users
     WHERE user_id = '$user_id'";
     $result = mysqli_query($conn, $query);

     if (mysqli_num_rows($result)==0){
       //header("Location: index.php");
     }
     else {
        while ($output = mysqli_fetch_assoc($result)){
            if ($output['user_password'] == $_POST['current_password']){
              $new_password = $_POST['new_password'];
              $new_password = check_isset($new_password);
              $update_password_query = "UPDATE users
              WHERE user_id = '$user_id'
              SET user_password = $new_password";
              $update = mysqli_query($conn, $update_password_query);
              echo "password successfully updated!";
            }
            else{
              echo "Current password was entered incorrectly!";
            }
        }
     }
   }

   if (isset($_POST['update_details'])){
     $first_name = $_POST['first_name'];
     $first_name = check_isset($first_name);

     $last_name = $_POST['last_name'];
     $last_name = check_isset($last_name);

     $display_name = $_POST['display_name'];
     $display_name = check_isset($display_name);

     $dob = $_POST['user_dob'];
     $dob = check_isset($dob);

     $query = "UPDATE users
     SET user_first_name = '$first_name', user_last_name = '$last_name', user_display_name = '$display_name', user_dob = '$dob'
     WHERE user_id = $user_id";
     $result = mysqli_query($conn, $query);
   }
?>

<h1>Edit Profile/Setting</h1>
<h2>Edit Profile</h2>
<?php
$user_data_query = "SELECT * FROM users
WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $user_data_query);
while ($output = mysqli_fetch_assoc($result)){
  $first_name = $output['user_first_name'];
  $last_name = $output['user_last_name'];
  $display_name = $output['user_display_name'];
  $dob = $output['user_dob'];
  echo "<div class='post_summary'>";
  echo "<form method='post' name='update'>";
  echo "Display Name: <input type='text' name='display_name' value='$display_name'/><br/>";
  echo "First Name: <input type='text' name='first_name' value='$first_name'/><br/>";
  echo "Last Name: <input type='text' name='last_name' value='$last_name'/><br/>";
  echo "DOB: <input type='date' name='user_dob' value='$dob'/><br/>";
  echo "<input type='submit' name='update_details'/>";
  echo "</form>";
  echo "</div>";
}
?>

<h2>Change Password</h2>
<div class="post_summary">
  <form name="new_password" method="post" action="">
    <p>Enter current password: <input type="password" name="current_password"/>
    <br/>
    <p>Enter new password: <input type="password" name="new_password"/>
    <br/>
    <input type="submit" name="update_password">
  </form>
</div>

</body>
</html>
