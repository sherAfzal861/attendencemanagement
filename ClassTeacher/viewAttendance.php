<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/AIU Transparent Logo.png" rel="icon">

  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <?php include "Includes/sidebar.php"; ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include "Includes/topbar.php"; ?>
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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
                        <th>Session</th>
                        <th>Select Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $teacherCode = $_SESSION['teachercode'];
                      $query = "SELECT * FROM Schedule WHERE TeacherCode = '$teacherCode'";
                      $result = mysqli_query($conn, $query);
                      
                      while ($row = mysqli_fetch_assoc($result)) {
                        $sessionquery = "SELECT SessionID from Sessions WHERE Session='$row[Session]'";
                        $sessionquery = $conn->query($sessionquery);
                        $sessionquery = $sessionquery->fetch_assoc();
                        $sessionid = $sessionquery['SessionID'];
                        echo '<tr>';
                        echo '<form method="post">';
                        echo '<td>' . $row['Department'] . '<input type="hidden" name="Department" value="' . $row['Department'] . '"></td>';
                        echo '<td>' . $row['Program'] . '<input type="hidden" name="Program" value="' . $row['Program'] . '"></td>';
                        echo '<td>' . $row['Semester'] . '<input type="hidden" name="Semester" value="' . $row['Semester'] . '"></td>';
                        echo '<td>' . $row['Subject'] . '<input type="hidden" name="Subject" value="' . $row['Subject'] . '"></td>';
                        echo '<td>' . $row['Session'] . '<input type="hidden" name="Session" value="' . $sessionid . '"></td>';
                        echo '<td>
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="dateTaken" placeholder="Class Arm Name">
                        </td>';
                        echo '<td><button type="submit" name="view" class="btn btn-primary">View attendance</button></td>';
                        echo '</form>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                  <?php
                  if (isset($_POST['view'])) {
                      $department = $_POST['Department'];
                      $semester = $_POST['Semester'];
                      $program = $_POST['Program'];
                      $subject = $_POST['Subject'];
                      $datetaken = $_POST['dateTaken'];
                      $sessionid = $_POST['Session'];

                      $schedule = "SELECT * FROM Schedule WHERE Department='$department' AND Program='$program' AND Semester='$semester' AND Subject='$subject'";
                      $rs = $conn->query($schedule);
                      if ($rs->num_rows > 0) {
                          $row = $rs->fetch_assoc();
                          $scheduleid = $row['ScheduleID'];

                          $query = "SELECT DISTINCT StudentRegNumber, Date, Status FROM attendance WHERE ScheduleID='$scheduleid' AND Date='$datetaken'";
                          $rs = $conn->query($query);
                          if ($rs->num_rows > 0) {
                              echo "<form method='post'>";
                              echo "<table class='table table-bordered'>";
                              echo "<thead><tr><th>Student ID</th><th>Date</th><th>Status</th></tr></thead><tbody>";

                              while ($attendanceRow = $rs->fetch_assoc()) {
                                  echo "<tr>";
                                  echo "<td>" . $attendanceRow['StudentRegNumber'] . "<input type='hidden' name='StudentRegNumber[]' value='" . $attendanceRow['StudentRegNumber'] . "'></td>";
                                  echo "<td>" . $attendanceRow['Date'] . "<input type='hidden' name='Date' value='" . $attendanceRow['Date'] . "'></td>";
                                  echo "<td>
                                        <input type='radio' name='Status[" . $attendanceRow['StudentRegNumber'] . "]' value='Present' " . ($attendanceRow['Status'] == 'Present' ? 'checked' : '') . "> Present
                                        <input type='radio' name='Status[" . $attendanceRow['StudentRegNumber'] . "]' value='Absent' " . ($attendanceRow['Status'] == 'Absent' ? 'checked' : '') . "> Absent
                                        <input type='radio' name='Status[" . $attendanceRow['StudentRegNumber'] . "]' value='Leave' " . ($attendanceRow['Status'] == 'Leave' ? 'checked' : '') . "> Leave
                                        </td>";
                                  echo "</tr>";
                              }

                              echo "</tbody></table>";
                              echo "<button type='submit' name='update' class='btn btn-success'>Update</button>";
                              echo "</form>";
                          } else {
                              echo "<div class='alert alert-warning'>No attendance records found for the selected date.</div>";
                          }
                      } else {
                          echo "<div class='alert alert-danger'>No schedule found for the entered class details.</div>";
                      }
                  }

                  if (isset($_POST['update'])) {
                      $date = $_POST['Date'];
                      $statuses = $_POST['Status'];

                      foreach ($statuses as $studentID => $status) {
                          $updateQuery = "UPDATE attendance SET Status='$status' WHERE StudentRegNumber='$studentID' AND Date='$date'";
                          $conn->query($updateQuery);
                      }

                      echo "<div class='alert alert-success'>Attendance updated successfully!</div>";
                  }
                  ?>
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
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>
