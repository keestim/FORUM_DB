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
   echo "<h1>TOP 10 TRENDING</h1>";
   $query = "SELECT * FROM posts ORDER BY view_count DESC";
   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result)==0){}
   else {
      while ($tag = mysqli_fetch_assoc($returned_tag)){
        echo "<a class='tag' href=viewtag.php?tag_id=" . $tag['tag_id'] . ">#" . $tag['tag_name'] . "</a><br/>";
      }
   }
?>


</body>
</html>
