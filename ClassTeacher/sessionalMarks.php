<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST['submit_marks'])) {
  $subject = $_POST['subject'];
  $semester = $_POST['semester'];
  $studentIds = $_POST['student_id'];
  $marks = $_POST['marks'];
  $scheduleid = $_POST['scheduleid'];
  $assesmenttype = $_POST['assesmenttype'];
  $totalmarks = $_POST['total_marks'];
  print_r($totalmarks);
  // print_r($_POST); // For debugging purposes

  // Loop through each student and insert or update their record
  for ($i = 0; $i < count($studentIds); $i++) {
      $studentId = $studentIds[$i];
      $mark = $marks[$i];

      // Insert or update statement
      $query = "INSERT INTO AcademicRecord (StudentRegNumber, ScheduleID, AssesmentType, MarksObtained,TotalMarks, SubmissionDate) 
                VALUES ('$studentId', $scheduleid, '$assesmenttype', '$mark',$totalmarks, CURDATE()) 
                ON DUPLICATE KEY UPDATE MarksObtained='$mark',TotalMarks='$totalmarks', SubmissionDate=CURDATE()";
      // Execute the query
      $conn->query($query);}
}
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
            <h1 class="h3 mb-0 text-gray-800">Enter Sessional Marks</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Enter Assignment Marks</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Details of classes</h6>
                </div>
                <div class="card-body">
                  
                  <!-- <form method="post">
                          <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Department<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="Department" required>
                          <option value="">Select Department</option>
                          <?php
                          $teacherCode = $_SESSION['teachercode'];
                          $query = "SELECT DISTINCT Department FROM Schedule WHERE TeacherCode = '$teacherCode'";
                          print_r($query);
                          $result = mysqli_query($conn, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo '<option value="' . $row['Department'] . '">' . $row['Department'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Program<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="Program" required>
                          <option value="">Select Program</option>
                          <?php
                          $query = "SELECT DISTINCT Program FROM Schedule WHERE TeacherCode = '$teacherCode'";
                          $result = mysqli_query($conn, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo '<option value="' . $row['Program'] . '">' . $row['Program'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Semester<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="Semester" required>
                          <option value="">Select Semester</option>
                          <?php
                          $query = "SELECT DISTINCT Semester FROM Schedule WHERE TeacherCode = '$teacherCode'";
                          $result = mysqli_query($conn, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo '<option value="' . $row['Semester'] . '">' . $row['Semester'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Subject<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="Subject" required>
                          <option value="">Select Subject</option>
                          <?php
                          $query = "SELECT DISTINCT Subject FROM Schedule WHERE TeacherCode = '$teacherCode'";
                          $result = mysqli_query($conn, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo '<option value="' . $row['Subject'] . '">' . $row['Subject'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Session<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="Session" required>
                          <option value="">Select Session</option>
                          <?php
                          $query = "SELECT Distinct s.SessionID, s.Session FROM  Schedule sc JOIN Sessions s ON sc.Session = s.Session WHERE sc.TeacherCode = '$teacherCode'";
                          print_r($query);
                          $result = mysqli_query($conn, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo '<option value="' . $row['SessionID'] . '">' . $row['Session'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Assessment Type<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="assessment_type" required>
                          <option value="">Select Assessment</option>
                          <option value="Assignment1">Assignment 1</option>
                          <option value="Assignment2">Assignment 2</option>
                          <option value="Assignment3">Assignment 3</option>
                          <option value="Midterm">Midterm</option>
                          <option value="Final">Final</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View Students</button>
                  </form> -->
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Department</th>
                        <th>Program</th>
                        <th>Semester</th>
                        <th>Subject</th>
                        <th>Session</th>
                        <th>Assessment Type</th>
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
                        echo '<td><label class="form-control-label">Assessment Type<span class="text-danger ml-2">*</span></label>
                              <select class="form-control" name="assessment_type" required>
                                <option value="">Select Assessment</option>
                                <option value="Assignment1">Assignment 1</option>
                                <option value="Assignment2">Assignment 2</option>
                                <option value="Assignment3">Assignment 3</option>
                                <option value="Quiz1">Quiz1</option>
                                <option value="Quiz2">Quiz2</option>
                                <option value="Project1">Project1</option>
                                <option value="Project2">Project2</option>

                              </select></td>';
                        echo '<td><button type="submit" name="view" class="btn btn-primary">Enter Marks</button></td>';
                        echo '</form>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <?php
if (isset($_POST['view'])) {
    $department = $_POST['Department'];
    $semester = $_POST['Semester'];
    $program = $_POST['Program'];
    $subject = $_POST['Subject'];
    $sessionid = $_POST['Session'];
    $assesmenttype = $_POST['assessment_type'];

    $schedule = "SELECT * FROM Schedule WHERE Department='$department' AND Program='$program' AND Semester='$semester' AND Subject='$subject'";
    $rs = $conn->query($schedule);
    $num = $rs->num_rows;
    $row = $rs->fetch_assoc();
    $scheduleid = $row['ScheduleID'];

    if ($num > 0) {
        $query = "SELECT * FROM Students WHERE SessionID=$sessionid AND Department='$department' AND Program='$program' AND Semester='$semester'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;

        $query = "SELECT StudentRegNumber AS RegNumber, MarksObtained, TotalMarks FROM AcademicRecord WHERE ScheduleID='$scheduleid' AND AssesmentType='$assesmenttype'";
        $result = $conn->query($query);
        $totalmarks = $result->fetch_assoc();
        $resultsDict = [];
        $resultsDict[$totalmarks['RegNumber']] = $totalmarks['MarksObtained'];
        $totalmarks = $totalmarks['TotalMarks'];
        if($result->num_rows>0){
          echo "<div class='alert alert-warning'>Marks for $assesmenttype are already submitted </div>";
        }
        while ($row = $result->fetch_assoc()) {
            $resultsDict[$row['RegNumber']] = $row['MarksObtained'];
        }

        if ($num > 0) {
            echo "<form method='post'>";
            echo "<input type='hidden' name='subject' value='$subject'>";
            echo "<input type='hidden' name='semester' value='$semester'>";
            echo "<input type='hidden' name='scheduleid' value='$scheduleid'>";
            echo "<input type='hidden' name='assesmenttype' value='$assesmenttype'>";

            echo "<div class='d-flex justify-content-between m-3'>";
          echo "<div class='mt-3 d-flex align-items-center '>";
          echo "<div class='form-group mb-3'>";
          echo "<label class='mr-3 font-weight-bold'>Total Marks:</label> ";
          echo "<input type='number' class='form-control' id='total_marks' name='total_marks' value='$totalmarks' min='0' placeholder='Enter total marks' required>";
          echo "</div>";
          echo "</div>";
          echo '<div class="d-flex align-items-center mr-3">';
          echo '<label class=" font-weight-bold mr-2">Assesment Type:</label>';
          echo '<span class="border-bottom font-weight-normal">' . htmlspecialchars($assesmenttype) . '</span>';
          echo '</div>';
          echo "</div>";

  
           

            echo "<div class='table-responsive p-3'>";
            echo "<table class='table align-items-center table-flush table-hover' id='dataTableHover'>";
            echo "<thead class='thead-light'><tr><th>Student Id</th><th>Name</th><th>Obtained Marks</th></tr></thead><tbody>";

            while ($rows = $rs->fetch_assoc()) {
                $regNumber = $rows['RegNumber'];
                $marks = isset($resultsDict[$regNumber]) ? $resultsDict[$regNumber] : "";

                echo "<tr>";
                echo "<td><input type='hidden' name='student_id[]' value='$regNumber'>$regNumber</td>";
                echo "<td>" . $rows['StudentName'] . "</td>";
                echo "<td><input type='number' class='form-control' name='marks[]' value='$marks' min='0' max='100' placeholder='Enter marks'></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
            echo "<button type='submit' name='submit_marks' class='btn btn-success mt-3'>Submit Marks</button>";
            echo "</div></form>";
        } else {
            echo "<div class='alert alert-warning'>No students found for the given criteria.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Class does not exist in schedule</div>";
    }
}
?>

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
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function() {
        $('#dataTableHover').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "ordering": true
        });
    });
</script>
</body>
</html>
