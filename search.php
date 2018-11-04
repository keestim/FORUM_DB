<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Forum Search Page"/>
  <meta name="keywords" content="HTML, CSS"/>
  <meta name="author" content="Timothy Keesman"/>
  <link href="style/style.css" rel="stylesheet" />
  <title>Search</title>
</head>
<body>
<?php
require_once("settings.php");
IsLoggedIn($conn);
DatabaseExists($conn);
include('header.inc');
?>

<div class="search_container">
   <form name="search" method="post" action="">
      <br/>
     <input type="text" name="search_term"/>
     <br/>
     <button type="submit" name"submit">SEARCH</button>
   </form>
</div>

<?php
//check if user has tried to search
if (isset($_POST['search_term'])){
   echo "<div class='search_result'>";
  echo "<h2>Tags:</h2>";
  echo "<div class='container'>";

  $search = $_POST['search_term'];
  $query_match_tags = "SELECT * FROM tags WHERE tag_name LIKE '%$search%' ORDER BY tag_name";
  $returned_tag = mysqli_query($conn, $query_match_tags);

  if (mysqli_num_rows($returned_tag)==0){
  }
  else {
     while ($tag = mysqli_fetch_assoc($returned_tag)){
       echo "<a class='tag' href=viewtag.php?tag_id=" . $tag['tag_id'] . ">#" . $tag['tag_name'] . "</a><br/>";
     }
  }

  echo "</div></div>";

  echo "<div class='search_result'>";
  echo "<h2>Users:</h2>";
  echo "<div class='container'>";
  $query_match_users = "SELECT * FROM users WHERE user_display_name LIKE '%$search%' ORDER BY user_display_name";
  $returned_users = mysqli_query($conn, $query_match_users);

  if (mysqli_num_rows($returned_users)==0){
  }
  else {
     while ($user = mysqli_fetch_assoc($returned_users)){
       echo "<a class='tag' href=viewprofile.php?profile_id=" . $user['user_id'] . ">" . $user['user_display_name'] . "</a><br/>";
     }
  }

  echo "</div></div>";


  echo "<div class='search_result'>";
  echo "<h2>Posts:</h2>";
  echo "<div class='container'>";
  $query_match_posts = "SELECT * FROM posts WHERE post_title LIKE '%$search%' OR post_content LIKE '%$search%' ORDER BY post_title";
  $returned_posts = mysqli_query($conn, $query_match_posts);

  if (mysqli_num_rows($returned_posts)==0){
  }
  else {
     while ($post = mysqli_fetch_assoc($returned_posts)){
       echo "<a class='tag' href=viewpost.php?post_id=" . $post['post_id'] . ">" . $post['post_title'] . "</a><br/>";
     }
  }

  echo "</div></div>";

}
?>

</body>
</html>
