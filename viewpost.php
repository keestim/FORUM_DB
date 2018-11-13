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
   $query_error = false;

   if (isset($_GET['post_id'])){

      //if someone has tries to comment
      if (isset($_POST['submit'])){
          $comment_data = $_POST['comment_data'];
          $comment_data = check_isset($comment_data);

         $query = "INSERT INTO comments (user_id, comment_text, commented_date)
         VALUES ('" . $_SESSION['id'] ."', '" . $comment_data . "', '$date')";
         $result = mysqli_query($conn, $query);
         if (mysqli_error($conn) > ""){
           $query_error = true;
         }

         $last_comment_id = mysqli_insert_id($conn);

         $post_id = $_GET['post_id'];
         $query = "INSERT INTO user_comments (post_id, comment_id)
         VALUES ('$post_id', '$last_comment_id')";
         $result = mysqli_query($conn, $query);
         if (mysqli_error($conn) > ""){
           $query_error = true;
         }
      }

      $query = "SELECT * FROM posts
      INNER JOIN user_posts ON user_posts.post_id = posts.post_id
      INNER JOIN users ON user_posts.user_id = users.user_id
      WHERE user_posts.post_id ='" . $_GET["post_id"] . "'";

      $result = mysqli_query($conn, $query);
      if (mysqli_error($conn) > ""){
        $query_error = true;
      }

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
           //if the user viewing post isn't the user who authored the post, then the view count is increased by one
           $newViews = $views + 1;
           $updateViewsQuery = "UPDATE posts SET view_count = $newViews
           WHERE post_id = '" . $_GET["post_id"] ."'";
           $update = mysqli_query($conn, $updateViewsQuery);
           if (mysqli_error($conn) > ""){
             $query_error = true;
           }
         }
      }
  }
   else {
      header("Location: ./index.php");
   }


   if (isset($_POST['reply'])){
     $reply_comment = $_POST['reply_comment'];
     $reply_comment = check_isset($reply_comment);

     $reply = $_POST['reply'];
     $reply = check_isset($reply);

     $query = "INSERT INTO comments (user_id, comment_text, commented_date, reply_comment_id)
     VALUES ('" . $_SESSION['id'] ."', '" . $reply_comment . "', '$date', '" . $reply . "')";
     $result = mysqli_query($conn, $query);
     if (mysqli_error($conn) > ""){
       $query_error = true;
     }

     $last_comment_id = mysqli_insert_id($conn);

     $post_id = $_GET['post_id'];
     $query = "INSERT INTO user_comments (post_id, comment_id)
     VALUES ('$post_id', '$last_comment_id')";
     $result = mysqli_query($conn, $query);
     if (mysqli_error($conn) > ""){
       $query_error = true;
     }
   }

   if ($query_error){
     mysqli_rollback($conn);
   }
   else {
     mysqli_commit($conn);
   }

   if (isset($_POST['edit_post'])){
     header("Location: edit_post.php?post_id=" . $_GET['post_id']);
   }

   if (isset($_POST['delete_post'])){
     DeletePost($conn, $_GET['post_id']);
     Header("Location: index.php");
   }

   if (isset($_POST['delete_comment'])){
     DeleteComment($conn, $_POST['delete_comment']);
     Header("Location: viewpost.php?post_id=" . $_GET['post_id']);
   }


 ?>
<div class="post_whole">
  <h1><?php echo $title; ?></h1>
  <p>Date posted: <?php echo $date; ?></p>

  <p>Views : <?php echo $views; ?></p>
  <p><?php getPostTags($conn, $_GET['post_id']) ?></p>

  <p>Posted by: <a href = viewprofile.php?profile_id=<?php echo $user_id . ">" . $display_name; ?></a></p>

  <br/>
  <hr/>
  <p align='left'><?php echo nl2br($content); ?></p>
  <hr/>
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

<div class="post_summary">
  <h2>Comment</h2>
  <form method="post" name="comment" action="">
     <textarea name="comment_data"></textarea>
     <br/>
     <br/>
     <input type="submit" name="submit">
  </form>
</div>

<?php
  PrintReplies($conn, $_GET['post_id']);
?>

<script src='./scripts/accordion.js'>
</script>

</body>
</html>
