<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Home Page"/>
  <meta name="keywords" content="HTML, CSS"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="" rel="stylesheet" />
  <title>Home</title>
</head>
<body>

<?php
   require_once("settings.php");
   IsLoggedIn($conn);
   DatabaseExists($conn);

   if (isset($_GET['profile_id'])){
      echo $_GET['profile_id'];

      $query = "SELECT * FROM users WHERE user_id = '" . $_GET["profile_id"] ."'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result)==0){
         header("Location: ./home.php");
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
      header("Location: ./home.php");
   }
 ?>

<h1><?php echo $display_name; ?></h1>

<?php
   if (count($post_ids) > 0){
      foreach ($post_ids as $id){
         $query = "SELECT * FROM posts WHERE post_id = '$id'";
         $result = mysqli_query($conn, $query);

         if (mysqli_num_rows($result) != 0){
            while ($row = mysqli_fetch_assoc($result)){
               echo "<h2>" . $row['post_title'] . "</h2>";
               echo "<p>" . $row['post_date'] . "</p>";
               echo "<p>" . $row['post_content'] . "</p>";
            }
         }
      }
   }

 ?>

</body>
</html>
