<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['department'])) {
    $department = mysqli_real_escape_string($conn, $_POST['department']);

    // Fetch related programs
    $programsQuery = "SELECT ProgramName FROM Programs WHERE Department='$department'";
    $programsResult = mysqli_query($conn, $programsQuery);
    $programs = [];
    while ($row = mysqli_fetch_assoc($programsResult)) {
        $programs[] = $row['ProgramName'];
    }

    // Fetch related subjects
    $subjectsQuery = "SELECT SubjectName FROM Subjects WHERE Department='$department'";
    $subjectsResult = mysqli_query($conn, $subjectsQuery);
    $subjects = [];
    while ($row = mysqli_fetch_assoc($subjectsResult)) {
        $subjects[] = $row['SubjectName'];
    }

    // Fetch related teachers
    $teachersQuery = "SELECT TeacherCode, TeacherName FROM Teachers WHERE deptName='$department'";
    $teachersResult = mysqli_query($conn, $teachersQuery);
    $teachers = [];
    while ($row = mysqli_fetch_assoc($teachersResult)) {
        $teachers[] = [
            'code' => $row['TeacherCode'],
            'name' => $row['TeacherName'],
        ];
    }

    // Return as JSON
    echo json_encode([
        'programs' => $programs,
        'subjects' => $subjects,
        'teachers' => $teachers,
    ]);
}
?>
