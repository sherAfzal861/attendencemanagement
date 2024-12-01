<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
// Handle Save Operation
if (isset($_POST['save'])) {
    $department = $_POST['department'];
    $program = $_POST['Program'];
    $semester = $_POST['Semester'];
    $subject = $_POST['Subject'];
    $teacherCode = $_POST['Teacher'];
    $session = $_POST['Session'];
    $day = $_POST['Day'];
    $time = $_POST['Time'];
    
    // Check if the schedule already exists
    $query = mysqli_query($conn, "SELECT * FROM Schedule WHERE Department='$department' AND Program='$program' AND Semester='$semester' AND Subject='$subject' AND Session='$session'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Schedule Already Exists!</div>";
    } else {
        // Insert new schedule
        $query = mysqli_query($conn, "INSERT INTO Schedule (Department, Program, Semester, Subject, TeacherCode, Session, Day, Time) VALUES ('$department', '$program', '$semester', '$subject', '$teacherCode', '$session', '$day', '$time')");
        if ($query) {
            
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Schedule Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An Error Occurred While Creating the Schedule!</div>";
        }
    }
}

// Handle Edit Operation
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM Schedule WHERE ScheduleID='$Id'");
    $editRow = mysqli_fetch_array($query);

    $teachername = mysqli_query($conn, "SELECT TeacherName FROM Teachers WHERE TeacherCode='{$editRow['TeacherCode']}'");
    $teachername = mysqli_fetch_array($teachername);
    $teachername = $teachername['TeacherName'];

    if (isset($_POST['update'])) {
        $department = $_POST['department'];
        $program = $_POST['Program'];
        $semester = $_POST['Semester'];
        $subject = $_POST['Subject'];
        $teacherCode = $_POST['Teacher'];
        $session = $_POST['Session'];
        $day = $_POST['Day'];
        $time = $_POST['Time'];

        // Update schedule
        $query = mysqli_query($conn, "UPDATE Schedule SET Department='$department', Program='$program', Semester='$semester', Subject='$subject', TeacherCode='$teacherCode', Session='$session', Day='$day', Time='$time' WHERE ScheduleID='$Id'");
        if ($query) {
            
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Schedule Updated Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An Error Occurred While Updating the Schedule!</div>";
        }
    }
}

