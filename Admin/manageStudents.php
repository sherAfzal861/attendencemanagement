<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['save'])){
    $studentName = $_POST['studentName'];
    $studentCode = $_POST['studentCode'];
    $department = $_POST['department'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM Students WHERE StudentCode = '$studentCode'");
    $ret = mysqli_fetch_array($query);

    if ($ret > 0) { 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Student Code Already Exists!</div>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO Students (StudentCode, StudentName, Department, Password) VALUES ('$studentCode', '$studentName', '$department', '$password')");
        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Student Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM Students WHERE StudentID = '$Id'");
    $editRow = mysqli_fetch_array($query);
    $query = mysqli_query($conn, "SELECT Session FROM Sessions WHERE SessionID = '$editRow[SessionID]'");
    $session = mysqli_fetch_array($query);
    $session = $session['Session'];

    if (isset($_POST['update'])) {
        $studentName = $_POST['studentName'];
        $studentCode = $_POST['studentCode'];
        $department = $_POST['department'];
        $password = $_POST['password'];
        $sessionid = $_POST['Session'];
        $program = $_POST['Program'];
        $semester = $_POST['Semester'];
        $query = mysqli_query($conn, "UPDATE Students SET SessionID='$sessionid', RegNumber='$studentCode', StudentName='$studentName', Department='$department',Program='$program',Semester='$semester', Password='$password' WHERE StudentID='$Id'");
        if ($query) {
            echo "<script type = \"text/javascript\">
            window.location = (\"manageStudents.php\")
            </script>"; 
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "DELETE FROM Students WHERE StudentID='$Id'");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"manageStudents.php\")
        </script>";  
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "Includes/sidebar.php";?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "Includes/topbar.php";?>
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manage Students</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Create Student</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Student Name<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="studentName" value="<?php echo $editRow['StudentName'];?>" placeholder="Student Name">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Student Registration Number<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="studentCode" value="<?php echo $editRow['RegNumber'];?>" placeholder="Student Code">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                        <div class="col-xl-6">
                                                <label class="form-control-label">Program<span class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Program" required>
                                                <option value="">Select Program</option>
                                                <?php
                                                $query = "SELECT ProgramName FROM Programs";
                                                print_r($query);
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $selected = ($row['ProgramName'] == ($editRow['Program'] ?? '')) ? 'selected' : '';
                                                  echo "<option value='{$row['ProgramName']}' $selected>{$row['ProgramName']}</option>";
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Semester<span class="text-danger ml-2">*</span></label>
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
                                            <label class="form-control-label">Department<span class="text-danger ml-2">*</span></label>
                                            <select class="form-control" name="department"id="departmentSelect" required onchange="updateDeptCode()">
                                            <option value="">Select Department</option>
                                            <?php
                                            $query = "SELECT * FROM Departments";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $selected = ($row['DeptName'] == ($editRow['Department'] ?? '')) ? 'selected' : '';
                                                  echo "<option value='{$row['DeptName']}' data-deptcode='{$row['DeptCode']}' $selected>{$row['DeptName']}</option>";
                                            }
                                            ?>
                                            </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Password<span class="text-danger ml-2">*</span></label>
                                                <input type="password" class="form-control" name="password" value="<?php echo $editRow['Password'];?>" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                        <div class="col-xl-6">
                                                <label class="form-control-label">Session<span class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="Session" required>
                                                <option value="">Select Session</option>
                                                <?php
                                                $query = "SELECT * FROM Sessions";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $selected = ($row['Session'] == ($session ?? '')) ? 'selected' : '';
                                                  echo "<option value='{$row['SessionID']}' $selected>{$row['Session']}</option>";
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
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student Name</th>
                                                        <th>Student Code</th>
                                                        <th>Department</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT * FROM Students";
                                                    $rs = $conn->query($query);
                                                    $sn = 0;
                                                    if ($rs->num_rows > 0) {
                                                        while ($rows = $rs->fetch_assoc()) {
                                                            $sn++;
                                                            echo "
                                                                <tr>
                                                                    <td>".$sn."</td>
                                                                    <td>".$rows['StudentName']."</td>
                                                                    <td>".$rows['StudentCode']."</td>
                                                                    <td>".$rows['Department']."</td>
                                                                    <td><a href='?action=edit&Id=".$rows['StudentID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                                                    <td><a href='?action=delete&Id=".$rows['StudentID']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                                                </tr>";
                                                        }
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
        </div>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTableHover').DataTable();
        });
    </script>
</body>
</html>
