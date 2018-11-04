<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum View Tag"/>
  <meta name="keywords" content="HTML, CSS"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="style/style.css" rel="stylesheet" />
  <title>View Tag</title>
</head>
<body>

<?php
   require_once("settings.php");
   IsLoggedIn($conn);
   DatabaseExists($conn);
   include('header.inc');

//probably get tag name in seperate query

   if (isset($_GET['tag_id'])){
     $tag_id = $_GET['tag_id'];

     $num_posts_query = "SELECT COUNT(tag_id) FROM post_tags WHERE tag_id = '$tag_id'";
     $num_result = mysqli_query($conn, $num_posts_query);

     if (mysqli_num_rows($num_result)==0){
       //header("Location: index.php");
     }
     else {
        while ($row = mysqli_fetch_assoc($num_result)){
          $num_posts = $row['COUNT(tag_id)'];
        }
     }


     $tag_name_query = "SELECT * FROM tags WHERE tag_id = $tag_id";
     $tag_name_result = mysqli_query($conn, $tag_name_query);

     if (mysqli_num_rows($tag_name_result)==0){
       //header("Location: index.php");
     }
     else {
        while ($tag_result = mysqli_fetch_assoc($tag_name_result)){
          $tag_name = $tag_result['tag_name'];
          echo "<h1>#" . $tag_name . "</h1>";
          echo "<h2>" . $num_posts . " posts</h2>";
        }
     }

     $tag_post_query = "SELECT * FROM post_tags
     INNER JOIN posts ON post_tags.post_id = posts.post_id
     INNER JOIN user_posts ON posts.post_id = user_posts.post_id
     INNER JOIN users ON user_posts.user_id = users.user_id
     WHERE post_tags.tag_id = '$tag_id'";

     $result = mysqli_query($conn, $tag_post_query);

     if (mysqli_num_rows($result) == 0)
     {
     }
     else {
        while ($tag = mysqli_fetch_assoc($result)){
           DisplayPostSummary($tag, $conn);
        }
     }
   }
