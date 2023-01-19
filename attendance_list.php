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
$sqli = "SELECT * FROM attendance";
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
        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_employeeid, $param_firstname, $param_lastname, $param_position, $param_address, $param_phone, $param_birthday, $param_salary);
            // set parameters
            $param_employeeid = $employeeid;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_position = $position;
            $param_address = $address;
            $param_phone = $phone;
            $param_birthday = $birthday;
            $param_salary = $salary;
            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // records updated successfully. Redirect to landing page
                header("location: employee_list.php");
                exit();
            } else {
                $insert_err = "Something went wrong. Please try again later.";
            }
        }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.0/axios.min.js" integrity="sha512-OdkysyYNjK4CZHgB+dkw9xQp66hZ9TLqmS2vXaBrftfyJeduVhyy1cOfoxiKdi4/bfgpco6REu6Rb+V2oVIRWg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
          <a class="nav-link" href="dashboard_sec.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="employee_list.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page" href="attendance_list.php">Attendance</a>
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add employee attendance <i class="fa-solid fa-plus"></i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
        <div class="modal-body">
            <h5><i>Note : Place your QR Code in front of Camera.</i></h5>
            <video id="preview"></video>
            <p class="fw-bold mt-2"><?php echo "Date:" . date(" Y/m/d");?></p>
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
            if(!empty($attended_success)){
              echo '<div class="alert alert-success">' . $empty_fields . '</div>';
          }    
        ?>
        <div class="d-flex justify-content-between mb-2">
        <p class="fw-bold">Attendance List</p>
            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa-solid fa-plus"></i> Add attendance
            </button>
        </div>

    <table class="table table-bordered table-secondary">
        <?php
        
        if (mysqli_num_rows($result) > 0) {
           echo  "<thead>
            <tr>
                <th scope='col'>Log Date</th>
                <th scope='col'>Employee ID</th>
                <th scope='col'>Name</th>
                <th scope='col'>Time In</th>
                <th scope='col'>Time Out</th>
                <th scope='col'>Status</th>
                <th scope='col'>Action</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result)) {
              $date_created = date("F d, Y", strtotime($row["date"]));
              $time_in = date("h:i:sa", strtotime($row["time_in"]));
              if($row["time_out"]){
                $time_out = date("h:i:sa", strtotime($row["time_out"]));
              }else{
                $time_out = "N/A";
              }
                echo "<tbody>
                <tr>
                    <td>".$date_created."</td>
                    <td>".$row["employee_id"]."</td>
                    <td>".$row["firstname"]. " " .$row["lastname"]."</td>
                    <td>".$time_in."</td>
                    <td>".$time_out."</td>
                    <td>".$row["status"]."</td>
                    <td>
                        <button class='btn btn-sm btn-danger' name='delete'><a href=delete_attendance.php?id=".$row["id"]." ?>Delete</a></button>
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
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    let opts = {
  // Whether to scan continuously for QR codes. If false, use scanner.scan() to manually scan.
  // If true, the scanner emits the "scan" event when a QR code is scanned. Default true.
  continuous: true,
  
  // The HTML element to use for the camera's video preview. Must be a <video> element.
  // When the camera is active, this element will have the "active" CSS class, otherwise,
  // it will have the "inactive" class. By default, an invisible element will be created to
  // host the video.
  video: document.getElementById('preview'),
  
  // Whether to horizontally mirror the video preview. This is helpful when trying to
  // scan a QR code with a user-facing camera. Default true.
  mirror: true,
  
  // Whether to include the scanned image data as part of the scan result. See the "scan" event
  // for image format details. Default false.
  captureImage: true,
  
  // Only applies to continuous mode. Whether to actively scan when the tab is not active.
  // When false, this reduces CPU usage when the tab is not active. Default true.
  backgroundScan: true,
  
  // Only applies to continuous mode. The period, in milliseconds, before the same QR code
  // will be recognized in succession. Default 5000 (5 seconds).
  refractoryPeriod: 5000,
  
  // Only applies to continuous mode. The period, in rendered frames, between scans. A lower scan period
  // increases CPU usage but makes scan response faster. Default 1 (i.e. analyze every frame).
  scanPeriod: 1
};
      let scanner = new Instascan.Scanner(opts);
      scanner.addListener('scan', function (content, image) {
        console.log(content.image);
        console.log(image);

        $.ajax({
              method: "POST",
              url:    "process_attendance.php",
              data: { "employee_id": content },
             })
                .done(function( msg ) {
                  console.log(msg);
                  if(msg == "success"){
                    $("#message").html("<div class='alert alert-success'>Attendance added successfully.</div>");
                  }else{
                    $("#message").html("<div class='alert alert-danger'>Attendance already added.</div>");
                  }
                    document.getElementById("message").innerHTML = "Attendance added successfully";
                    $('#exampleModal').modal('hide');
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                })
                .fail(function( msg ) {
                    document.getElementById("message").innerHTML = "Attendance not added";
                });
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);

        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
    </script>
</body>
</html>