<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/AIU Transparent Logo.png" rel="icon">

  <title>Mark Attendance</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>
<body id="page-top">
  <div id="wrapper">
    <?php include "Includes/sidebar.php"; ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include "Includes/topbar.php"; ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Download Attendance Report</h1>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Details of classes</h6>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Department</th>
                        <th>Program</th>
                        <th>Semester</th>
                        <th>Subject</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $teacherCode = $_SESSION['teachercode'];
                      $query = "SELECT * FROM Schedule WHERE TeacherCode = '$teacherCode'";
                      $result = mysqli_query($conn, $query);

                      while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<form method="post" action="download_attendance_report.php">';
                        echo "<input type='hidden' name='scheduleid' value='{$row['ScheduleID']}'>";
                        echo '<td>' . $row['Department'] . '</td>';
                        echo '<td>' . $row['Program'] . '</td>';
                        echo '<td>' . $row['Semester'] . '</td>';
                        echo '<td>' . $row['Subject'] . '</td>';
                        echo '<td><button type="submit" class="btn btn-primary">Download Attendance Report</button></td>';
                        echo '</form>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