// Handle Delete Operation
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $scheduleID = $_GET['Id'];
    
    // Delete schedule
    $query = mysqli_query($conn, "DELETE FROM Schedule WHERE ScheduleID='$scheduleID'");
    if ($query) {
        
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Schedule Deleted Successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An Error Occurred While Deleting the Schedule! Check for Dependent Records.</div>";
    }
}


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

    <?php include 'includes/title.php';?>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }

    h2 {
        margin-top: 20px;
        text-align: center;
    }

    .time-slot {
        font-size: 0.9em;
        line-height: 1.5;
    }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "Includes/sidebar.php";?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "Includes/topbar.php";?>
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manage Schedule</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Schedule</li>
                        </ol>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Set Schedule</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Department<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="department" id="departmentSelect"
                                                    required onchange="updateDeptCode()">
                                                    <option value="">Select Department</option>
                                                    <?php
                                                        $dept = $_SESSION['deptName'];
                                                        $query = "SELECT * FROM Departments WHERE deptName = '$dept'";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $selected = ($row['DeptName'] == ($editRow['Department'] ?? '')) ? 'selected' : '';
                                                            echo "<option value='{$row['DeptName']}' data-deptcode='{$row['DeptCode']}' $selected>{$row['DeptName']}</option>";
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Program<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Program" required>
                                                    <option value="">Select Program</option>
                                                    <?php
                                                    $dept = $_SESSION['deptName'];
                                                    $query = "SELECT ProgramName FROM Programs WHERE Department = '$dept'";
                                                    print_r($query);
                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $selected = ($row['ProgramName'] == ($editRow['Program'] ?? '')) ? 'selected' : '';
                                                    echo "<option value='{$row['ProgramName']}' $selected>{$row['ProgramName']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Subject<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Subject" required>
                                                    <option value="">Select Subject</option>
                                                    <?php
                                                        $dept = $_SESSION['deptName'];
                                                        $query = "SELECT SubjectName FROM Subjects WHERE Department = '$dept'";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $selected = ($row['SubjectName'] == ($editRow['Subject'] ?? '')) ? 'selected' : '';
                                                        echo "<option value='{$row['SubjectName']}' $selected>{$row['SubjectName']}</option>";
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Semester<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Semester" required>
                                                    <option value="">Select Semester</option>
                                                    <?php
                                                $query = "SELECT Semester FROM Semesters";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $selected = ($row['Semester'] == ($editRow['Semester'] ?? '')) ? 'selected' : '';
                                                  echo "<option value='{$row['Semester']}' $selected>{$row['Semester']}</option>";
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Day<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Day" required>
                                                    <option value="">Select Day</option>
                                                    <option value="Monday"
                                                        <?= ($editRow['Day'] ?? '') == "Monday" ? 'selected' : '' ?>>
                                                        Monday</option>
                                                    <option value="Tuesday"
                                                        <?= ($editRow['Day'] ?? '') == 'Tuesday' ? 'selected' : '' ?>>
                                                        Tuesday</option>
                                                    <option value="Wednesday"
                                                        <?= ($editRow['Day'] ?? '') == 'Wednesday' ? 'selected' : '' ?>>
                                                        Wednesday</option>
                                                    <option value="Thursday"
                                                        <?= ($editRow['Day'] ?? '') == 'Thursday' ? 'selected' : '' ?>>
                                                        Thursday</option>
                                                    <option value="Friday"
                                                        <?= ($editRow['Day'] ?? '') == 'Friday' ? 'selected' : '' ?>>
                                                        Friday</option>
                                                    <option value="Saturday"
                                                        <?= ($editRow['Day'] ?? '') == 'Saturday' ? 'selected' : '' ?>>
                                                        Saturday</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Time<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="Time" placeholder="00:00-00:00" 
                                                    pattern="\d{2}:\d{2}-\d{2}:\d{2}" 
                                                    title="Time must be in the format HH:MM-HH:MM" 
                                                    value="<?php echo isset($editRow) ? $editRow['Time'] : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Session<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Session" required>
                                                    <option value="">Select Session</option>
                                                    <?php
                                                $query = "SELECT * FROM Sessions";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $selected = ($row['Session'] == ($editRow['Session'] ?? '')) ? 'selected' : '';
                                                  echo "<option value='{$row['Session']}' $selected>{$row['Session']}</option>";
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Teacher<span
                                                        class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Teacher" required>
                                                    <option value="">Select Teacher</option>
                                                    <?php
                                                    $dept = $_SESSION['deptName'];
                                                    $query = "SELECT * FROM Teachers WHERE DeptName = '$dept'";
                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $selected = ($row['TeacherName'] == ($teachername ?? '')) ? 'selected' : '';
                                                    echo "<option value='{$row['TeacherCode']}' data-deptcode='{$row['TeacherName']}' $selected>{$row['TeacherName']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if (isset($Id)) { ?>
                                        <button type="submit" name="update" class="btn btn-warning">Update</button>
                                        <?php } else { ?>
                                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div
                                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">All Teachers</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Day</th>
                                                        <th>Time</th>
                                                        <th>Department</th>
                                                        <th>Program</th>
                                                        <th>Semester</th>
                                                        <th>Subject</th>
                                                        <th>Teacher</th>
                                                        <th>Session</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $dept = $_SESSION['deptName'];
                                                    $query = "SELECT * FROM Schedule WHERE Department = '$dept'";
                                                    $rs = $conn->query($query);
                                                    $sn = 0;
                                                    if ($rs->num_rows > 0) {
                                                        while ($rows = $rs->fetch_assoc()) {
                                                            $query = "SELECT TeacherName FROM Teachers WHERE TeacherCode=$rows[TeacherCode]";
                                                            $teachername = $conn->query($query);
                                                            $teachername = $teachername->fetch_assoc();
                                                            $teachername = $teachername['TeacherName'];
                                                            $sn++;
                                                            echo "
                                                                <tr>
                                                                    <td>{$sn}</td>
                                                                    <td>{$rows['Day']}</td>
                                                                    <td>{$rows['Time']}</td>
                                                                    <td>{$rows['Department']}</td>
                                                                    <td>{$rows['Program']}</td>
                                                                    <td>{$rows['Semester']}</td>
                                                                    <td>{$rows['Subject']}</td>
                                                                    <td>{$teachername}</td>
                                                                    <td>{$rows['Session']}</td>
                                                                    <td><a href='?action=edit&Id={$rows['ScheduleID']}'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                                                    <td><a href='?action=delete&Id={$rows['ScheduleID']}'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                                                </tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>


                                        <?php include "Includes/timetable.php";?>
                                    </div>
                                </div>
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
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#dataTableHover').DataTable();
    });
    </script>
    <script>
    function updateDeptCode() {
        var selectElement = document.getElementById("departmentSelect");
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var deptCode = selectedOption.getAttribute("data-deptcode");
        document.getElementById("deptCodeInput").value = deptCode || '';

    }
    </script>
</body>

</html>