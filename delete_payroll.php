<?php
require_once "config.php";

$id = $_GET['id'];
$sql = "DELETE FROM payroll WHERE id=$id";
mysqli_query($link, $sql);
header("location: payroll_list.php");

?>