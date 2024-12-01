<?php
require_once '../vendor/autoload.php'; // Load mPDF library
include '../Includes/dbcon.php'; // Database connection

if (isset($_POST['scheduleid'])) {
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

    // Start generating the PDF
    $mpdf = new \Mpdf\Mpdf();
    $html = "
        <h1 style='text-align: center;'>Sessional Record</h1>
        <table style='width: 100%; border-collapse: collapse;'>
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
        <table border='1' style='width: 100%; border-collapse: collapse; text-align: center;'>
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
        $html .= "
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
    $html .= "
            </tbody>
        </table>
    ";

    // Output the PDF
    $mpdf->WriteHTML($html);
    $fileName = "Marks_Report_Schedule_$scheduleID.pdf";
    $mpdf->Output($fileName, 'D'); // Download the PDF file
    exit;
} else {
    echo "Invalid request. Schedule ID not found.";
}
?>
