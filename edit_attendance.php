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
// update the record
if (isset($_POST['submit'])) {
    $date = date('Y-m-d' );
    $log_date = $_POST['log_date'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $status = $_POST['status'];

    //check for empty fields
    if (empty($time_in) || empty($time_out) || empty($status) || empty($log_date)) {
        echo "Please fill in all fields";
    } else {
        // prepare an update statement
        $sql = "UPDATE attendance SET date = ?, time_in = ?, time_out = ?, status = ? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_date, $param_time_in, $param_time_out, $param_status, $param_id);
            // set parameters
            $param_date = $log_date;
            $param_time_in = $time_in;
            $param_time_out = $time_out;
            $param_status = $status;
            $param_id = $id;
            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // records updated successfully. Redirect to landing page
                header("location: attendance_list.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }

}

// get data from database
$sql = "SELECT * FROM attendance WHERE id=$id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);

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
          <a class="nav-link active" href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page" href="#">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="payroll_list.php">Payroll List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Deduction List</a>
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
        <p class="fw-bold">Update employee attendance <i class="fas fa-edit"></i></p>
            
        </div>
        <form action="" method="post">
        <table class="table table-bordered">
        <thead>
            <tr>
                <th scope='col'>Log Date</th>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Time In</th>
                <th scope='col'>Time Out</th>
                <th scope='col'>Status</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>
                <tbody>
                <tr>                    <td><input class="form-control form-control-sm" name="log_date" type="text" value="<?php echo $row['date']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="employee_id" type="text" value="<?php echo $row['employee_id']; ?>" disabled></td>
                    <td><input class="form-control form-control-sm" name="name" type="text" value="<?php echo $row['firstname']; ?>" disabled></td>
                    <td><input class="form-control form-control-sm" name="time_in" type="text" value="<?php echo $row['time_in']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="time_out" type="text" value="<?php echo $row['time_out']; ?>"></td>
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