
<?php
// Fetch Timetable Data
$sql = "
SELECT 
    s.Day,
    d.DeptName AS Department,
    p.ProgramName AS Program,
    sem.Semester AS Semester,
    subj.SubjectName AS Subject,
    t.TeacherName AS Teacher,
    s.RoomNo AS Room,
    s.Time
FROM 
    Schedule s
LEFT JOIN Departments d ON s.Department = d.DeptName
LEFT JOIN Programs p ON s.Program = p.ProgramName
LEFT JOIN Semesters sem ON s.Semester = sem.Semester
LEFT JOIN Subjects subj ON s.Subject = subj.SubjectName
LEFT JOIN Teachers t ON s.TeacherCode = t.TeacherCode
ORDER BY 
    s.Day, d.DeptName, p.ProgramName, sem.Semester, s.Time;
";

$result = $conn->query($sql);

$timetable = [];

// Organize Data for Display
if ($result->num_rows > 0) {
$department = "";
while ($row = $result->fetch_assoc()) {
    $day = $row['Day'];
    $department = $row['Department'];
    $program = $row['Program'];
    $semester = $row['Semester'];
    $time = $row['Time'];

    $timetable[$day][$department][$program][$semester][$time] = [
        'subject' => $row['Subject'],
        'teacher' => $row['Teacher'],
        'room' => $row['Room']
    ];
}
} else {
echo "No schedule found.";
}

$conn->close();
?>
      <h3 class="text-center">Timetable</h3>
<div class="table-responsive">
<?php if (!empty($timetable)): ?>
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Department</th>
                <th>Program</th>
                <th>Semester</th>
                <th>09:00-10:00</th>
                <th>10:00-11:00</th>
                <th>11:00-12:00</th>
                <th>12:00-01:00</th>
                <th>02:00-03:00</th>
                <th>03:00-04:00</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timetable as $day => $departments): ?>
                <?php 
                // Count total rows for the day
                $rowspan = 0;
                foreach ($departments as $programs) {
                    foreach ($programs as $semesters) {
                        $rowspan += count($semesters);
                    }
                }
                $first_day_row = true; // Track the first row for each day
                ?>
                <?php foreach ($departments as $department => $programs): ?>
                    <?php foreach ($programs as $program => $semesters): ?>
                        <?php foreach ($semesters as $semester => $slots): ?>
                            <tr>
                                <?php if ($first_day_row): ?>
                                    <!-- Add Day cell only for the first row -->
                                    <td rowspan="<?= $rowspan ?>" style="vertical-align: middle; text-align: center;"><?= $day ?></td>
                                    <?php $first_day_row = false; ?>
                                <?php endif; ?>
                                <td><?= $department ?></td>
                                <td><?= $program ?></td>
                                <td><?= $semester ?></td>
                                <?php
                                // Define time slots
                                $time_slots = ['09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-01:00', '02:00-03:00', '03:00-04:00'];
                                foreach ($time_slots as $time) {
                                    if (isset($slots[$time])) {
                                        $details = $slots[$time];
                                        echo "<td class='time-slot'>
                                            <strong>Subject:</strong> {$details['subject']}<br>
                                            <strong>Teacher:</strong> {$details['teacher']}<br>
                                            <strong>Room:</strong> {$details['room']}
                                        </td>";
                                    } else {
                                        echo "<td>-</td>"; // Empty slot
                                    }
                                }
                                ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No timetable available.</p>
<?php endif; ?>
