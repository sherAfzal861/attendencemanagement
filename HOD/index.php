<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Replace query with details from Teachers table (as per the existing database schema).
// This example assumes that Class information is derived from Program and Semester.
$query = "SELECT Programs.ProgramName, Semesters.Semester
          FROM Teachers 
          INNER JOIN Programs ON Programs.Department = Teachers.DeptName
          INNER JOIN Semesters ON Semesters.SemesterID = Programs.ProCode
          WHERE Teachers.TeacherID = '$_SESSION[userId]'";

$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/AIU Transparent Logo.png" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>
        <!-- Topbar -->
        
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- Students Card -->
          <?php 
          $query1 = mysqli_query($conn, "SELECT * FROM Students");                       
          $students = mysqli_num_rows($query1);
          ?>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-users fa-2x text-info"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Programs Card -->
          <?php 
          $query1 = mysqli_query($conn, "SELECT * FROM Programs");                       
          $programs = mysqli_num_rows($query1);
          ?>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Programs</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $programs;?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-chalkboard fa-2x text-primary"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Semesters Card -->
          <?php 
          $query1 = mysqli_query($conn, "SELECT * FROM Semesters");                       
          $semesters = mysqli_num_rows($query1);
          ?>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Semesters</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $semesters;?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-code-branch fa-2x text-success"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Departments Card -->
          <?php 
          $query1 = mysqli_query($conn, "SELECT * FROM Departments");                       
          $departments = mysqli_num_rows($query1);
          ?>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Departments</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $departments;?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-building fa-2x text-secondary"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Teachers Card -->
          <?php 
          $query1 = mysqli_query($conn, "SELECT * FROM Teachers");                       
          $teachers = mysqli_num_rows($query1);
          ?>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Teachers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $teachers;?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Sessions Card -->
          <?php 
          $query1 = mysqli_query($conn, "SELECT * FROM Sessions");                       
          $sessions = mysqli_num_rows($query1);
          ?>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Sessions</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $sessions;?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <?php include 'Includes/footer.php';?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
</body>

</html>
