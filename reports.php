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

$sqli = "SELECT * FROM attendance where date = CURDATE()";
$result2 = mysqli_query($link, $sqli);

$sqli = "SELECT * FROM payroll";
$result3 = mysqli_query($link, $sqli);

$sqli = "SELECT * FROM accomplishment";
$result4 = mysqli_query($link, $sqli);

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
          <a class="nav-link" href="dashboard.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="employee_list_admin.php">Employee List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active fw-bold" aria-current="page"  href="reports.php">Reports</a>
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
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
    </div>
  </div>
</nav>
<div class="container mt-5">
        <div class="row">
    <div class="canvas_div_pdf">
    <h5 class="fw-bold d-flex">Reports</h5>
    <table class="table table-bordered table-secondary">
        <?php
        
        if (mysqli_num_rows($result4) > 0) {
           echo  "<thead>
            <tr>
                <th scope='col'>No.</th>
                <th scope='col'>Client Name</th>
                <th scope='col'>Scope</th>
                <th scope='col'>Payment Mode</th>
                <th scope='col'>Project Cost</th>
                <th scope='col'>Material</th>
                <th scope='col'>Payroll</th>
                <th scope='col'>Total Expenses</th>
                <th scope='col'>Gross Income</th>
                <th scope='col'>Date Completed</th>
            </tr>
            </thead>";
            while($row = mysqli_fetch_assoc($result4)) {
                if($row['status'] == 'completed'){
                    $date = date("F d, Y", strtotime($row["date_completed"]));
                }else if($row['status'] == 'ongoing'){
                    $date = "Ongoing";
                }
                echo "<tbody>
                <tr>
                    <td>".$row['id']."</td>
                    <td>".$row["client"]."</td>
                    <td>".$row["scope_work"]."</td>
                    <td>".$row["payment_mode"]."</td>
                    <td>".$row["project_cost"]."</td>
                    <td>".$row["materials"]."</td>
                    <td>".$row["payroll"]."</td>
                    <td>".$row["expenses"]."</td>
                    <td>".$row["gross"]."</td>
                    <td>".$date."</td>
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
    <button class="btn btn-sm btn-primary d-flex" onclick="getPDF()">Download PDF Copy</button>
        </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script>
function getPDF(){

var HTML_Width = $(".canvas_div_pdf").width();
var HTML_Height = $(".canvas_div_pdf").height();
var top_left_margin = 15;
var PDF_Width = HTML_Width+(top_left_margin*2);
var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
var canvas_image_width = HTML_Width;
var canvas_image_height = HTML_Height;

var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;


html2canvas($(".canvas_div_pdf")[0],{allowTaint:true}).then(function(canvas) {
    canvas.getContext('2d');
    
    console.log(canvas.height+"  "+canvas.width);
    
    
    var imgData = canvas.toDataURL("image/jpeg", 1.0);
    var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
    
    
    for (var i = 1; i <= totalPDFPages; i++) { 
        pdf.addPage(PDF_Width, PDF_Height);
        pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
    }
    
    pdf.save("HTML-Document.pdf");
});
};
</script>
</body>
</html>