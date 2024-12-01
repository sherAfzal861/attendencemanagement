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
                        echo '<form method="post">';
                        echo "<input type='hidden' name='scheduleid' value='{$row['ScheduleID']}'>";
                        echo '<td>' . $row['Department'] . '<input type="hidden" name="Department" value="' . $row['Department'] . '"></td>';
                        echo '<td>' . $row['Program'] . '<input type="hidden" name="Program" value="' . $row['Program'] . '"></td>';
                        echo '<td>' . $row['Semester'] . '<input type="hidden" name="Semester" value="' . $row['Semester'] . '"></td>';
                        echo '<td>' . $row['Subject'] . '<input type="hidden" name="Subject" value="' . $row['Subject'] . '"></td>';
                        echo '<td>' . $row['Session'] . '<input type="hidden" name="Session" value="' . $sessionid . '"></td>';
                        echo '<td>' . $row['TeacherName'] . '<input type="hidden" name="TeacherName" value="' . $row['TeacherName'] . '"></td>';
                        echo '<td><button type="submit" name="view" class="btn btn-primary">View attendance</button></td>';
                        echo '</form>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>

                </div>
                  <?php
if (isset($_POST['view'])) {
    $department = $_POST['Department'];
    $semester = $_POST['Semester'];
    $program = $_POST['Program'];
    $subject = $_POST['Subject'];
    $sessionid = $_POST['Session'];
    $teachername = $_POST['TeacherName'];
    $scheduleid = $_POST['scheduleid'];

    $schedule = "SELECT * FROM Schedule WHERE ScheduleID = '$scheduleid'";
    $rs = $conn->query($schedule);

    if ($rs->num_rows > 0) {
        $row = $rs->fetch_assoc();
        $scheduleid = $row['ScheduleID'];

        // Display session-related information with Bootstrap styling
        echo "<div class='card mb-4'>";
        echo "<div class='card-header bg-primary text-white'>";
        echo "<h5 class='mb-0'>Session Details</h5>";
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<p><strong>Session:</strong> $sessionid</p>";
        echo "<p><strong>Department:</strong> $department</p>";
        echo "<p><strong>Program:</strong> $program</p>";
        echo "<p><strong>Subject:</strong> $subject</p>";
        echo "<p><strong>Semester:</strong> $semester</p>";
        echo "<p><strong>Teacher:</strong> $teachername</p>";
        echo "</div>";
        echo "</div>";

        // Fetch attendance data
        $query = "SELECT DISTINCT StudentRegNumber, Status, CreditHours, Date FROM attendance WHERE ScheduleID = '$scheduleid' ORDER BY Date ASC";
        $attendanceResult = $conn->query($query);

        if ($attendanceResult->num_rows > 0) {
            // Fetch unique dates for table headers
            $datesQuery = "SELECT DISTINCT Date FROM attendance WHERE ScheduleID = '$scheduleid' ORDER BY Date ASC";
            $datesResult = $conn->query($datesQuery);

            $dateColumns = [];
            while ($dateRow = $datesResult->fetch_assoc()) {
                $dateColumns[] = $dateRow['Date'];
            }

            // Prepare data for display
            $students = [];
            while ($row = $attendanceResult->fetch_assoc()) {
                $regNumber = $row['StudentRegNumber'];
                $date = $row['Date'];
                $status = $row['Status'];

                if (!isset($students[$regNumber])) {
                    $students[$regNumber] = [];
                }
                $students[$regNumber][$date] = $status;
            }

            // Generate the attendance table with Bootstrap classes
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-striped table-hover'>";
            echo "<thead class='table-light'>";
            echo "<tr>";
            echo "<th>Registration Number</th>";
            echo "<th>Student Name</th>";

            // Table headers for each date
            foreach ($dateColumns as $date) {
                $dateObj = new DateTime($date);
                echo "<th>" . $dateObj->format('d-m-Y') . "</th>";
            }

            echo "<th>Total</th>";
            echo "<th>Obtained</th>";
            echo "<th>Attendance %</th>";
            echo "<th>Status</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($students as $regNumber => $statuses) {
                // Fetch student name
                $studentnameQuery = "SELECT StudentName FROM Students WHERE RegNumber = '$regNumber'";
                $studentnameResult = $conn->query($studentnameQuery);
                $studentName = $studentnameResult->fetch_assoc()['StudentName'] ?? 'Unknown';

                echo "<tr>";
                echo "<td>$regNumber</td>";
                echo "<td>$studentName</td>";

                $obt = 0;
                $total = 0;
                foreach ($dateColumns as $date) {
                    $status = $statuses[$date] ?? '-';
                    if ($status === 'Present') {
                        $status = 'P';
                        $obt++;
                    } elseif ($status === 'Absent') {
                        $status = 'A';
                    } elseif ($status === 'Leave') {
                        $status = 'L';
                    }
                    echo "<td>$status</td>";
                    $total++;
                }

                $percentage = $total > 0 ? round(($obt / $total) * 100, 2) : 0;
                $statusText = $percentage >= 75 ? 'Allowed' : 'Not Allowed';

                echo "<td>$total</td>";
                echo "<td>$obt</td>";
                echo "<td>$percentage%</td>";
                echo "<td>$statusText</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning'>No attendance records found for the selected schedule.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No schedule found for the entered class details.</div>";
    }
}
?>
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
