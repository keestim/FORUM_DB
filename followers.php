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



</body>
</html>
