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

   if (isset($_GET['post_id'])){

      //if someone has tries to comment
      if (isset($_POST['submit'])){
         $query = "INSERT INTO comments (user_id, comment_text, commented_date) VALUES ('" . $_SESSION['id'] ."', '" . $_POST['comment_data'] . "', '$date')";
         $result = mysqli_query($conn, $query);
         $last_comment_id = mysqli_insert_id($conn);

         $post_id = $_GET['post_id'];
         $query = "INSERT INTO user_comments (post_id, comment_id) VALUES ('$post_id', '$last_comment_id')";
         $result = mysqli_query($conn, $query);

      }

      $query = "SELECT * FROM posts WHERE post_id = '" . $_GET["post_id"] ."'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result)==0){
         header("Location: ./home.php");
      }
      else {
         while ($post = mysqli_fetch_assoc($result)){
            $title = $post['post_title'];
            $content = $post['post_content'];
            $date = $post['post_date'];
            $views = $post['view_count'];
         }
         $newViews = $views + 1;
         $updateViewsQuery = "UPDATE posts SET view_count = $newViews WHERE post_id = '" . $_GET["post_id"] ."'";
         $update = mysqli_query($conn, $updateViewsQuery);
      }

      $comment_ids = array();
      $query = "SELECT * FROM user_comments WHERE post_id = '" . $_GET['post_id'] . "'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0){
         while ($post_comment_id = mysqli_fetch_assoc($result)){
            array_push($comment_ids, $post_comment_id["comment_id"]);
         }
      }

      //get Tags
        $query = "SELECT * FROM post_tags WHERE post_id = '" . $_GET["post_id"] ."'";
        $result = mysqli_query($conn, $query);

        $tagsArray = array();


        if (mysqli_num_rows($result)==0){
           //header("Location: ./home.php");
        }
        else {
           while ($row = mysqli_fetch_assoc($result)){
             $querytagName = "SELECT * FROM tags WHERE tag_id = '" .  $row['tag_id'] . "'";
             $queryNameResult = mysqli_query($conn, $querytagName);
             if (mysqli_num_rows($result)==0){

             }
             else {
               while ($tag_data = mysqli_fetch_assoc($queryNameResult)){
                 $name = $tag_data['tag_name'];
                 array_push($tagsArray, $name);
               }
             }
           }
         }
  }
   else {
      header("Location: ./home.php");
   }


   if (isset($_POST['reply'])){
     $query = "INSERT INTO comments (user_id, comment_text, commented_date, reply_comment_id) VALUES ('" . $_SESSION['id'] ."', '" . $_POST['reply_comment'] . "', '$date', '" . $_POST['reply'] . "')";
     $result = mysqli_query($conn, $query);
   }
 ?>

<h1><?php echo $title; ?></h1>
<p>Date posted: <?php echo $date; ?></p>
<p>Views : <?php echo $views; ?></p>
<p>Tags:</p>
<p>
<?php
foreach($tagsArray as $tag){
  if ($tag > ""){
    echo "#" . $tag;
    echo "<br/>";
  }
}
?></p>
<br/>
<p><?php echo $content; ?></p>
<br/>
<h2>Comment</h2>
<form method="post" name="comment" action="">
   <textarea name="comment_data"></textarea>
   <br/>
   <br/>
   <input type="submit" name="submit">
</form>

<?php

echo "num comments: " . count($comment_ids);
if (count($comment_ids) > 0){
   foreach ($comment_ids as $id){
     echo "comment id: " . $id;
      $query = "SELECT * FROM comments WHERE comment_id = '$id' AND reply_comment_id IS NULL";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) != 0){
         while ($row = mysqli_fetch_assoc($result)){
            echo "<p>" . $row['commented_date'] . "</p>";
            echo "<p>" . $row['comment_text'] . "</p>";

            //dispplay replies
            $sub_comment = $row['comment_id'];
            $sub_comment_query = "SELECT * FROM comments WHERE reply_comment_id = '$sub_comment'";
            $find_replies = mysqli_query($conn, $sub_comment_query);
            if (mysqli_num_rows($find_replies) != 0){
               while ($comment_data = mysqli_fetch_assoc($find_replies)){
                  echo "<p>" . $comment_data['commented_date'] . "</p>";
                  echo "<p>" . $comment_data['comment_text'] . "</p>";

                  //do not display reply button!
                  $sub_comment_query = $comment_data['reply_comment_id'];
               }
            }

            echo "<h3>Reply</h3>";
            echo "<form name='reply' method='post' action=''><textarea name='reply_comment'></textarea>
            <br/>
            <br/>
            <button type='submit' name='reply' value='" . $row['comment_id'] . "'>REPLY</button></form>";

         }
      }
   }
}
?>

</body>
</html>
