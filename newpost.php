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

   if (isset($_POST['submit'])){
      $title = $_POST['title'];
      $content = $_POST['content'];
      echo $content;
      $tags = $_POST['tags'];

      DatabaseExists($conn);

      //ideal use case for transactions (so use them!)
      //query a
      $query = "INSERT INTO posts (post_title, post_content, post_date, view_count) VALUES ('$title', '$content', '$date', '0')";
      $result = mysqli_query($conn, $query);

      if(mysqli_connect_errno()){
         echo mysqli_connect_error();
      }

      $last_post_id = mysqli_insert_id($conn);
      echo $last_post_id;

      //get post id
      //insert post in user_posts
      $query = "INSERT INTO user_posts (post_id, user_id) VALUES ('$last_post_id', '" . $_SESSION['id'] ."')";
      $result = mysqli_query($conn, $query);


      if (isset($tags)){
         if ($tags > " "){
            $tags = str_replace(' ', '', $tags);
            $tags = strtoupper($tags);
            $tagArray = explode("#", $tags);

            //select tags
            foreach ($tagArray as $tag){
               $query = "SELECT * FROM tags WHERE tag_name = '$tag'";
               $result = mysqli_query($conn, $query);
               if (mysqli_num_rows($result)==0){
                  $queryinsertTag = "INSERT INTO tags (tag_name) VALUES ('$tag')";
                  $tagInsertResult = mysqli_query($conn, $queryinsertTag);
                  $tag_id = mysqli_insert_id($conn);
               }
               else {
                  while ($row = mysqli_fetch_assoc($result)){
                     $tag_id = $row['tag_id'];
                  }
               }
               $linkPostTagQuery = "INSERT INTO post_tags (post_id, tag_id) VALUES ('$last_post_id', '$tag_id')";
               $postTagResult = mysqli_query($conn, $linkPostTagQuery);
            }
         }
      }
      //3 queries, 1 for posts, user_posts and tags
   }
?>

<h1>New Post</h1>
<hr/>
<form name ="post" method="post" action="">
   <h2>Title</h2>
   <input type="text" name="title">

   <h2>Post Content</h2>
   <textarea name="content"></textarea>

   <h2>Tags</h2>
   <input type="text" name="tags">
   <br/>
   <br/>
   <p>Use #tagname, to enter a tag and seperate each tag with a space</p>
   <input type="submit" name="submit">
</form>

</body>
</html>
