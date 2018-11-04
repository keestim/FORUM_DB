<?php

function DisplayPostSummary($assoc, $conn){
   echo "<div class='post_summary'>";
   echo "<h2>" . $assoc['post_title'] . "</h2>";
   echo "<p>" . $assoc['post_date'] . "</p>";
   echo "<p>" . getPostTags($conn, $assoc['post_id']) . "</p><hr/>";

   if (strlen($assoc['post_content']) > 100){
   echo "<p>" . nl2br(substr($assoc['post_content'], 95)) . "...</p><hr/>";
  }
  else {
    echo "<p>" . nl2br($assoc['post_content']) . "</p><hr/>";
  }
   echo "<p><a href=viewpost.php?post_id=" . $assoc['post_id'] . ">VIEW POST</a></p>";
   echo "</div>";
}

function getPostTags($conn, $post_id){
  $query = "SELECT * FROM post_tags
          INNER JOIN tags ON post_tags.tag_id = tags.tag_id
          WHERE post_tags.post_id = '$post_id'";

  $result = mysqli_query($conn, $query);
  $tag_names_array = array();

  if (mysqli_num_rows($result) == 0)
  {
  }
  else {
     while ($tag = mysqli_fetch_assoc($result)){
       $tag_id = $tag['tag_id'];
       echo "<a class='tag' href='viewtag.php?tag_id=$tag_id'>#" . $tag['tag_name'] . "</a>";
     }
  }
}


function DeletePost($conn, $post_id){
  $query_a = "DELETE FROM user_posts WHERE post_id = '$post_id'";
  $result = mysqli_query($conn, $query_a);

  $query = "SELECT * FROM user_comments WHERE post_id = '$post_id'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 0)
  {
  }
  else {
    $query_b = "DELETE FROM user_comments WHERE post_id =  '$post_id'";
    $result_b = mysqli_query($conn, $query_b);

     while ($output = mysqli_fetch_assoc($result)){
       $comment_id = $output['comment_id'];
       $query_f = "DELETE FROM comments WHERE comment_id = '$comment_id'";
       $query_f_result = mysqli_query($conn, $query_f);
     }
  }

  $query_c = "DELETE FROM post_tags WHERE post_id ='$post_id'";
  $result = mysqli_query($conn, $query_c);

  $query_e = "DELETE FROM posts WHERE post_id = '$post_id'";
  $result = mysqli_query($conn, $query_e);
}

function PrintReplies($conn, $post_id){
  $query = "SELECT * FROM user_comments
  INNER JOIN comments ON user_comments.comment_id = comments.comment_id
  INNER JOIN users ON comments.user_id = users.user_id
  WHERE user_comments.post_id = '$post_id' AND comments.reply_comment_id IS NULL";

  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) != 0){
  echo "Number of Comments: " . mysqli_num_rows($result) . "<br/>";
    while ($output = mysqli_fetch_assoc($result)){
        //INITIAL DATA
        echo "<div class='comment'>";
        DisplayComment($output);
        GetReplies($conn, $output['comment_id']);
        echo "</div>";
    }
  }
}

function GetReplies($conn, $reply_id){
  $query = "SELECT * FROM comments
  INNER JOIN users ON comments.user_id = users.user_id
  WHERE reply_comment_id = '$reply_id'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) != 0){
    echo "<button class='accordion'>View Replies</button>";
    echo "<div class='panel'>";
    while ($output = mysqli_fetch_assoc($result)){
      echo "<div class='reply_container'>";
      echo "<div class='reply_comment'>";
      DisplayComment($output);
      GetReplies($conn, $output['comment_id']);
      echo "</div>";
      echo "</div>";
    }
    echo "</div>";
  }
}

function DeleteComment($conn, $comment_id){
  $query_a = "DELETE FROM user_comments WHERE comment_id = '$comment_id'";
  $result = mysqli_query($conn, $query_a);

  $query_b = "DELETE FROM comments WHERE reply_comment_id = '$comment_id'";
  $result = mysqli_query($conn, $query_b);

  $query_c = "DELETE FROM comments WHERE comment_id = '$comment_id'";
  $result = mysqli_query($conn, $query_c);
}

function DisplayComment($output_array){
  echo "<p><strong>" . $output_array['comment_text'] . "</strong><p/>";
  echo "<p>posted: " . $output_array['commented_date'] . "</p>";
  echo "<p>Written by: <a href='viewprofile.php?profile_id=" . $output_array['user_id'] . "'>" . $output_array['user_display_name'] . "</a></p>";

  echo "<button class='accordion'>Reply</button>";
  echo "<div class='panel'>";
  echo "<form name='reply' method='post' action=''>
  <br/>
  <textarea name='reply_comment'></textarea>
  <br/>
  <br/>
  <button type='submit' name='reply' value='" . $output_array['comment_id'] . "'>REPLY</button></form>";
  echo "</div>";
  if ($output_array['user_id'] == $_SESSION['id']){
    echo "<form method='post' action=''>";
    echo "<button type='submit' class='modify_left' value='" . $output_array['comment_id'] . "' name='delete_comment'>DELETE</button>";
    echo "</form>";
  }
}


function check_isset($input)
{
  if (isset($input)){
    $result = trim($input);
    $result = htmlspecialchars($input);
  }
  else {
    $result = " ";
  }
  return $result;
}

 ?>
