<?php
require '../vendor/autoload.php'; // PhpSpreadsheet library
include '../Includes/dbcon.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

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
    $teachername = $scheduleData['TeacherName'];

    // Create a new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header section
    // $sheet->mergeCells('A1:H1');
    $sheet->setCellValue('A1', 'Session');
    $sheet->getStyle('A1')->getFont()->setBold(true);     // Bold 'Semester'

    $sheet->mergeCells('B1:C1');
    $sheet->setCellValue('B1', 'Fall 2024');
    // $sheet->mergeCells('A2:H2');
    $sheet->setCellValue('A2', "TeacherName");
    $sheet->getStyle('A2')->getFont()->setBold(true);     // Bold 'Semester'

    $sheet->mergeCells('B2:C2');
    $sheet->setCellValue('B2', "$teachername");
    $sheet->setCellValue('C3', "Lec. Number");
    $sheet->setCellValue('C4', "CrHr");
    $sheet->setCellValue('C5', "Year");
    $sheet->setCellValue('C6', "Month");
    $sheet->setCellValue('C7', "Date");
    $sheet->mergeCells('D1:G1');
    $sheet->mergeCells('D2:G2');
    $sheet->setCellValue('D1', "Department");
    $sheet->getStyle('D1')->getFont()->setBold(true);     // Bold 'Semester'

    $sheet->setCellValue('D2', "Subject");
    $sheet->getStyle('D2')->getFont()->setBold(true);     // Bold 'Semester'

    $sheet->mergeCells('H1:J1');
    $sheet->mergeCells('H2:J2');
    $sheet->setCellValue('H1', $department);
    $sheet->setCellValue('H2', $subject);

    $sheet->mergeCells('K1:L1');
    $sheet->mergeCells('K2:L2');
    $sheet->setCellValue('K1','Program');
    $sheet->setCellValue('K2','Semester');
    $sheet->getStyle('K1')->getFont()->setBold(true); 
    $sheet->getStyle('K2')->getFont()->setBold(true); 

    $sheet->mergeCells('M1:N1');
    $sheet->mergeCells('M2:N2');
    $sheet->setCellValue('M1',$program);
    $sheet->setCellValue('M2',$semester);
     
    
    $sheet->mergeCells('A3:A7');
    $sheet->setCellValue('A3', "Registration Numbers");
    $sheet->getStyle('A3')->getFont()->setBold(true); 
    $sheet->getStyle('A3:A7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);    // Bold 'Semester'

    $sheet->mergeCells('B3:B7');
    $sheet->setCellValue('B3', "Student Names");
    $sheet->getStyle('B3')->getFont()->setBold(true); 
    $sheet->getStyle('B3:B7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);    // Bold 'Semester'



    // Fetch attendance dates
    $datesQuery = $conn->prepare("SELECT DISTINCT CreditHours, Date FROM attendance WHERE ScheduleID = ? ORDER BY Date ASC");
    $datesQuery->bind_param("i", $scheduleID);
    $datesQuery->execute();
    $datesResult = $datesQuery->get_result();

    $creditHoursMap = [];
    $dateColumns = [];
    $currentColumn = 'D';
    $totalcredithours = 0;
    while ($dateRow = $datesResult->fetch_assoc()) {
        $dateObj = new DateTime($dateRow['Date']);

        $creditHoursMap[$dateRow['Date']] = $dateRow['CreditHours'];
        // Get the day, month, and year
        $day = $dateObj->format('d');  // Day (e.g., 17)
        $month = $dateObj->format('m'); // Month (e.g., 11)
        $year = $dateObj->format('Y');  // Year (e.g., 2024)
        $sheet->setCellValue($currentColumn . '5', $year);
        $sheet->setCellValue($currentColumn . '6', $month);
        $sheet->setCellValue($currentColumn . '7', $day);
        $sheet->setCellValue($currentColumn . '4', $dateRow['CreditHours']);
        $dateColumns[$dateRow['Date']] = $currentColumn;
        $currentColumn++;
        $totalcredithours += $dateRow['CreditHours'];
    }
    $currentColumn++;
    $calculationstartcolumn = $currentColumn;
    
    // Table headers
    $sheet->setCellValue($currentColumn++. '7', 'Total');
    $sheet->setCellValue($currentColumn++. '7', 'Obt');
    $sheet->setCellValue($currentColumn++. '7', '100%');
    $sheet->setCellValue($currentColumn++. '7', '10%');
    $sheet->setCellValue($currentColumn++. '7', 'obtained CrHr');
    $sheet->setCellValue($currentColumn++. '7', 'Status');
    $sheet->setCellValue('L4', 'TotalCrHr');
    $sheet->setCellValue('L5', "$totalcredithours");
    // Fetch attendance data
    $attendanceQuery = $conn->prepare("SELECT DISTINCT StudentRegNumber, Date, Status FROM attendance WHERE ScheduleID = ?");
    $attendanceQuery->bind_param("i", $scheduleID);
    $attendanceQuery->execute();
    $attendanceResult = $attendanceQuery->get_result();
    
    $students = [];
    while ($row = $attendanceResult->fetch_assoc()) {
        $regNumber = $row['StudentRegNumber'];
        $date = $row['Date'];
        $status = $row['Status'];

        if (!isset($students[$regNumber])) {
            $students[$regNumber] = [];
        }
        $students[$regNumber][$date] = $status;
    }

    // Write student attendance data
    $currentRow = 9; // Start below the headers
    foreach ($students as $regNumber => $statuses) {
        $studentname = "SELECT StudentName FROM Students WHERE RegNumber= '$regNumber'";
        $studentname = $conn->query($studentname);
        $studentname = $studentname->fetch_assoc();
        $studentname = $studentname['StudentName'];
        $sheet->setCellValue('A' . $currentRow, $regNumber);
        $sheet->mergeCells('B'. $currentRow .':C'. $currentRow);
        $sheet->setCellValue('B' . $currentRow, $studentname); // Replace with actual student name if available

        $obt = 0;
        $total=0;
        $totCrHr = 0;
        $currentColumn = 'D';
        foreach ($dateColumns as $date => $column) {
            $status = $statuses[$date] ?? '-'; // Default to '-' if no data
            $creditHoursForDate = $creditHoursMap[$date];
            if($status=="Present"){
                $status='P';
            }elseif($status=="Absent"){
                $status="A";
            }elseif($status=="Leave"){
                $status="L";
            }
            $sheet->setCellValue($column . $currentRow, $status);
            if ($status === 'P') {
                $obt++;
                $totCrHr += $creditHoursForDate;
            }
            $total+=1;
        }

        $tmpcolumn = $calculationstartcolumn;
        $sheet->setCellValue($tmpcolumn++ . $currentRow, $total); // Total days attended
        $sheet->setCellValue($tmpcolumn++ . $currentRow, $obt); // Total days attended
        
        $sheet->setCellValue($tmpcolumn++ . $currentRow, intval(($obt/$total)*100));
        $sheet->setCellValue($tmpcolumn++ . $currentRow, intval(($obt/$total)*10));
        $sheet->setCellValue($tmpcolumn++ . $currentRow, $totCrHr);
        $sheet->setCellValue($tmpcolumn++ . $currentRow, intval(($obt/$total)*100) >= 75 ? 'Allowed' : 'Not-Allowed'); // Example threshold
        $currentRow++;
    }

    // Apply formatting
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];
    $sheet->getStyle('A7:X' . $currentRow)->applyFromArray($styleArray);
    $sheet->getStyle('A7:X' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    foreach (range('A', 'X') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // // Output Excel file
    // $fileName = "Attendance_Report_Schedule_$scheduleID _" . date("Y-m-d_H-i-s") . ".xlsx";
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="' . $fileName . '"');
    // header('Cache-Control: max-age=0');

    // $writer = new Xlsx($spreadsheet);
    // $writer->save('php://output');

    // Generate PDF
    $fileName = "Attendance_Report_Schedule_$scheduleID _" . date("Y-m-d_H-i-s") . ".pdf";
    $spreadsheet->getActiveSheet()->setShowGridlines(true); // Ensure gridlines are visible

    $pdfWriter = new Mpdf($spreadsheet);

    // Set headers for PDF download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    // Save the PDF to output
    $pdfWriter->save('php://output');

    exit;
} else {
    echo "Invalid request. Schedule ID not found.";
}
?>
