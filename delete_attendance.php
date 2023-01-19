<?php
require_once "config.php";

$id = $_GET['id'];
$sql = "DELETE FROM attendance WHERE id=$id";
mysqli_query($link, $sql);
header("location: attendance_list.php");

?>