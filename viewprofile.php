<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Home Page"/>
  <meta name="keywords" content="HTML, CSS"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="style/style.css" rel="stylesheet" />
  <title>Home</title>
  <script src="./scripts/accordion.js">
  </script>
</head>

<body>
<?php
 require_once("settings.php");
 IsLoggedIn($conn);
 DatabaseExists($conn);
 include('header.inc');
 $query_error = false;

 if (isset($_GET['profile_id'])){
    $query = "SELECT * FROM users WHERE user_id = '" . $_GET["profile_id"] ."'";
    $result = mysqli_query($conn, $query);
    if (mysqli_error($conn) > ""){
      $query_error = true;
    }

    if (mysqli_num_rows($result) == 0){
       header("Location: ./index.php");
    }
    else {
      //gets the display name of a given user
       while ($profile = mysqli_fetch_assoc($result)){
          $display_name = $profile['user_display_name'];
       }
    }
 }
 else {
    header("Location: ./index.php");
 }

 //checks if the follow button has been pressed
 if (isset($_POST['follow'])){

   //checks if the user is being followed currently or isn't being followed
   switch ($_POST['follow']){
     //if the user is being follow and want to unfollow
     case 0:
     $query = "DELETE FROM following WHERE user_id = " . $_SESSION['id'] . " AND followed_user_id = " . $_GET['profile_id'];
     $result = mysqli_query($conn, $query);
     if (mysqli_error($conn) > ""){
       $query_error = true;
     }
       break;

       //if the user isn't being folloed and the user wants to follow them
      case 1:
      $following_id = $_GET['profile_id'];
      $user_id = $_SESSION['id'];
      $query = "INSERT INTO following (user_id, followed_user_id) VALUES ('$user_id', '$following_id')";
      $result = mysqli_query($conn, $query);
      if (mysqli_error($conn) > ""){
        $query_error = true;
      }
        break;
   }
 }
?>

<?php echo "<h1>" . $display_name , "</h1>"; ?>

<?php
  if ($_GET['profile_id']){
    //checks if the profile being views isn't the user's own profile
    //because the user can't follow themself
    if  ($_GET['profile_id'] != $_SESSION['id'])
    {
      echo '<form name="follow" method="post" action="">';
      $query = "SELECT * FROM following
      WHERE user_id = " . $_SESSION["id"] . " AND followed_user_id = " . $_GET['profile_id'];
      $result = mysqli_query($conn, $query);
      if (mysqli_error($conn) > ""){
        $query_error = true;
      }

      if (mysqli_num_rows($result) == 0){
        echo "<button class='follow' name='follow' type='submit' value='1'>FOLLOW</button>";
      }
      else
      {
        echo "<button class='follow' name='follow' type='submit' value='0'>UNFOLLOW</button>";
      }
      echo "</form>";
    }
  }

  if (isset($_GET['profile_id'])){
    $select_profile = $_GET['profile_id'];

    //display all of the posts for a given user id to the page, in descending order (i.e. from most recent to oldest)
    $query = "SELECT * FROM user_posts
    INNER JOIN posts ON user_posts.post_id = posts.post_id
    WHERE user_id = '$select_profile' ORDER BY posts.post_date DESC";

    $result = mysqli_query($conn, $query);
    if (mysqli_error($conn) > ""){
      $query_error = true;
    }

    if (mysqli_num_rows($result) != 0){
      while ($row = mysqli_fetch_assoc($result)){
         DisplayPostSummary($row, $conn);
      }
    }
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
