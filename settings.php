<?php
   $host = "localhost";
   $sql_db = "forum";

   //@ prevents any error from being displayed from the sql query
   $conn = mysqli_connect($host, 'root', '', $sql_db);

   $date = date("Y-m-d");

   if (!$conn) {
       echo 'Could not connect to mysql';
   }

   session_start();

function DatabaseExists($conn){
   if(@mysqli_query("DESCRIBE `tags`"))
   {}
   else{
      $query = "CREATE TABLE tags(tag_id int NOT NULL AUTO_INCREMENT, tag_name varchar(30) NOT NULL,PRIMARY KEY (tag_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `users`"))
   {}
   else{
      $query = "CREATE TABLE users(user_id INT NOT NULL AUTO_INCREMENT, user_first_name VARCHAR(50) NOT NULL, user_last_name VARCHAR(50) NOT NULL, user_display_name VARCHAR(50) NOT NULL, user_email VARCHAR(100) NOT NULL, user_password VARCHAR(100) NOT NULL, user_dob DATE NOT NULL, PRIMARY KEY (user_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `posts`"))
   {}
   else{
      $query = "CREATE TABLE posts(post_id int NOT NULL AUTO_INCREMENT, post_title VARCHAR(30) NOT NULL, post_content VARCHAR(20000) NOT NULL, post_date DATE NOT NULL, modified_date DATE NULL, view_count int NOT NULL, PRIMARY KEY (post_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `post_tags`"))
   {}
   else{
      $query = "CREATE TABLE post_tags(post_id int NOT NULL, tag_id int NOT NULL, FOREIGN KEY (post_id) REFERENCES posts(post_id), FOREIGN KEY (tag_id) REFERENCES tags(tag_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `user_posts`"))
   {}
   else{
      $query = "CREATE TABLE user_posts(post_id int NOT NULL, user_id int NOT NULL, FOREIGN KEY (post_id) REFERENCES posts(post_id), FOREIGN KEY (user_id) REFERENCES users(user_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `comments`"))
   {}
   else{
      $query = "CREATE TABLE comments(comment_id int NOT NULL AUTO_INCREMENT, user_id int NOT NULL, comment_text VARCHAR(20000) NOT NULL, commented_date DATE NOT NULL, modified_date DATE NULL, reply_comment_id INT NULL, private boolean DEFAULT false, PRIMARY KEY (comment_id), FOREIGN KEY (reply_comment_id) REFERENCES comments(comment_id), FOREIGN KEY (user_id) REFERENCES users(user_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `comments`"))
   {}
   else{
      $query = "CREATE TABLE user_comments(post_id int NOT NULL, comment_id int NOT NULL, FOREIGN KEY (post_id) REFERENCES posts(post_id), FOREIGN KEY (comment_id) REFERENCES comments(comment_id))";
      $result = mysqli_query($conn, $query);
   }

   if(@mysqli_query("DESCRIBE `following`"))
   {}
   else{
      $query = "CREATE TABLE following(user_id int NOT NULL, followed_user_id int NOT NULL, FOREIGN KEY (user_id) REFERENCES users(user_id), FOREIGN KEY (followed_user_id) REFERENCES users(user_id))";
      $result = mysqli_query($conn, $query);
   }
}

function IsLoggedIn($conn){
   if (!isset($_SESSION['id'])){
      header("Location: ./login.php");
   }
   else {
      if ($_SESSION["id"] <= " "){
         header("Location: ./login.php");
      }
   }
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
       echo "<a class='tag' href='viewtag.php?tagid=$tag_id'>#" . $tag['tag_name'] . "</a>";
     }
     //return $tag_names_array;
  }
}



?>
