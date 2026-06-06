<?php
$conn = new mysqli("localhost", "root", "", "studentrecordsV1");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_analytics_report.csv"');

$output = fopen("php://output", "w");

/* ================= HEADER ================= */
fputcsv($output, ["STUDENT ANALYTICS REPORT"]);
fputcsv($output, []);
fputcsv($output, ["Student ID", "Name", "Course", "Year Level", "Subject", "Midterm", "Final", "Average", "Remarks"]);

/* ================= FULL STUDENT GRADE DETAILS ================= */
$res = $conn->query("
SELECT 
    Students.StudentID,
    Students.FirstName,
    Students.LastName,
    Students.Course,
    Students.YearLevel,
    Subjects.subject_name,
    Grades.midterm,
    Grades.final,
    Grades.average,
    Grades.remarks
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
JOIN Subjects ON Grades.subject_id = Subjects.subject_id
ORDER BY Students.StudentID
");

while ($row = $res->fetch_assoc()) {

    fputcsv($output, [
        $row['StudentID'],
        $row['FirstName'] . " " . $row['LastName'],
        $row['Course'],
        $row['YearLevel'],
        $row['subject_name'],
        $row['midterm'],
        $row['final'],
        $row['average'],
        $row['remarks']
    ]);
}

/* ================= SPACING ================= */
fputcsv($output, []);
fputcsv($output, ["SUMMARY SECTION"]);
fputcsv($output, []);

/* ================= STUDENTS PER COURSE ================= */
fputcsv($output, ["Course", "Total Students"]);

$res = $conn->query("
SELECT Course, COUNT(*) AS total
FROM Students
GROUP BY Course
");

while ($row = $res->fetch_assoc()) {
    fputcsv($output, [$row['Course'], $row['total']]);
}

fputcsv($output, []);

/* ================= TOP STUDENTS (1.0 - 2.0) ================= */
fputcsv($output, ["Top Students (1.0 - 2.0)", "Average Grade"]);

$res = $conn->query("
SELECT Students.FirstName, Students.LastName,
AVG(Grades.average) AS avg_grade
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
GROUP BY Students.StudentID
HAVING AVG(Grades.average) <= 2.0
ORDER BY avg_grade ASC
");

while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['FirstName'] . " " . $row['LastName'],
        round($row['avg_grade'], 2)
    ]);
}

fputcsv($output, []);

/* ================= AVERAGE STUDENTS (2.0 - 3.0) ================= */
fputcsv($output, ["Average Students (2.0 - 3.0)", "Average Grade"]);

$res = $conn->query("
SELECT Students.FirstName, Students.LastName,
AVG(Grades.average) AS avg_grade
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
GROUP BY Students.StudentID
HAVING AVG(Grades.average) > 2.0
AND AVG(Grades.average) < 3.0
ORDER BY avg_grade ASC
");

while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['FirstName'] . " " . $row['LastName'],
        round($row['avg_grade'], 2)
    ]);
}

fputcsv($output, []);

/* ================= AT RISK (3.5 - 5.0) ================= */
fputcsv($output, ["At Risk Students (3.5 - 5.0)", "Average Grade"]);

$res = $conn->query("
SELECT Students.FirstName, Students.LastName,
AVG(Grades.average) AS avg_grade
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
GROUP BY Students.StudentID
HAVING AVG(Grades.average) >= 3.5
ORDER BY avg_grade DESC
");

while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['FirstName'] . " " . $row['LastName'],
        round($row['avg_grade'], 2)
    ]);
}

fclose($output);
exit;
?>