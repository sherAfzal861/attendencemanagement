
<?php 
include 'Includes/dbcon.php';
session_start();
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
  <title>Alhamd Academics System Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpg'); background-size: cover; background-repeat: no-repeat;">
  <!-- Login Content -->
  <div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
      <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="card shadow-sm">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <h5 class="text-center">Alhamd Academics System</h5>
                  <div class="text-center">
                    <img src="img/logo/AIU Transparent Logo.png" style="width:100px;height:100px">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                  </div>
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <select required name="userType" class="form-control mb-3">
                        <option value="">--Select User Roles--</option>
                        <option value="Administrator">Administrator</option>
                        <option value="ClassTeacher">ClassTeacher</option>
                        <option value="HOD">Head Of Department</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-success btn-block" value="Login" name="login" />
                    </div>
                  </form>

                     <?php
if (isset($_POST['login'])) {

    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($userType == "Administrator") {
        // Query for administrator
        $query = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();

        if ($num > 0) {
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['firstName'] = $rows['firstName'];
            $_SESSION['lastName'] = $rows['lastName'];
            $_SESSION['emailAddress'] = $rows['emailAddress'];

            echo "<script type=\"text/javascript\">
            window.location = (\"Admin/index.php\")
            </script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";
        }
    } elseif ($userType == "ClassTeacher") {
        // Query for class teacher in the Teachers table
        $query = "SELECT * FROM Teachers WHERE email = '$username' AND Password = '$password'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();

        if ($num > 0) {
            $_SESSION['userId'] = $rows['TeacherID'];
            $_SESSION['teacherName'] = $rows['TeacherName'];
            $_SESSION['deptCode'] = $rows['DeptCode'];
            $_SESSION['deptName'] = $rows['DeptName'];
            $_SESSION['teachercode']=$rows['TeacherCode'];
            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";
            echo "<script type=\"text/javascript\">
            window.location = (\"ClassTeacher/index.php\")
            </script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";
        }
    }elseif ($userType == "HOD") {
      // Query to check for the teacher in the Teachers table
      $query = "SELECT * FROM Teachers WHERE email = '$username' AND Password = '$password'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();
  
      if ($num > 0) {
          // Teacher is found, check if they are an HOD
          $teacherID = $rows['TeacherID'];
          $hodQuery = "SELECT HODID FROM HOD WHERE TeacherID = '$teacherID'";
          $hodResult = $conn->query($hodQuery);
          $isHOD = $hodResult->num_rows > 0;
  
          // Store teacher information in the session
          $_SESSION['userId'] = $teacherID;
          $_SESSION['teacherName'] = $rows['TeacherName'];
          $_SESSION['deptCode'] = $rows['DeptCode'];
          $_SESSION['deptName'] = $rows['DeptName'];
          $_SESSION['teachercode'] = $rows['TeacherCode'];
  
          // If the teacher is an HOD, add HODID to the session
          if ($isHOD) {
              $hodRow = $hodResult->fetch_assoc();
              $_SESSION['HODID'] = $hodRow['HODID'];
              // Redirect to the HOD dashboard
              echo "<script type=\"text/javascript\">
              window.location = (\"HOD/index.php\")
              </script>";
          }else{
              // Invalid credentials
              echo "<div class='alert alert-danger' role='alert'>
              You are not HOD
              </div>";
          }
  
          
      } else {
          // Invalid credentials
          echo "<div class='alert alert-danger' role='alert'>
          Invalid Username/Password!
          </div>";
      }
  }elseif ($userType == "Student") {
        // Query for student login
        $query = "SELECT * FROM Students WHERE RegNumber = '$username' AND Password = '$password'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();

        if ($num > 0) {
            $_SESSION['userId'] = $rows['SerialNo'];
            $_SESSION['studentName'] = $rows['StudentName'];
            $_SESSION['department'] = $rows['Department'];
            $_SESSION['program'] = $rows['Program'];
            $_SESSION['semester'] = $rows['Semester'];

            echo "<script type=\"text/javascript\">
            window.location = (\"Student/index.php\")
            </script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";
    }
}
?>


                    <!-- <hr>
                    <a href="index.html" class="btn btn-google btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->

                
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>