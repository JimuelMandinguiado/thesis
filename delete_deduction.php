<?php
require_once "config.php";

$id = $_GET['id'];
$sql = "DELETE FROM deductions WHERE id=$id";
mysqli_query($link, $sql);
header("location: deduction.php");

?>