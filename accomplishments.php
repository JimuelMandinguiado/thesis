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
// get employee data and display to table
$sqli = "SELECT * FROM accomplishment";
$result = mysqli_query($link, $sqli);

$insert_err = $empty_fields = "";

if (isset($_POST['submit'])) {
    $client = $_POST['client'];
    $scope = $_POST['scope'];
    $cost = $_POST['cost'];
    $material = $_POST['material'];
    $mode_payment = $_POST['mode'];
    $payroll = $_POST['payroll'];


    //check for empty fields
    if (empty($client) || empty($scope) || empty($cost) || empty($material) || empty($mode_payment) || empty($payroll)) {
        $empty_fields = "Please fill in all fields.";
        //wait for 3 seconds
    } else {
        // prepare an insert statement
        $sql = "INSERT INTO accomplishment (scope_work,client, project_cost, payment_mode, materials, payroll, expenses, gross,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssss", $param_scope_work, $param_client, $param_project_cost, $param_payment_mode, $param_material, $param_payroll, $param_total_expenses, $param_gross_profit,$param_duration);
            // Set parameters
            $param_client = $client;
            $param_scope_work = $scope;
            $param_project_cost = $cost;
            $param_payment_mode = $mode_payment;
            $param_material = $material;
            $param_payroll = $payroll;
            $param_total_expenses = $param_payroll + $param_material;
            $param_gross_profit = $param_project_cost - $param_total_expenses;
            $param_duration = "ongoing";
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                header("location: accomplishments.php");
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
    <title>General Manager</title>
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
      <img src="https://i.imgur.com/0gfEfHt.jpg" alt="" height="70" class="d-inline-block align-text-top">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="employee_list_admin.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reports.php">Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page" href="accomplishments.php">Accomplishments</a>
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
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add accomplished job <i class="fa-solid fa-plus"></i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
        <div class="modal-body">
           
                <div class="form-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="client" placeholder="Client Name">
                </div>
                <div class="form-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="scope" placeholder="Scope of work e.g : Reapir and Cleaning">
                </div>
                <div class="form-group mb-2">
                    <select name="mode" class="form-select" aria-label="Default select example">
                        <option selected>Select Payment Mode</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="cost" placeholder="Project Cost">
                </div>
                <div class="form-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="material" placeholder="Materials">
                </div>
                <div class="form-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="payroll" placeholder="Payroll">
                </div>

                <div class="form-group">
                <button type="submit" name="submit" class='btn btn-sm btn-danger d-flex'>Save Accomplishment</button>
                </div>
        </div>
        </form>
        </div>
    </div>
    </div>
    
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
            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa-solid fa-plus"></i> Add accomplishment
            </button>
        </div>

    <table class="table table-bordered table-secondary">
        <?php
        
        if (mysqli_num_rows($result) > 0) {
           echo  "<thead>
            <tr>
                <th scope='col'>No.</th>
                <th scope='col'>Client Name</th>
                <th scope='col'>Scope_work</th>
                <th scope='col'>Project Cost</th>
                <th scope='col'>Duration</th>
                <th scope='col'>Date Completed</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
                    if($row['status'] == 'completed'){
                        $date = date("F d, Y", strtotime($row["date_completed"]));
                    }else if($row['status'] == 'ongoing'){
                        $date = "Ongoing";
                    }
                echo "<tbody>
                <tr>
                    <th scope='row'>".$row["id"]."</th>
                    <td>".$row["client"]."</td>
                    <td>".$row["scope_work"]."</td>
                    <td>".$row["project_cost"]."</td>
                    <td>".$row["status"]."</td>
                    <td>".$date."</td>
                    <td>
                        <button class='btn btn-sm btn-secondary'><a href=edit_accomplishments.php?id=".$row["id"]." ?>Edit</a></button>
                        <button class='btn btn-sm btn-danger' name='delete'><a href=delete_accomplishment.php?id=".$row["id"]." ?>Delete</a></button>
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