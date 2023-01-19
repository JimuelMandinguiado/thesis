<?php
date_default_timezone_set('Asia/Manila');
require_once "config.php";

$res = 0;

$ontime = "12:00:00 pm"; //on time
$late = "12:08:00 pm"; //late time
$absent = "12:50:00 pm"; //absent time
$date = date("Y-m-d");

//function to create a payroll record for each employee if on time , late or absent
//function arguments are employee id, name, status
function createPayroll($employee_id, $name, $status){
    global $link;
    global $date;
    if($status == "On Time"){
        $sql = "INSERT INTO payroll (employee_id, name, status, net_pay) VALUES ('$employee_id', '$name', '$status', 500)";
        if(mysqli_query($link, $sql)){
            echo "Payroll record created successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }else if($status == "Late"){
        $sql = "INSERT INTO payroll (employee_id, name, status, net_pay) VALUES ('$employee_id', '$name', '$status', 400)";
        if(mysqli_query($link, $sql)){
            echo "Payroll record created successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }else if($status == "Absent"){
        $sql = "INSERT INTO payroll (employee_id, name, status, net_pay) VALUES ('$employee_id', '$name', '$status', 0)";
        if(mysqli_query($link, $sql)){
            echo "Payroll record created successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
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

$employee_id = $_POST['employee_id'];
$check = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
$result = mysqli_query($link, $check);
$count = mysqli_num_rows($result);


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
    // $attended = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND time_in IS NOT NULL AND time_out IS NOT NULL AND date = '$date'";
    // $result = mysqli_query($link, $attended);
    
    
    if ($count == 1) { //if employee exists
        if (checkAttendance($employee_id)) { //if employee has already attended
            $res = 2;
        } else { //if employee has not attended
            //check if employee has time in
            $time_in = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND time_in IS NOT NULL";
            $result = mysqli_query($link, $time_in);

            if (mysqli_num_rows($result) > 0) { //if employee has time in
                //check if employee is absent
                $absent_check = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND time_in IS NOT NULL AND time_out IS NULL AND date = '$date' AND status_check = 'Absent'";
                $result = mysqli_query($link, $absent_check);
                if(mysqli_num_rows($result) > 0){
                    $res = 3;
                }else{
                    $time_out = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND time_out IS NOT NULL";
                    $result = mysqli_query($link, $time_out);
                    if (mysqli_num_rows($result) > 0) { //if employee has time out
                        $res = 3;
                    } else { //if employee has no time out
                        //time difference function
                        $time_diff = "SELECT time_in, TIMESTAMPDIFF(MINUTE, time_in, NOW()) AS status FROM attendance WHERE employee_id = '$employee_id' AND time_in IS NOT NULL";
                        $result = mysqli_query($link, $time_diff);
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)){
                                $time_in = $row['time_in'];
    
                                //8rs above : Overtime , 7hrs below : Undertime, 7hrs to 8hrs : Regular
                                if ($row['status'] > 480) {
                                    $y = $row['status']/60;
                                    $status = "Overtime" . " " . number_format($y,2,'.','') . " hours";
                                } elseif ($row['status'] < 420) {
                                    $y = $row['status']/60;
                                    $status = "Undertime" . " " . number_format($y,2,'.','') . " hours";
                                } else {
                                    $y = $row['status']/60;
                                    $status = "Regular" . " " . number_format($y,2,'.','') . " hours";
                                }
                            }
                        }
                        $sql = "UPDATE attendance SET time_out = NOW(), status = '$status', date = '$date' WHERE employee_id = '$employee_id'";
                        if (mysqli_query($link, $sql)) {
                            $res = 1;
                        } else {
                            $res = 4;
                        }
                    } 
                }
                

            } else { //if employee has no time in
                //get current time h:i:s
                $time = date("H:i:sa");
                // check if time is greater than 8:30:00
                if ($time > $ontime && $time < $late) {
                    $status = "On Time";
                } elseif ($time > $late && $time < $absent) {
                    $status = "Late";
                } else if($time > $absent) {
                    $status = "Absent";
                }
                $sql = "INSERT INTO attendance (employee_id,date, firstname, lastname, time_in,status,status_check) VALUES ('$employee_id','$date', '$firstname', '$lastname', NOW(), '$status', '$status')";
                if (mysqli_query($link, $sql)) {
                    createPayroll($employee_id, $firstname." ".$lastname, $status);
                    $res = 1;
                } else {
                    $res = 4;
                }

            }
        }
    } else { //if employee does not exist
        $res = 4;
    }

}



?>