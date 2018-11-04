<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Trending Page"/>
  <meta name="keywords" content="HTML, CSS"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="style/style.css" rel="stylesheet" />
  <title>Trending</title>
</head>
<body>
<?php
require_once("settings.php");
IsLoggedIn($conn);
DatabaseExists($conn);
include('header.inc');
?>

<?php
   echo "<h1>TOP 10 POST</h1>";
   $query = "SELECT * FROM posts ORDER BY view_count DESC LIMIT 10";
   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result)==0){}
   else {
      while ($output = mysqli_fetch_assoc($result)){
        DisplayPostSummary($output, $conn);
      }
   }


   echo "<h1>TOP 10 TRENDING TAGS</h1>";
   $query = "SELECT post_tags.tag_id, tags.tag_name, COUNT(post_tags.tag_id)
   FROM post_tags
   INNER JOIN tags ON post_tags.tag_id = tags.tag_id
   GROUP BY post_tags.tag_id ASC LIMIT 10";
   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result) == 0){}
   else {
      while ($output = mysqli_fetch_assoc($result)){
        echo "<a class='modify_left' href='viewtag.php?tag_id=" . $output['tag_id'] ."'><h2>" . $output['tag_name'] . "</h2></a>";
      }
   }
?>


</body>
</html>
