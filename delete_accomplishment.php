<?php
require_once "config.php";

$id = $_GET['id'];
$sql = "DELETE FROM accomplishment WHERE id=$id";
mysqli_query($link, $sql);
header("location: accomplishments.php");

?>