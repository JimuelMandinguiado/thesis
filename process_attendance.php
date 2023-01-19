<?php
date_default_timezone_set('Asia/Manila');
require_once "config.php";

$res = 0;

$ontime = "08:29:00 am"; //on time
$late = "08:59:00 am"; //late time
$absent = "09:00:00 am"; //absent time
$date = date("Y-m-d");

//Error messages
$attended_success = $absent_success = $late_success = "";


$employee_id = $_POST['employee_id'];
$check = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
$result = mysqli_query($link, $check);

//function for checking half day , full day
function check($time_in, $time_out){
    $time_in = strtotime($time_in);
    $time_out = strtotime($time_out);
    $diff = $time_out - $time_in;
    $hours = $diff / (60 * 60);
    if($hours >= 8){
        return "Full Day";
    }else{
        return "Half Day";
    }
}

if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
    $id = $row['id'];
    $employee_id = $row['employee_id'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $position = $row['position'];
    $address = $row['address'];
    $phone = $row['phone'];
    $birthday = $row['birthday'];
    $salary = $row['salary'];
    }
}
//check if employee exists
function check_employee($employee_id){
    global $link;
    $check = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
    $result = mysqli_query($link, $check);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}
//function to create a payroll record for each employee if on time , late or absent
//function arguments are employee id, name, status
function createPayroll($employee_id, $first,$last, $status){
    global $link;
    global $date;
    //get current net pay
    $get_payroll = "SELECT * FROM payroll WHERE employee_id = '$employee_id'";
    $result = mysqli_query($link, $get_payroll);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $net_pay = $row['net_pay'];
            $late = $row['late'];
            $absent = $row['absent'];
        }
    }
    if($status == "On Time"){
        //update payroll record
        $sql = "UPDATE payroll SET late = 0, absent = 0, net_pay = '$net_pay' + 500, date_inserted = '$date' WHERE employee_id = '$employee_id'";
        $payroll_list = "INSERT INTO payroll_list (employee_id, name, status, date_inserted, net_pay) VALUES ('$employee_id', '$first $last', '$status', '$date', 500)";
        mysqli_query($link, $sql);
        mysqli_query($link, $payroll_list);

    }else if($status == "Late"){
        
        $sql = "UPDATE payroll SET late = '$late' + 1, absent = 0, net_pay = '$net_pay' +  450, date_inserted = '$date' WHERE employee_id = '$employee_id'";
        $payroll_list = "INSERT INTO payroll_list (employee_id, name, status, date_inserted, net_pay) VALUES ('$employee_id', '$first $last', '$status', '$date', 450)";
        mysqli_query($link, $sql);
        mysqli_query($link, $payroll_list);
    }else if($status == "Absent"){
        $sql = "UPDATE payroll SET late = 0, absent = $absent + 1, net_pay = '$net_pay' + 0, date_inserted = '$date' WHERE employee_id = '$employee_id'";
        $payroll_list = "INSERT INTO payroll_list (employee_id, name, status, date_inserted, net_pay) VALUES ('$employee_id', '$first $last', '$status', '$date', 0)";
        mysqli_query($link, $sql);
        mysqli_query($link, $payroll_list);
    }
}
//function to check if employee is already present on the same date to avoid duplicate records if not present then create a record
function checkAttendance($employee_id){
    global $link;
    global $date;
    $sql = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND time_out IS NOT NULL AND date = '$date'";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}
//function to check for morning attendance
function checkMorningAttendance($employee_id){
    global $link;
    global $date;
    $sql = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND time_in IS NOT NULL AND date = '$date'";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}
//function for timestampdiff
function timeDiff($time_in){
    global $ontime;
    global $late;
    global $absent;
    
    if($time_in <= $ontime){
        $status = "On Time";
        return $status;
    }else if($time_in > $ontime && $time_in <= $late){
        $status = "Late";
        return $status;
    }else if($time_in > $late && $time_in <= $absent){
        $status = "Absent";
        return $status;
    }else{
        $status = "Absent";
        return $status;
    }

}
//add day

if(check_employee($employee_id)){
    $res = "success";
    if(checkAttendance($employee_id)){
        $res = 1;
        return $res;
    }else{
        if(checkMorningAttendance($employee_id)){ // present in the morning
            $time_in = date("Y:m:d h:i:sa");
            $sql = "UPDATE attendance SET time_out = '$time_in' WHERE employee_id = '$employee_id' AND date = '$date'";
            if(mysqli_query($link, $sql)){
                echo "Attendance updated successfully.";
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            }
        }else{  // not present in the morning - create a record
            $time_in = date("Y-m-d H:i:sa");
            $curr_time = date("H:i:sa");
            if(timeDiff($curr_time) == "On Time"){
                $sql = "INSERT INTO attendance (employee_id,firstname,lastname, time_in, date, status) VALUES ('$employee_id','$firstname','$lastname', '$time_in', '$date', 'On Time')";
                if(mysqli_query($link, $sql)){
                    echo "Attendance created successfully.";
                    createPayroll($employee_id, $firstname ,$lastname, "On Time");
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }
            }else if(timeDiff($curr_time) == "Late"){
                $sql = "INSERT INTO attendance (employee_id,firstname,lastname, time_in, date, status) VALUES ('$employee_id','$firstname','$lastname', '$time_in', '$date', 'Late')";
                if(mysqli_query($link, $sql)){
                    echo "Attendance created successfully.";
                    createPayroll($employee_id, $firstname,$lastname, "Late");
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }
            }else if(timeDiff($curr_time) == "Absent"){
                $sql = "INSERT INTO attendance (employee_id,firstname,lastname, time_in, date, status) VALUES ('$employee_id','$firstname','$lastname', '$time_in', '$date', 'Absent')";
                if(mysqli_query($link, $sql)){
                    echo "Attendance created successfully.";
                    createPayroll($employee_id, $firstname, $lastname, "Absent");
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }
            }
        }
    }
    return $res;
}else{
    $res = "404";
    return $res;
}


?>