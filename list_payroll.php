<?php
require_once "config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$id = $_GET['id'];
$sqli = "SELECT * FROM payroll_list where employee_id = '$id'";
$result = mysqli_query($link, $sqli);

$count_records = "SELECT COUNT(*) AS count_work FROM payroll_list where employee_id = '$id'";
$count_res = mysqli_query($link, $count_records);
if(mysqli_num_rows($count_res) > 0){
    while($row = mysqli_fetch_assoc($count_res)){
        $count_work = $row['count_work'];
    }
}

$sss = 250;
$philhealth = 100;
$tax = 50;
$allowance = 100;

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <script src="  https://printjs-4de6.kxcdn.com/print.min.js"></script>
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
          <a class="nav-link" href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="attendance_list.php">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page" href="payroll_list.php">Payroll List</a>
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add employee attendance <i class="fa-solid fa-plus"></i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
        <div class="modal-body">
            
        </div>
        </form>
        </div>
    </div>
    </div>
    <div class="list mt-5 p-2 border">
        <p id="message"></p>
        <?php 
            if(!empty($insert_err)){
                echo '<div class="alert alert-danger">' . $insert_err . '</div>';
            }    
            if(!empty($empty_fields)){
                echo '<div class="alert alert-success">' . $empty_fields . '</div>';
            }    
        ?>
        <p class="fw-bold d-flex">Number of Days attended : <b><span class="ms-2"><?php echo $count_work; ?> Days</span></b></p>
        <div class="d-flex justify-content-between mb-2">
            <form action="" method="get">
                <div class="d-flex flex-row bd-highlight mb-3">
                    <input type="text" name="id" value="<?php echo $id; ?>" hidden>
                    <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" placeholder="Date From">
                    <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" placeholder="Date To">
                    <button type="submit" class="btn btn-primary btn-sm" name="search" id="search_date">Search</button>
                </div>
            </form>
            <div>
            <button type="button" class="btn btn-sm btn-primary" onclick="printJS({ printable: 'list', type: 'html' })">
            <i class="fa-solid fa-print"></i> Print Payslip
                </button>
            </div>
        </div>

    <table class="table table-bordered" id="list">
        <?php 
        if(isset($_GET['search'])){
            //get id from url
            $id = $_GET['id'];
            //date_from and date_to
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
            //get employee data and display to table
            $sqli = "SELECT * FROM payroll_list WHERE date_inserted >= '$date_from' AND date_inserted <= '$date_to' AND employee_id = '$id'";
            $result = mysqli_query($link, $sqli);
            if(mysqli_num_rows($result) > 0){
                echo  "<thead>
            <tr>
                <th scope='col'>Date of Hire</th>
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
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
                $date_inserted = date('F d, Y', strtotime($row['date_inserted']));
                echo "<tbody>
                <tr>
                    <td>" . $date_inserted . "</td>
                    <th scope='row'>".$row["employee_id"]."</th>
                    <td>".$row["name"]."</td>
                    <td>".$row["absent"]."</td>
                    <td>".$row["late"]. " " .$row["lastname"]."</td>
                    <td>".$row["cash_advance"]."</td>
                    <td>".$row["deduction"]."</td>
                    <td>".$row["net_pay"]."</td>
                    <td>".$row["status"]."</td>
                    <td>
                        <button class='btn btn-sm btn-primary'>Re-calculate</button>
                        <button class='btn btn-sm btn-secondary'><a href=edit_employee.php?id=".$row["id"]." ?>Edit</a></button>
                        <button class='btn btn-sm btn-danger' name='delete'><a href=delete_payroll.php?id=".$row["id"]." ?>Delete</a></button>
                        </td>
                </tr>
                </tbody>";
            }

            }else{
                echo "No records found";
            }    
        }else{
            if(mysqli_num_rows($result) > 0){
                echo  "<thead>
            <tr>
                <th scope='col'>Date</th>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Net Pay</th>
                <th scope='col'>Status</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
                $date_inserted = date('F d, Y', strtotime($row['date_inserted']));
                echo "
                <form method='GET' action=''>
                <tbody>
                <tr>
                    <td>" . $date_inserted . "</td>
                    <th scope='row'>".$row['employee_id']."</th>
                    <td><a href='list_payroll.php?id=".$row['employee_id']."'>".$row["name"]."</a></td>
                    <td>".$row["net_pay"]."</td>
                    <td>".$row["status"]."</td>

                </tr>
                </tbody>
                </form>
                ";
            }
    
            }else{
                echo "No records found";
            } 
        }
        ?>

        </table>

    </div>
</div>

</body>
</html>