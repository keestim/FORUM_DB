<?php

function DisplayPostSummary($assoc, $conn){
   echo "<div class='post_summary'>";
   echo "<h2>" . $assoc['post_title'] . "</h2>";
   echo "<p>" . $assoc['post_date'] . "</p>";
   echo "<p>" . getPostTags($conn, $assoc['post_id']) . "</p>";
   echo "<p>" . nl2br($assoc['post_content']) . "</p>";
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

 ?>
