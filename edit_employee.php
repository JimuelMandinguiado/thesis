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
    $employeeid = $_POST['employeeid'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    $salary = $_POST['salary'];

    //check for empty fields
    if (empty($firstname) || empty($lastname) || empty($position) || empty($address) || empty($phone) || empty($birthday) || empty($salary)) {
        echo "Please fill in all fields";
    } else {
        // prepare an update statement
        $sql = "UPDATE employee SET employee_id=?, firstname=?, lastname=?, position=?, address=?, phone=?, birthday=?, salary=? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssi", $param_employeeid, $param_firstname, $param_lastname, $param_position, $param_address, $param_phone, $param_birthday, $param_salary, $param_id);
            // set parameters
            $param_employeeid = $employeeid;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_position = $position;
            $param_address = $address;
            $param_phone = $phone;
            $param_birthday = $birthday;
            $param_salary = $salary;
            $param_id = $id;
            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // records updated successfully. Redirect to landing page
                header("location: employee_list.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }

}

// get data from database
$sql = "SELECT * FROM employee WHERE id=$id";
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
          <a class="nav-link active" aria-current="page" href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Payroll List</a>
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
        <p class="fw-bold">Update employee informations <i class="fas fa-edit"></i></p>
            
        </div>
        <form action="" method="post">
        <table class="table table-bordered">
        <thead>
            <tr>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Firstname</th>
                <th scope='col'>Lastname</th>
                <th scope='col'>Position</th>
                <th scope='col'>Birthdate</th>
                <th scope='col'>Phone Number</th>
                <th scope='col'>Home Address</th>
                <th scope='col'>Daily Salary</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>
                <tbody>
                <tr>
                    <th><input class="form-control form-control-sm" name="employeeid" type="text" value="<?php echo $row['employee_id']; ?>"></th>
                    <td><input class="form-control form-control-sm" name="firstname" type="text" value="<?php echo $row['firstname']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="lastname" type="text" value="<?php echo $row['lastname']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="position" type="text" value="<?php echo $row['position']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="birthday" type="text" value="<?php echo $row['birthday']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="phone" type="text" value="<?php echo $row['phone']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="address" type="text" value="<?php echo $row['address']; ?>"></td>
                    <td><input class="form-control form-control-sm" name="salary" type="text" value="<?php echo $row['salary']; ?>"></td>
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