<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <img src="img/logo/AIU Transparent Logo.png">
        </div>
        <div class="sidebar-brand-text mx-3">Alhamd</div>
    </a>
    <div class="sidebar-brand-text mx-3 mt-3 text-danger">HOD Panel </div>

    <hr class="sidebar-divider my-1"><!-- Reduce margin around divider -->

    <li class="nav-item mb-2"><!-- Reduce space between items -->
        <a class="nav-link py-2" href="index.php"><!-- Reduce padding inside links -->
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider my-1">


    <li class="nav-item mb-2">
        <a class="nav-link py-2" href="manageStudents.php">
            <i class="fas fa-user-graduate"></i>
            <span>Manage Students</span>
        </a>
    </li>

    <hr class="sidebar-divider my-1">

    <li class="nav-item mb-2">
        <a class="nav-link py-2" href="manageSchedule.php">
            <i class="fa fa-book"></i>
            <span>Manage Schedule</span>
        </a>
    </li>

    <hr class="sidebar-divider my-1">
    <div class="sidebar-heading">
         Attendance Record
     </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
             aria-expanded="true" aria-controls="collapseBootstrapcon">
             <i class="fa fa-calendar-alt"></i>
             <span>Manage Attendance</span>
         </a>
         <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap"
             data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Manage Attendance</h6>
                 <a class="collapse-item" href="viewAttendance.php">View Class Attendance</a>
                 <a class="collapse-item" href="downloadRecord.php">Print Attendence Report (xls)</a>
                 <!-- <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a> -->
             </div>
         </div>
     </li>
     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Sessional Record
     </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap2"
             aria-expanded="true" aria-controls="collapseBootstrap2">
             <i class="fas fa-user-graduate"></i>
             <span>Manage Sessional Marks</span>
         </a>
         <div id="collapseBootstrap2" class="collapse" aria-labelledby="headingBootstrap"
             data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Sessional Record</h6>
                 <a class="collapse-item" href="viewSessionalMarks.php">View Sessional Marks</a>
                 <a class="collapse-item" href="downloadmarksreport.php">Download Marks Report</a>
                 <!-- <a class="collapse-item" href="#">Assets Type</a> -->
             </div>
         </div>
     </li>
</ul>
