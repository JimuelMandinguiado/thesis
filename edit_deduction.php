<?php
require_once "config.php";
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// get id from GET request 
$id = $_GET['id'];

$sql = "SELECT * FROM deductions WHERE employee_id = $id";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employee_id = $row['employee_id'];
        $name = $row['name'];
        $absent = $row['absent'];
        $late = $row['late'];
        $sss = $row['sss'];
        $cash_advance = $row['cash_advance'];
        $philhealth = $row['philhealth'];
        $allowance = $row['allowance'];
        $tax = $row['tax'];
    }
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $absent = $_POST['absent'];
    $late = $_POST['late'];
    $sss = $_POST['sss'];
    $cash_advances = $_POST['cashadv'];
    $philhealth = $_POST['philhealth'];
    $allowance = $_POST['allowance'];
    $tax = $_POST['tax'];

        $sql = "UPDATE deductions SET employee_id=?, name=?, absent=?, late=?, sss=?, cash_advance=?, philhealth=?,allowance=?, tax=? WHERE employee_id=?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssssssssi", $param_employee_id, $param_name, $param_absent, $param_late, $param_sss, $param_cash_advance, $param_philhealth,$param_allowance, $param_tax, $param_id);
            $param_employee_id = $employee_id;
            $param_name = $name;
            $param_absent = $absent;
            $param_late = $late;
            $param_sss = $sss;
            $param_cash_advance = $cash_advances;
            $param_philhealth = $philhealth;
            $param_allowance = $allowance;
            $param_tax = $tax;
            $param_id = $id;
            if(mysqli_stmt_execute($stmt)){
                header("location: deduction.php");
                exit();
            }else{
                echo "Something went wrong. Please try again later.";
            }
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<style>
  a {
        text-decoration: none;
        color: black;
    }
    .back {
        background-image: url(https://i.imgur.com/M8OzrmV.jpg);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        height: 500px;
    }
</style>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  <a class="navbar-brand" href="#">
      <img src="https://i.imgur.com/6hqDcBO.png" alt="" height="40" class="d-inline-block align-text-top">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="dashboard_sec.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="attendance_list.php">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="payroll_list.php">Payroll List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold active" aria-current="page"  href="deduction.php">Deduction List</a>
        </li>
      </ul>
      <li class="nav-item dropdown d-flex">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Logout</a></li>
          </ul>
        </li>
    </div>
  </div>
</nav>
<div class="container">
    <div class="list mt-5 p-3 border">
    <div class="d-flex justify-content-between mb-2">
        <p class="fw-bold">Update employee informations <i class="fas fa-edit"></i></p>
            
        </div>
        <form action="" method="post">
        <table class="table table-bordered">
        <thead>
            <tr>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Absent</th>
                <th scope='col'>Late</th>
                <th scope='col'>Allowance</th>
                <th scope='col'>Cash Advance</th>
                <th scope='col'>SSS</th>
                <th scope='col'>Philhealth</th>
                <th scope='col'>Tax</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>
                <tbody>
                <tr>
                    <th><input class="form-control form-control-sm" name="employeeid" type="text" value="<?php echo $id; ?>"></th>
                    <td><input class="form-control form-control-sm" name="name" type="text" value="<?php echo $name; ?>"></td>
                    <td><input class="form-control form-control-sm" name="absent" type="text" value="<?php echo $absent; ?>"></td>
                    <td><input class="form-control form-control-sm" name="laste" type="text" value="<?php echo $late; ?>"></td>
                    <td><input class="form-control form-control-sm" name="allowance" type="text" value="<?php echo $allowance; ?>"></td>
                    <td><input class="form-control form-control-sm" name="cashadv" type="text" value="<?php echo $cash_advance; ?>"></td>
                    <td><input class="form-control form-control-sm" name="sss" type="text" value="<?php echo $sss; ?>"></td>
                    <td><input class="form-control form-control-sm" name="philhealth" type="text" value="<?php echo $philhealth; ?>"></td>
                    <td><input class="form-control form-control-sm" name="tax" type="text" value="<?php echo $tax; ?>"></td>
                    <td>
                        <button type="submit" name="submit" class="btn btn-primary btn-sm">Update</button>
                    </td>
                </tr>
                </tbody>

        </table>
        </form>
    </div>
</div>

</body>
</html>