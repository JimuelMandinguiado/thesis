<?php
$servername = "127.0.0.1";
$username = "";
$password = "";
$db = "thesis";

// Create connection
$link = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$link) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>