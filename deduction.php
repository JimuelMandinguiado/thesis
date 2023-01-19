<?php
date_default_timezone_set('Asia/Manila');
require_once "config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

//check if deduction's already been made for the day
$sqli = "SELECT * FROM deductions";
$result = mysqli_query($link, $sqli);


if(isset($_POST['calculate'])){
    $id = $_POST['id'];
    $sqli = "SELECT * FROM payroll WHERE employee_id = '$id'";
    $result = mysqli_query($link, $sqli);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $net_pay = $row['net_pay'];
            $name = $row['name'];
            $employee_id = $row['employee_id'];
            $absent = $row['absent'];
            $late = $row['late'];
        }
    }
    $late_total = $late * 50; 
    $abs = $absent * 500;
    $philhealth = $_POST['philhealth'];
    $cash_advance = $_POST['cashadv'];
    $tax = $_POST['tax'];
    $sss = $_POST['sss'];
    $deduction = $late_total + $abs + $sss + $philhealth + $cash_advance;
    $total = $net_pay - $deduction;
    $sql = "INSERT INTO deductions (employee_id, name, absent, late, sss, cash_advance, philhealth, tax, status) VALUES
            ('$employee_id', '$name', '$absent', '$late', '$sss', '$cash_advance', '$philhealth', '$tax', 'Completed')";
    $payroll_update = "UPDATE payroll SET net_pay = '$total',cash_advance = '$cash_advance', deduction = '$deduction', status = 'Computed' WHERE employee_id = '$employee_id'";
    if(mysqli_query($link, $sql)){
        mysqli_query($link, $payroll_update);
        header("Location: deduction.php");
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
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
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
    .search-box{
        width: 300px;
        position: relative;
        display: inline-block;
        font-size: 14px;
    }
    .search-box input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }
    .result{
        background: #9de49d;
        position: absolute;        
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
    }
    .result p:hover{
        background: #f2f2f2;
    }
</style>
<script>
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("search_payroll.php", {id: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});
</script>
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
          <a class="nav-link" href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="attendance_list.php">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="payroll_list.php">Payroll List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page" href="deduction.php">Deduction List</a>
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add employee attendance <i class="fa-solid fa-plus"></i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <form action="" method="get">
            <div class="search-box d-flex flex-row bd-highlight mb-3">
                <input type="text" autocomplete="off" placeholder="Search employee ID" name="search_emp" />
                <button class="btn btn-sm btn-primary" type="submit" name="deduction">Search</button>
                <div class="result"></div>
            </div>
            </form>
        </div>

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
        <p class="fw-bold d-flex">Payroll List</p>
        <div class="d-flex justify-content-between mb-2">
            <form action="" method="get">
            <div class="search-box d-flex flex-row bd-highlight mb-3">
                <input type="text" autocomplete="off" placeholder="Search employee ID" name="search_emp" />
                <button class="btn btn-sm btn-primary" type="submit" name="search">Search</button>
                <div class="result"></div>
            </div>
            </form>
            <div>
            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa-solid fa-plus"></i> Add employee
            </button>
            </div>
        </div>

    <table class="table table-bordered table-info" id="list">
        <?php 
        if(isset($_GET['deduction'])){
            $id = $_GET['search_emp'];
            //get employee data and display to table
            $sqli = "SELECT * FROM payroll WHERE name = '$id'";
            $result = mysqli_query($link, $sqli);
            if(mysqli_num_rows($result) > 0){
                echo  "
            <thead>
            <tr>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Absent</th>
                <th scope='col'>Late</th>
                <th scope='col'>Cash Advance</th>
                <th scope='col'>SSS</th>
                <th scope='col'>Philhealth</th>
                <th scope='col'>Tax</th>
                <th scope='col'>Gross Pay</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "
                <form method='POST' action=''>
                <tbody>
                <tr>
                    <td scope='row'><input class='form-control form-control-sm' type='text' value=".$row['employee_id']." name='id'></td>
                    <th>".$row["name"]."</th>
                    <td>".$row["absent"]."</td>
                    <td>".$row["late"]."</td>
                    <td><input class='form-control form-control-sm' name='cashadv'></td>
                    <td><input class='form-control form-control-sm' value='100' name='sss'></td>
                    <td><input class='form-control form-control-sm' value='50' name='philhealth'></td>
                    <td><input class='form-control form-control-sm' value='100' name='tax'></td>
                    <td>".$row['net_pay']."</td>
                    <td>
                    <button class='btn btn-sm btn-secondary' type='submit' name='calculate'>Calculate</button>
                    </td>
                </tr>
                </tbody>
                </form>";
            }

            }else{
                echo "No records found";
            } 
        }
            if(mysqli_num_rows($result) > 0){
                echo  "<thead>
            <tr>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Absent</th>
                <th scope='col'>Late</th>
                <th scope='col'>Allowance</th>
                <th scope='col'>Cash Advance</th>
                <th scope='col'>SSS</th>
                <th scope='col'>PhilHealth</th>
                <th scope='col'>Tax</th>
                <th scope='col'>Status</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "
                <form method='GET' action=''>
                <tbody>
                <tr>
                    <th scope='row'>".$row['employee_id']."</th>
                    <td><a href='list_payroll.php?id=".$row['employee_id']."'>".$row["name"]."</a></td>
                    <td>".$row["absent"]."</td>
                    <td>".$row["late"]."</td>
                    <td>".$row["allowance"]."</td>
                    <td>".$row["cash_advance"]."</td>
                    <td>".$row["sss"]."</td>
                    <td>".$row["philhealth"]."</td>
                    <td>".$row["tax"]."</td>
                    <td>".$row["status"]."</td>
                    <td>
                        <button class='btn btn-sm btn-secondary'><a href=edit_deduction.php?id=".$row["employee_id"]." ?>Edit</a></button>
                        <button class='btn btn-sm btn-danger' name='delete'><a href=delete_deduction.php?id=".$row["id"]." ?>Delete</a></button>
                        </td>
                </tr>
                </tbody>
                </form>
                ";
            }
    
            }else{
                echo "No records found";
            } 
        ?>

        </table>

    </div>
</div>

</body>
</html>