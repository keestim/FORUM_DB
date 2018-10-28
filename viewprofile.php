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

   if (isset($_GET['profile_id'])){
      $query = "SELECT * FROM users WHERE user_id = '" . $_GET["profile_id"] ."'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result)==0){
         header("Location: ./index.php");
      }
      else {
         while ($profile = mysqli_fetch_assoc($result)){
            $display_name = $profile['user_display_name'];
         }
      }

      $query = "SELECT * FROM user_posts WHERE user_id = " . $_GET['profile_id'];
      $result = mysqli_query($conn, $query);

      $post_ids = array();

      if (mysqli_num_rows($result) != 0){
         while ($row = mysqli_fetch_assoc($result)){
            array_push($post_ids, $row['post_id']);
         }
      }
   }
   else {
      header("Location: ./index.php");
   }

   if (isset($_POST['follow'])){
     switch ($_POST['follow']){
       case 0:
       $query = "DELETE FROM following WHERE user_id = " . $_SESSION['id'] . " AND followed_user_id = " . $_GET['profile_id'];
       $result = mysqli_query($conn, $query);
         break;

        case 1:
        $following_id = $_GET['profile_id'];
        $user_id = $_SESSION['id'];
        $query = "INSERT INTO following (user_id, followed_user_id) VALUES ('$user_id', '$following_id')";
        $result = mysqli_query($conn, $query);
          break;
     }
   }
 ?>

<h1><?php echo $display_name; ?></h1>

<?php
if ($_GET['profile_id']){
  if  ($_GET['profile_id'] != $_SESSION['id'])
  {
    echo '<form name="follow" method="post" action="">';
    $query = "SELECT * FROM following WHERE user_id = " . $_SESSION["id"] . " AND followed_user_id = " . $_GET["profile_id"];
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0){
      echo "<button name='follow' type='submit' value='1'>FOLLOW</button>";
    }
    else{
      echo "<button name='follow' type='submit' value='0'>UNFOLLOW</button>";
    }
    echo "</form>";
  }
}
?>

<?php
   if (count($post_ids) > 0){
      foreach ($post_ids as $id){
         $query = "SELECT * FROM posts WHERE post_id = '$id'";
         $result = mysqli_query($conn, $query);

         if (mysqli_num_rows($result) != 0){
            while ($row = mysqli_fetch_assoc($result)){
              echo "<div class='post_summary'>";
              echo "<h2>" . $row['post_title'] . "</h2>";
              echo "<p>" . $row['post_date'] . "</p>";
              echo "<p>" . getPostTags($conn, $row['post_id']) . "</p>";
              echo "<p>" . $row['post_content'] . "</p>";
              echo "<p><a href=viewpost.php?post_id=" . $row['post_id'] . ">VIEW POST</a></p>";
              echo "</div>";
            }
         }
      }
   }
 ?>

</body>
</html>
