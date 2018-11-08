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

$query_error = false;

$user_id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
if (mysqli_error($conn) > ""){
  $query_error = true;
}

if (mysqli_num_rows($result)==0){
  header("Location: ./login.php");
}
while ($row = mysqli_fetch_assoc($result)){
   $display_name = $row['user_display_name'];
}
?>

<h1>Welcome Back, <?php echo $display_name; ?></h1>

<?php
$user_id = $_SESSION['id'];
$query = "SELECT * FROM following
INNER JOIN user_posts ON following.followed_user_id = user_posts.user_id
INNER JOIN posts ON user_posts.post_id = posts.post_id
INNER JOIN users ON following.followed_user_id = users.user_id
WHERE following.user_id = '$user_id'
ORDER BY posts.post_date DESC";

$result = mysqli_query($conn, $query);
if (mysqli_error($conn) > ""){
  $query_error = true;
}

if (mysqli_num_rows($result)==0){
}
while ($row = mysqli_fetch_assoc($result)){
   DisplayPostSummary($row, $conn);
}

if ($query_error){
  mysqli_rollback($conn);
}
else {
  mysqli_commit($conn);
}
?>

</body>
</html>
