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
      $query = "SELECT * FROM posts
      INNER JOIN user_posts ON user_posts.post_id = posts.post_id
      INNER JOIN users ON user_posts.user_id = users.user_id
      WHERE user_posts.post_id ='" . $_GET["post_id"] . "'
      AND user_posts.user_id = '" . $_SESSION["id"] . "'";

      $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
      if (mysqli_error($conn) > ""){
        $query_error = true;
      }

      if (mysqli_num_rows($result)==0){
         header("Location: ./index.php");
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
         if (mysqli_error($conn) > ""){
           $query_error = true;
         }
       }
     }
   }
   else{
     header("Location: ./index.php");
   }

   if (isset($_POST['undo_post'])){
     header("Location: viewpost.php?post_id=" . $_GET['post_id']);
   }

   if (isset($_POST['save_post'])){
     $post_content = $_POST['content'];
     $post_content = check_isset($post_content);

     $post_title = $_POST['title'];
     $post_title = check_isset($post_title);

     //updates the post with the user's inputted data
     $query = "UPDATE posts SET modified_date='$date', post_title='$post_title', post_content='$post_content' WHERE post_id =" . $_GET['post_id'];
     $result = mysqli_query($conn, $query);
     if (mysqli_error($conn) > ""){
       $query_error = true;
     }

     header("Location: viewpost.php?post_id=" . $_GET['post_id']);
   }

   if (isset($_POST['delete_post'])){
     DeletePost($conn, $_GET['post_id']);
     Header("Location: index.php");
   }

   if ($query_error){
     mysqli_rollback($conn);
   }
   else {
     mysqli_commit($conn);
   }
 ?>

   <div class="post_whole">
   <form method="post" action="">
   <h1><?php echo "<input type='text' class='title_input' name='title' value='" . $title . "'/>"; ?></h1>
   <p>Date posted: <?php echo $date; ?></p>

   <p>Views : <?php echo $views; ?></p>
   <p><?php getPostTags($conn, $_GET['post_id']) ?></p>

   <p>Posted by: <a href = viewprofile.php?profile_id=<?php echo $user_id . ">" . $display_name; ?></a></p>

   <br/>
   <p><?php echo "<textarea name='content' data-adaptheight>" . $content . "</textarea>"; ?></p>
   <?php
     echo "<p>";
     echo "<button type='submit' class='modify' name='delete_post'>DELETE</button>";
     echo "<button type='submit' class='modify' name='save_post'>SAVE</button>";
     echo "<button type='submit' class='modify' name='undo_post'>UNDO</button>";
     echo "</p>";
   ?>
   </form>
   </div>

   <script>(function() {
       function adjustHeight(textareaElement, minHeight) {
           var diff = parseInt(window.getComputedStyle(el).height, 10) - el.clientHeight;
           el.style.height = 0;
           el.style.height = Math.max(minHeight, el.scrollHeight + diff) + 'px';
       }

       var textAreas = document.querySelectorAll('textarea[data-adaptheight]');

       for (var i = 0, l = textAreas.length; i < l; i++) {
           var el = textAreas[i];
           el.style.boxSizing = el.style.mozBoxSizing = 'border-box';
           el.style.overflowY = 'hidden';
           var minHeight = el.scrollHeight;

           el.addEventListener('input', function() { adjustHeight(el, minHeight); });
           window.addEventListener('resize', function() { adjustHeight(el, minHeight); });

           adjustHeight(el, minHeight);
       }
   }());
 </script>

</body>
</html>
