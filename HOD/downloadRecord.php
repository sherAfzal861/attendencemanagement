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
                <table class="table table-bordered mb-5">
                    <thead>
                      <tr>
                        <th>Department</th>
                        <th>Program</th>
                        <th>Semester</th>
                        <th>Subject</th>
                        <th>Session</th>
                        <th>Teacher</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $dept = $_SESSION['deptName'];
                      $query = "SELECT 
                          Schedule.ScheduleID,
                          Schedule.Subject, 
                          Schedule.Semester, 
                          Schedule.Department, 
                          Schedule.Program, 
                          Teachers.TeacherName ,
                          Schedule.Session
                      FROM Schedule
                      INNER JOIN Teachers ON Schedule.TeacherCode = Teachers.TeacherCode
                      WHERE Schedule.Department = '$dept'";
                      $result = mysqli_query($conn, $query);
                      
                      while ($row = mysqli_fetch_assoc($result)) {
                        $sessionquery = "SELECT SessionID from Sessions WHERE Session='$row[Session]'";
                        $sessionquery = $conn->query($sessionquery);
                        $sessionquery = $sessionquery->fetch_assoc();
                        $sessionid = $sessionquery['SessionID'];
                        echo '<tr>';
                        echo '<form method="post" action="download_attendance_report.php">';
                        echo "<input type='hidden' name='scheduleid' value='{$row['ScheduleID']}'>";
                        echo '<td>' . $row['Department'] . '<input type="hidden" name="Department" value="' . $row['Department'] . '"></td>';
                        echo '<td>' . $row['Program'] . '<input type="hidden" name="Program" value="' . $row['Program'] . '"></td>';
                        echo '<td>' . $row['Semester'] . '<input type="hidden" name="Semester" value="' . $row['Semester'] . '"></td>';
                        echo '<td>' . $row['Subject'] . '<input type="hidden" name="Subject" value="' . $row['Subject'] . '"></td>';
                        echo '<td>' . $row['Session'] . '<input type="hidden" name="Session" value="' . $sessionid . '"></td>';
                        echo '<td>' . $row['TeacherName'] . '<input type="hidden" name="TeacherName" value="' . $row['TeacherName'] . '"></td>';
                        echo '<td><button type="submit" name="view" class="btn btn-primary">Print Report</button></td>';
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
