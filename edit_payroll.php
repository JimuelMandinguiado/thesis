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
// get data from database
$sql = "SELECT * FROM payroll WHERE employee_id=$id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$employee_id = $row['employee_id'];
// update the record
if (isset($_POST['submit'])) {
    $employeeid = $_POST['employeeid'];
    $name = $_POST['name'];
    $late = $_POST['late'];
    $absent = $_POST['absent'];
    $cash_adv = $_POST['cashadv'];
    $deduction = $_POST['deduction'];
    $net = $_POST['net'];
    $status = $_POST['status'];

        // prepare an update statement
        $sql = "UPDATE payroll SET employee_id='$employee_id', name='$name', late='$late', absent='$absent', cash_advance='$cash_adv', deduction='$deduction', net_pay='$net', status='$status' WHERE employee_id='$row[employee_id]'";
        if (mysqli_query($link, $sql)) {
            header("location: payroll_list.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }


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
          <a class="nav-link active"href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="attendance_list.php">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page"  href="payroll_list.php">Payroll List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="deduction.php">Deduction List</a>
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
                <th scope='col'>Cash Advance</th>
                <th scope='col'>Total Deduction</th>
                <th scope='col'>Net Pay</th>
                <th scope='col'>Status</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>
                <tbody>
                <!-- +----+-------------+----------------+------------+------+--------+--------------+-----------+---------+----------+------+---------+---------------------+
| id | employee_id | name           | total_work | late | absent | cash_advance | deduction | net_pay | status   | date | halfday | date_inserted       |
+----+-------------+----------------+------------+------+--------+--------------+-----------+---------+----------+------+---------+---------------------+
|  9 |           1 | Nick Fury      |       NULL |    0 |      0 |         NULL |       250 |    1200 | Computed | NULL |    NULL | 2022-11-30 00:00:00 |
| 10 |           2 | Jimmy Mandings |       NULL |    1 |      0 |         NULL |      NULL |     450 | NULL     | NULL |    NULL | 2022-11-28 00:00:00 |
| 11 |           3 | Elon Stark     |       NULL |    1 |      0 |         NULL |      NULL |     450 | NULL     | NULL |    NULL | 2022-11-28 00:00:00 |
+----+-------------+----------------+------------+------+--------+--------------+-----------+---------+----------+------+---------+---------------------+ -->
                <tr>
                    <th><input class="form-control form-control-sm" name="employeeid" type="text" value="<?php echo $row['employee_id']; ?>"></th>
                    <td><input class="form-control form-control-sm" name="name" type="text" value="<?php echo $row['name']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="late" type="text" value="<?php echo $row['late']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="absent" type="text" value="<?php echo $row['absent']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="cashadv" type="text" value="<?php echo $row['cash_advance']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="deduction" type="text" value="<?php echo $row['deduction']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="net" type="text" value="<?php echo $row['net_pay']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="status" type="text" value="<?php echo $row['status']; ?>"></td>
                    <td>
                        <button type="submit" name="submit" class='btn btn-sm btn-danger'>Save</button>
                    </td>
                </tr>
                </tbody>

        </table>
        </form>
    </div>
</div>

</body>
</html>