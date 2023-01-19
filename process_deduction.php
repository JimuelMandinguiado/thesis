<?php 
date_default_timezone_set('Asia/Manila');
require_once "config.php";

    $id = $_GET['id'];
    $sql = "SELECT * FROM payroll WHERE employee_id = '$id'";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $net_pay = $row['net_pay'];
            $late = $row['late'];
            $absent = $row['absent'];
            $name = $row['name'];
        }
    }

    $deduction = $late + $absent;
    $net_pay = $net - $deduction;
    $sss = $_POST['sss'];
    $philhealth = $_POST['philhealth'];
    $sql = "INSERT INTO deductions (employee_id,name, sss,philhealth) VALUES ('$id','$name', '$sss', '$philhealth')";
    if(mysqli_query($link, $sql)){
        header("Location: deduction.php");
    }


?>