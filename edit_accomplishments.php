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
// get id from GET request 
$id = $_GET['id'];
// update the record
if (isset($_POST['submit'])) {
    $client = $_POST['client'];
    $scope = $_POST['scope'];
    $payment_mode = $_POST['mode'];
    $cost = $_POST['cost'];
    $material = $_POST['material'];
    $payroll = $_POST['payroll'];
    $duration = $_POST['duration'];

    //check for empty fields
    if (empty($client) || empty($scope)) {
        echo "Please fill in all fields";
    } else {
        // prepare an update statement
        $sql = "UPDATE accomplishment SET client=?, scope_work=?,payment_mode = ?,project_cost = ?, materials = ?, payroll = ?, gross = ?, expenses = ?, status=?,date_completed=? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssi", $param_client, $param_scope, $param_payment_mode,$param_project_cost,$param_material,$param_payroll,$param_gross_profit,$param_total_expenses,$param_duration,$param_date_completed, $param_id);
            // set parameters
            $param_client = $client;
            $param_scope = $scope;
            $param_project_cost = $cost;
            $param_payment_mode = $payment_mode;
            $param_material = $material;
            $param_payroll = $payroll;
            $param_total_expenses = $param_payroll + $param_material;
            $param_gross_profit = $param_project_cost - $param_total_expenses;
            $param_duration = $duration;
            $param_date_completed = date('Y-m-d');
            $param_id = $id;
            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // records updated successfully. Redirect to landing page
                header("location: accomplishments.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }

}

// get data from database
$sql = "SELECT * FROM accomplishment WHERE id=$id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);

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
          <a class="nav-link active" aria-current="page" href="employee_list_admin.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reports.php">Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="accomplishments.php">Accomplishments</a>
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
        <p class="fw-bold">Update accomplishment informations <i class="fas fa-edit"></i></p>
            
        </div>
        <form action="" method="post">
        <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">Date complated</th>
            <th scope="col">Client Name</th>
            <th scope='col'>Scope of work</th>
            <th scope='col'>Action</th>
            </tr>
            </thead>
                <tbody>
                <tr>
                    <td><input type="date" name="date" value="<?php echo $row['date_completed']; ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="client" value="<?php echo $row['client']; ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="scope" value="<?php echo $row['scope_work']; ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="mode" value="<?php echo $row['payment_mode']; ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="cost" value="<?php echo $row['project_cost']; ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="material" value="<?php echo $row['materials']; ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="payroll" value="<?php echo $row['payroll']; ?>"></td>
                    <td><div class="form-group mb-2">
                    <select name="duration" class="form-select" aria-label="Default select example">
                        <option value="ongoing" selected>Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div></td>

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