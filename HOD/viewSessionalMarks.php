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
  <title>Mark Attendance</title>
  <link href="img/logo/AIU Transparent Logo.png" rel="icon">

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
            <h1 class="h3 mb-0 text-gray-800">Download Report of Marks</h1>
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
                        echo '<tr>';
                        echo '<form method="post">';
                        echo "<input type='hidden' name='scheduleid' value='{$row['ScheduleID']}'>";
                        echo '<td>' . $row['Department'] . '</td>';
                        echo '<td>' . $row['Program'] . '</td>';
                        echo '<td>' . $row['Semester'] . '</td>';
                        echo '<td>' . $row['Subject'] . '</td>';
                        echo '<td>' . $row['Session'] . '</td>';
                        echo '<td>' . $row['TeacherName'] . '</td>';

                        echo '<td><button type="submit" name="view" class="btn btn-primary">View Sessional Marks</button></td>';
                        echo '</form>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <?php
require_once '../Includes/dbcon.php'; // Database connection

if (isset($_POST['view'])) {
    $scheduleID = filter_input(INPUT_POST, 'scheduleid', FILTER_VALIDATE_INT);

    if (!$scheduleID) {
        echo "Invalid Schedule ID.";
        exit;
    }

    // Fetch schedule details
    $stmt = $conn->prepare("
        SELECT 
            Schedule.Subject, 
            Schedule.Semester, 
            Schedule.Department, 
            Schedule.Program, 
            Teachers.TeacherName 
        FROM Schedule
        INNER JOIN Teachers ON Schedule.TeacherCode = Teachers.TeacherCode
        WHERE Schedule.ScheduleID = ?
    ");
    $stmt->bind_param("i", $scheduleID);
    $stmt->execute();
    $scheduleResult = $stmt->get_result();
    $scheduleData = $scheduleResult->fetch_assoc();

    if (!$scheduleData) {
        echo "No schedule found for the given ID.";
        exit;
    }

    $subject = $scheduleData['Subject'];
    $semester = $scheduleData['Semester'];
    $department = $scheduleData['Department'];
    $program = $scheduleData['Program'];
    $teacherName = $scheduleData['TeacherName'];

    // Fetch marks data
    $marksQuery = $conn->prepare("
        SELECT 
            ar.StudentRegNumber, 
            s.StudentName,
            SUM(CASE WHEN ar.AssesmentType = 'Assignment1' THEN ar.MarksObtained ELSE 0 END) AS Assignment1,
            SUM(CASE WHEN ar.AssesmentType = 'Assignment2' THEN ar.MarksObtained ELSE 0 END) AS Assignment2,
            SUM(CASE WHEN ar.AssesmentType = 'Assignment3' THEN ar.MarksObtained ELSE 0 END) AS Assignment3,
            SUM(CASE WHEN ar.AssesmentType = 'Quiz1' THEN ar.MarksObtained ELSE 0 END) AS Quiz1,
            SUM(CASE WHEN ar.AssesmentType = 'Quiz2' THEN ar.MarksObtained ELSE 0 END) AS Quiz2,
            SUM(CASE WHEN ar.AssesmentType = 'Project1' THEN ar.MarksObtained ELSE 0 END) AS Project1,
            SUM(CASE WHEN ar.AssesmentType = 'Project2' THEN ar.MarksObtained ELSE 0 END) AS Project2
        FROM AcademicRecord ar
        INNER JOIN Students s ON ar.StudentRegNumber = s.RegNumber
        WHERE ar.ScheduleID = ?
        GROUP BY ar.StudentRegNumber
    ");
    $marksQuery->bind_param("i", $scheduleID);
    $marksQuery->execute();
    $marksResult = $marksQuery->get_result();

    // Prepare data for the table
    $rows = [];
    while ($row = $marksResult->fetch_assoc()) {
        $row['Total'] = array_sum([ 
            $row['Assignment1'], $row['Assignment2'], $row['Assignment3'], 
            $row['Quiz1'], $row['Quiz2'], $row['Project1'], $row['Project2']
        ]);
        $row['Percentage'] = round(($row['Total'] / 100) * 100, 2); // Example calculation
        $row['Weighted'] = round($row['Percentage'] * 0.2, 2); // Weighted 20%
        $rows[] = $row;
    }

    // Display the sessional marks on the HTML page
    echo "
        <h1 style='text-align: center;'>Sessional Record</h1>
        <table style='width: 100%; border-collapse: collapse;' class='table'>
            <tr>
                <td><b>Session:</b> Fall 2024</td>
                <td><b>Department:</b> $department</td>
                <td><b>Program:</b> $program</td>
                <td><b>Semester:</b> $semester</td>
            </tr>
            <tr>
                <td><b>Teacher Name:</b> $teacherName</td>
                <td colspan='3'><b>Subject:</b> $subject</td>
            </tr>
        </table>
        <br><br>
        <table border='1' class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Registration No.</th>
                    <th>Student Name</th>
                    <th>Assign-1</th>
                    <th>Assign-2</th>
                    <th>Assign-3</th>
                    <th>Quiz-1</th>
                    <th>Quiz-2</th>
                    <th>Project-1</th>
                    <th>Project-2</th>
                    <th>Total</th>
                    <th>100%</th>
                    <th>20%</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($rows as $row) {
        echo "
            <tr>
                <td>{$row['StudentRegNumber']}</td>
                <td>{$row['StudentName']}</td>
                <td>{$row['Assignment1']}</td>
                <td>{$row['Assignment2']}</td>
                <td>{$row['Assignment3']}</td>
                <td>{$row['Quiz1']}</td>
                <td>{$row['Quiz2']}</td>
                <td>{$row['Project1']}</td>
                <td>{$row['Project2']}</td>
                <td>{$row['Total']}</td>
                <td>{$row['Percentage']}%</td>
                <td>{$row['Weighted']}</td>
            </tr>";
    }

    echo "
            </tbody>
        </table>";
} else {
    echo "Invalid request. Schedule ID not found.";
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
</body>
</html>
