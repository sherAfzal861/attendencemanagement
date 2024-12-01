<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Assuming $conn is your database connection
if (isset($_POST['submit_attendance'])) {
  $subject = $_POST['subject'];
  $semester = $_POST['semester'];
  $scheduleID = $_POST['scheduleid'];
  $date = $_POST['date']; // Current date or use $_POST['date'] if provided in the form
  $students = $_POST['student_id'];
  $statuses = $_POST['status'];
  $credithours = $_POST['credithours'];
  $success = true;

  $query = "SELECT * FROM Attendance WHERE Date = '$date' AND ScheduleID = $scheduleID";
  $record = $conn->query($query);
  $numrecord = $record->num_rows;
  if ($numrecord>0){
    echo "<div class='alert alert-warning'>Attendance for $date Already marked if you want to change go to view attendance section</div>"; 
  }else{
    foreach ($students as $student) {
      $status = isset($statuses[$student]) ? $statuses[$student] : 'Absent'; // Default to 'Absent' if not set

      $sql = "INSERT INTO Attendance (StudentRegNumber, ScheduleID, Date, Status, CreditHours) 
              VALUES ('$student', '$scheduleID', '$date', '$status', '$credithours')";
      if (!$conn->query($sql)) {
        $success = false; // If any query fails, set success flag to false
        break; // Stop further execution on error
      }
  }
  if ($success) {
    $alertMessage = "Attendance successfully submitted!";
    $alertType = "success";
  } else {
      $alertMessage = "There was an error submitting the attendance.";
      $alertType = "danger";
  }
  }
  
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
                        <h1 class="h3 mb-0 text-gray-800">Mark Attendance</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mark Attendance</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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
                          $query = "SELECT DISTINCT Session FROM Schedule WHERE TeacherCode = '$teacherCode'";
                          $result = mysqli_query($conn, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo '<option value="' . $row['Session'] . '">' . $row['Session'] . '</option>';
                          }
                          ?>
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

                                                <th>Select Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                      $teacherCode = $_SESSION['teachercode'];
                      $query = "SELECT * FROM Schedule WHERE TeacherCode = '$teacherCode'";
                      print_r($teacherCode);
                      $result = mysqli_query($conn, $query);
                      print_r($result);
                      
                      while ($row = mysqli_fetch_assoc($result)) {
                        $sessionquery = "SELECT SessionID from Sessions WHERE Session='$row[Session]'";
                        $sessionquery = $conn->query($sessionquery);
                        $sessionquery = $sessionquery->fetch_assoc();
                        $sessionid = $sessionquery['SessionID'];
                        echo '<tr>';
                        echo '<form method="post">';
                        echo "<input type='hidden' name='scheduleid' value='$row[ScheduleID]'>";
                        echo '<td>' . $row['Department'] . '<input type="hidden" name="Department" value="' . $row['Department'] . '"></td>';
                        echo '<td>' . $row['Program'] . '<input type="hidden" name="Program" value="' . $row['Program'] . '"></td>';
                        echo '<td>' . $row['Semester'] . '<input type="hidden" name="Semester" value="' . $row['Semester'] . '"></td>';
                        echo '<td>' . $row['Subject'] . '<input type="hidden" name="Subject" value="' . $row['Subject'] . '"></td>';
                        echo '<td>' . $row['Session'] . '<input type="hidden" name="Session" value="' . $sessionid . '"></td>';
                        echo '<td>
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="date" required>
                        </td>';
                        echo '<td><button type="submit" name="view" class="btn btn-primary">Take Attendance</button></td>';
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
    $scheduleid = $_POST['scheduleid'];
    $date = $_POST['date'];
    $sessionid = $_POST['Session'];

    $query = "SELECT * FROM Attendance WHERE Date = '$date' AND ScheduleID = $scheduleid";
    $record = $conn->query($query);
    $numrecord = $record->num_rows;
    if ($numrecord>0){
      echo "<div class='alert alert-warning'>Attendance for $date Already marked if you want to change go to view attendance section</div>"; 
    }
    else{
      $query = "SELECT * FROM Students WHERE SessionID='$sessionid' AND Department='$department' AND Program='$program' AND Semester='$semester'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      
      if ($num > 0) {
        
          echo "<form method='post' id='attendanceForm'>";
          echo "<input type='hidden' name='subject' value='$subject'>";
          echo "<input type='hidden' name='semester' value='$semester'>";
          echo "<input type='hidden' name='scheduleid' value='$scheduleid'>";
          echo "<input type='hidden' name='date' value='$date'>";
          echo "<div class='d-flex justify-content-between m-3'>";
          echo "<div class='mt-3 d-flex align-items-center '>";
          echo "<label class='mr-3 font-weight-bold'>Mark All:</label> ";
          echo "<div class='form-check form-check-inline'>";
          echo "<input class='form-check-input' type='radio' name='mark_all' id='markAllPresent' value='Present' onclick='markAll(\"Present\")'>";
          echo "<label class='form-check-label' for='markAllPresent'>All Present</label>";
          echo "</div>";
          echo "<div class='form-check form-check-inline'>";
          echo "<input class='form-check-input' type='radio' name='mark_all' id='markAllAbsent' value='Absent' onclick='markAll(\"Absent\")'>";
          echo "<label class='form-check-label' for='markAllAbsent'>All Absent</label>";
          echo "</div>";
          echo "<div class='form-check form-check-inline'>";
          echo "<input class='form-check-input' type='radio' name='mark_all' id='markAllLeave' value='Leave' onclick='markAll(\"Leave\")'>";
          echo "<label class='form-check-label' for='markAllLeave'>All Leave</label>";
          echo "</div>";
          
          echo "</div>";
          echo "<div class='mb-3'>";
          echo "<label for='credit_hours' class='form-label'>Select Contact Hours:</label>";
          echo "<select name='credithours' id='credithours' class='form-control' required>";
          echo "<option value='' disabled selected>--Select a value--</option>";
          echo "<option value='1'>1</option>";
          echo "<option value='1.5'>1.5</option>";
          echo "<option value='2'>2</option>";
          echo "<option value='3'>3</option>";
          echo "</select>";
          echo "</div>";
          echo '<div class="d-flex align-items-center mr-3">';
          echo '<label class=" font-weight-bold mr-2">Date:</label>';
          echo '<span class="border-bottom font-weight-normal">' . htmlspecialchars($date) . '</span>';
          echo '</div>';
          echo "</div>";

          echo "<div class='table-responsive p-3'>";
          echo "<table class='table align-items-center table-flush table-hover' id='dataTableHover'>";
          echo "<thead class='thead-light'><tr><th>Registration Number</th><th>Name</th><th>Status</th></tr></thead><tbody>";

          while ($rows = $rs->fetch_assoc()) {
              echo "<tr>";
              echo "<td><input type='hidden' name='student_id[]' value='" . $rows['RegNumber'] . "'>" . $rows['RegNumber'] . "</td>";
              echo "<td>" . $rows['StudentName'] . "</td>";
              echo "<td>";
              echo "<div class='form-check form-check-inline'>";
              echo "<input class='form-check-input status-radio' type='radio' name='status[" . $rows['RegNumber'] . "]' value='Present' required> Present";
              echo "</div>";
              echo "<div class='form-check form-check-inline'>";
              echo "<input class='form-check-input status-radio' type='radio' name='status[" . $rows['RegNumber'] . "]' value='Absent'> Absent";
              echo "</div>";
              echo "<div class='form-check form-check-inline'>";
              echo "<input class='form-check-input status-radio' type='radio' name='status[" . $rows['RegNumber'] . "]' value='Leave'> Leave";
              echo "</div>";
              echo "</td>";
              echo "</tr>";
          }
          echo "</tbody></table>";
          echo "<button type='submit' name='submit_attendance' class='btn btn-success mt-3'>Submit Attendance</button>";
          echo "</div></form>";

      }else{
        echo "<div class='alert alert-warning'>No students found for the given criteria.</div>";
      }
      
    }
    
    
}
?>

                            <script>
                            function markAll(status) {
                                const radios = document.querySelectorAll('.status-radio');
                                radios.forEach(radio => {
                                    if (radio.value === status) {
                                        radio.checked = true;
                                    }
                                });
                            }
                            </script>

                        </div>
                    </div>
                    <?php if (isset($alertMessage)): ?>
                    <div class="alert alert-<?= $alertType ?>"><?= $alertMessage ?></div>
                    <?php endif; ?>
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