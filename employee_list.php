<?php
require_once "config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// get employee data and display to table
$sqli = "SELECT * FROM employee";
$result = mysqli_query($link, $sqli);

$insert_err = $empty_fields = "";

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
        $empty_fields = "Please fill in all fields.";
    } else {
        // prepare an insert statement
        $sql = "INSERT INTO employee (employee_id, firstname, lastname, position, address, phone, birthday, salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $payroll_creattion = "INSERT INTO payroll (employee_id, name, late,absent,net_pay, date_inserted) VALUES ('$employeeid', '$firstname $lastname', 0,0,0, NOW())";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_employeeid, $param_firstname, $param_lastname, $param_position, $param_address, $param_phone, $param_birthday, $param_salary);
            // Set parameters
            $param_employeeid = $employeeid;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_position = $position;
            $param_address = $address;
            $param_phone = $phone;
            $param_birthday = $birthday;
            $param_salary = $salary;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                mysqli_query($link, $payroll_creattion);
                header("location: employee_list.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);

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
      <img src="https://i.imgur.com/0gfEfHt.jpg" alt="" height="70" class="d-inline-block align-text-top">
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
          <a class="nav-link active fw-bold" aria-current="page" href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="attendance_list.php">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="payroll_list.php">Payroll List</a>
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
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
    </div>
  </div>
</nav>
<div class="container">
    <div class="list mt-5 p-2 border">
        <?php 
            if(!empty($insert_err)){
                echo '<div class="alert alert-danger">' . $insert_err . '</div>';
            }    
            if(!empty($empty_fields)){
                echo '<div class="alert alert-success">' . $empty_fields . '</div>';
            }    
        ?>
        <div class="d-flex justify-content-between mb-2">
        <p class="fw-bold">Employee List</p>
        </div>

    <table class="table table-bordered table-secondary">
        <?php
        
        if (mysqli_num_rows($result) > 0) {
           echo  "<thead>
            <tr>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Position</th>
                <th scope='col'>Birthdate</th>
                <th scope='col'>Phone Number</th>
                <th scope='col'>Home Address</th>
                <th scope='col'>Daily Salary</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
                $date = date("F d, Y", strtotime($row["birthday"]));
                echo "<tbody>
                <tr>
                    <th scope='row'>".$row["employee_id"]."</th>
                    <td>".$row["firstname"]."</td>
                    <td>".$row["position"]."</td>
                    <td>".$date."</td>
                    <td>".$row["phone"]."</td>
                    <td>".$row["address"]."</td>
                    <td>".$row["salary"]."</td>
                    <td>
                        <button class='btn btn-sm btn-secondary'><a href=edit_employee.php?id=".$row["id"]." ?>Edit</a></button>
                        <button class='btn btn-sm btn-danger' name='delete'><a href=delete_employee.php?id=".$row["id"]." ?>Delete</a></button>
                    </td>
                </tr>
                </tbody>";
            }
        } else {
            echo "0 results";
        }
        ?>
        </table>
    </div>
</div>

</body>
</html>