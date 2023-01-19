<?php
require_once "config.php";

$id = $_GET['id'];
$sql = "DELETE FROM employee WHERE id=$id";
mysqli_query($link, $sql);
header("location: employee_list_admin.php");

?>