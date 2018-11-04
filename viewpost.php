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

   if (isset($_GET['post_id'])){

      //if someone has tries to comment
      if (isset($_POST['submit'])){
         $query = "INSERT INTO comments (user_id, comment_text, commented_date)
         VALUES ('" . $_SESSION['id'] ."', '" . $_POST['comment_data'] . "', '$date')";
         $result = mysqli_query($conn, $query);
         $last_comment_id = mysqli_insert_id($conn);

         $post_id = $_GET['post_id'];
         $query = "INSERT INTO user_comments (post_id, comment_id)
         VALUES ('$post_id', '$last_comment_id')";
         $result = mysqli_query($conn, $query);
      }

      $query = "SELECT * FROM posts
      INNER JOIN user_posts ON user_posts.post_id = posts.post_id
      INNER JOIN users ON user_posts.user_id = users.user_id
      WHERE user_posts.post_id ='" . $_GET["post_id"] . "'";

      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result)==0){
         //header("Location: ./index.php");
      }
      else {
         while ($post = mysqli_fetch_assoc($result)){
            $title = $post['post_title'];
            $content = $post['post_content'];
            $date = $post['post_date'];
            $views = $post['view_count'];
            $display_name = $post['user_display_name'];
            $user_id = $post['user_id'];
         }
         if ($_SESSION['id'] != $user_id){
           $newViews = $views + 1;
           $updateViewsQuery = "UPDATE posts SET view_count = $newViews
           WHERE post_id = '" . $_GET["post_id"] ."'";
           $update = mysqli_query($conn, $updateViewsQuery);
         }
      }

      $comment_ids = array();
      $query = "SELECT * FROM user_comments
      WHERE post_id = '" . $_GET['post_id'] . "'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0){
         while ($post_comment_id = mysqli_fetch_assoc($result)){
            array_push($comment_ids, $post_comment_id["comment_id"]);
         }
      }
  }
   else {
      header("Location: ./index.php");
   }


   if (isset($_POST['reply'])){
     $query = "INSERT INTO comments (user_id, comment_text, commented_date, reply_comment_id)
     VALUES ('" . $_SESSION['id'] ."', '" . $_POST['reply_comment'] . "', '$date', '" . $_POST['reply'] . "')";
     $result = mysqli_query($conn, $query);
   }

   if (isset($_POST['edit_post'])){
     header("Location: edit_post.php?post_id=" . $_GET['post_id']);
   }
 ?>
<div class="post_whole">
<h1><?php echo $title; ?></h1>
<p>Date posted: <?php echo $date; ?></p>

<p>Views : <?php echo $views; ?></p>
<p><?php getPostTags($conn, $_GET['post_id']) ?></p>

<p>Posted by: <a href = viewprofile.php?profile_id=<?php echo $user_id . ">" . $display_name; ?></a></p>

<br/>
<p><?php echo $content; ?></p>
<?php
  if ($user_id == $_SESSION['id']){
    echo "<form method='post' action=''>";
    echo "<p>";
    echo "<button type='submit' class='modify' name='delete_post'>DELETE</button>";
    echo "<button type='submit' class='modify' name='edit_post'>EDIT</button>";
    echo "</p>";
    echo "</form>";
  }
?>

</div>
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
