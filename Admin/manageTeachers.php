<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = '';

if (isset($_POST['save']) || isset($_POST['update'])) {
    $teacherName = $_POST['teacherName'];
    $deptCode = $_POST['deptCode'];
    $deptName = $_POST['deptName'];
    $teacherCode = $_POST['teacherCode'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (isset($_POST['save'])) {
        // Insert teacher
        $stmt = $conn->prepare("SELECT * FROM Teachers WHERE TeacherCode = ?");
        $stmt->bind_param("s", $teacherCode);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $statusMsg = "<div class='alert alert-danger'>This Teacher Code Already Exists!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO Teachers (DeptCode, DeptName, TeacherCode, TeacherName, email, Password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $deptCode, $deptName, $teacherCode, $teacherName, $email, $password);
            if ($stmt->execute()) {
                $statusMsg = "<div class='alert alert-success'>Teacher Created Successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger'>An Error Occurred!</div>";
            }
        }
    }

    if (isset($_POST['update'])) {
        $Id = $_GET['Id'];
        // Update teacher
        $stmt = $conn->prepare("UPDATE Teachers SET DeptCode=?, DeptName=?, TeacherCode=?, TeacherName=?, email=?, Password=? WHERE TeacherID=?");
        $stmt->bind_param("ssssssi", $deptCode, $deptName, $teacherCode, $teacherName, $email, $password, $Id);
        if ($stmt->execute()) {
            echo "<script>window.location = 'manageTeachers.php';</script>";
        } else {
            $statusMsg = "<div class='alert alert-danger'>An Error Occurred!</div>";
        }
    }
}

// Fetch teacher data for editing
$editRow = [];
if (isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['Id'])) {
    $Id = $_GET['Id'];
    $stmt = $conn->prepare("SELECT * FROM Teachers WHERE TeacherID = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $editRow = $stmt->get_result()->fetch_assoc();
}

// Delete teacher
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['Id'])) {
    $Id = $_GET['Id'];
    $stmt = $conn->prepare("DELETE FROM Teachers WHERE TeacherID = ?");
    $stmt->bind_param("i", $Id);
    if ($stmt->execute()) {
        echo "<script>window.location = 'manageTeachers.php';</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>An Error Occurred!</div>";
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
                        <h1 class="h3 mb-0 text-gray-800">Manage Teachers</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Create Teacher</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Teacher Name<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="teacherName" value="<?php echo $editRow['TeacherName'];?>" placeholder="Teacher Name">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Teacher Code<span class="text-danger ml-2">*</span></label>
                                                <input type="number" class="form-control" name="teacherCode" value="<?php echo $editRow['TeacherCode'];?>" placeholder="Teacher Code">
                                            </div>
                                            
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                            <label class="form-control-label">Department<span class="text-danger ml-2">*</span></label>
                                            <select class="form-control" name="deptName"id="departmentSelect" required onchange="updateDeptCode()">
                                            <option value="">Select Department</option>
                                            <?php
                                            $query = "SELECT * FROM Departments";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $selected = ($row['DeptName'] == ($editRow['DeptName'] ?? '')) ? 'selected' : '';
                                                  echo "<option value='{$row['DeptName']}' data-deptcode='{$row['DeptCode']}' $selected>{$row['DeptName']}</option>";
                                                  
                                                print_r($row['DeptCode']);
                                            }
                                            ?>
                                            </select>
                                            </div>
                                            <div class="col-xl-6">
                                            <label class="form-control-label">Department Code<span class="text-danger ml-2">*</span></label>
                                             <input type="number" class="form-control" name="deptCode" value="<?php echo $editRow['DeptCode'];?>" id="deptCodeInput" placeholder="Department Code" readonly>

                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">email<span class="text-danger ml-2">*</span></label>
                                                <input type="email" class="form-control" name="email" value="<?php echo $editRow['Email'];?>" placeholder="email">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Password<span class="text-danger ml-2">*</span></label>
                                                <input type="password" class="form-control" name="password" value="<?php echo $editRow['Password'];?>" placeholder="Password">
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
                                            <h6 class="m-0 font-weight-bold text-primary">All Teachers</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Teacher Name</th>
                                                        <th>Teacher Code</th>
                                                        <th>Department</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT * FROM Teachers";
                                                    $rs = $conn->query($query);
                                                    $sn = 0;
                                                    if ($rs->num_rows > 0) {
                                                        while ($rows = $rs->fetch_assoc()) {
                                                            $sn++;
                                                            echo "
                                                                <tr>
                                                                    <td>".$sn."</td>
                                                                    <td>".$rows['TeacherName']."</td>
                                                                    <td>".$rows['TeacherCode']."</td>
                                                                    <td>".$rows['DeptName']."</td>
                                                                    <td><a href='?action=edit&Id=".$rows['TeacherID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                                                    <td><a href='?action=delete&Id=".$rows['TeacherID']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
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
