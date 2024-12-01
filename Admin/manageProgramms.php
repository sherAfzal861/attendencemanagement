<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['save'])){
    $programName = $_POST['programName'];
    $programCode = $_POST['programCode'];
    $department = $_POST['department'];
    print_r($_POST);
    $query = mysqli_query($conn, "SELECT * FROM Programs WHERE ProCode = '$programCode'");
    $ret = mysqli_fetch_array($query);

    if ($ret > 0) { 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Program Code Already Exists!</div>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO Programs (ProCode, ProgramName, Department) VALUES ('$programCode', '$programName', '$department')");
        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Program Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM Programs WHERE ProgramID = '$Id'");
    $editRow = mysqli_fetch_array($query);

    if (isset($_POST['update'])) {
        $programName = $_POST['programName'];
        $programCode = $_POST['programCode'];
        $department = $_POST['department'];

        $query = mysqli_query($conn, "UPDATE Programs SET ProCode='$programCode', ProgramName='$programName', Department='$department' WHERE ProgramID='$Id'");
        if ($query) {
            echo "<script type = \"text/javascript\">
            window.location = (\"manageProgramms.php\")
            </script>"; 
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "DELETE FROM Programs WHERE ProgramID='$Id'");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"manageProgramms.php\")
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
                        <h1 class="h3 mb-0 text-gray-800">Manage Programs</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Create Program</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Program Name<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="programName" value="<?php echo $editRow['ProgramName'];?>" placeholder="Program Name">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Program Code<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="programCode" value="<?php echo $editRow['ProCode'];?>" placeholder="Program Code">
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
                                            <h6 class="m-0 font-weight-bold text-primary">All Programs</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Program Name</th>
                                                        <th>Program Code</th>
                                                        <th>Department</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT * FROM Programs";
                                                    $rs = $conn->query($query);
                                                    $sn = 0;
                                                    if ($rs->num_rows > 0) {
                                                        while ($rows = $rs->fetch_assoc()) {
                                                            $sn++;
                                                            echo "
                                                                <tr>
                                                                    <td>".$sn."</td>
                                                                    <td>".$rows['ProgramName']."</td>
                                                                    <td>".$rows['ProgramCode']."</td>
                                                                    <td>".$rows['Department']."</td>
                                                                    <td><a href='?action=edit&Id=".$rows['ProgramID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                                                    <td><a href='?action=delete&Id=".$rows['ProgramID']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
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
