<?php
session_start();

$conn = new mysqli("localhost", "root", "", "studentrecordsV1");
if ($conn->connect_error) {
    die("DB Connection failed");
}

/* ================= COURSE DATA ================= */
$courses = [];
$courseTotals = [];

$res = $conn->query("
SELECT Course, COUNT(*) AS total
FROM Students
GROUP BY Course
");

while ($row = $res->fetch_assoc()) {
    $courses[] = $row['Course'];
    $courseTotals[] = $row['total'];
}

/* ================= YEAR LEVEL ================= */
$years = [];
$yearTotals = [];

$res = $conn->query("
SELECT YearLevel, COUNT(*) AS total
FROM Students
GROUP BY YearLevel
");

while ($row = $res->fetch_assoc()) {
    $years[] = $row['YearLevel'];
    $yearTotals[] = $row['total'];
}

/* ================= SUBJECT AVERAGE ================= */
$subjects = [];
$subjectAvg = [];

$res = $conn->query("
SELECT Subjects.subject_name,
AVG(Grades.average) AS avg_grade
FROM Grades
JOIN Subjects ON Grades.subject_id = Subjects.subject_id
GROUP BY Subjects.subject_id
");

while ($row = $res->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
    $subjectAvg[] = round($row['avg_grade'], 2);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Analytics Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: linear-gradient(135deg, #1f4037, #99f2c8);
}

.header {
    text-align: center;
    padding: 20px;
    font-size: 28px;
    color: white;
    font-weight: bold;
    text-shadow: 2px 2px 5px black;
}

.nav {
    text-align: center;
    margin-bottom: 10px;
}

.nav a {
    display: inline-block;
    padding: 10px 15px;
    margin: 5px;
    border-radius: 20px;
    text-decoration: none;
    color: white;
    background: rgba(0,0,0,0.4);
    transition: 0.2s;
}

.nav a:hover {
    background: rgba(0,0,0,0.7);
    transform: scale(1.05);
}

.card {
    width: 85%;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #2c3e50;
    color: white;
    padding: 10px;
}

td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

h2 {
    text-align: center;
}

.actions {
    text-align: center;
    margin: 10px;
}

.export-btn {
    display: inline-block;
    padding: 10px 18px;
    background: #27ae60;
    color: white;
    border-radius: 20px;
    text-decoration: none;
    font-weight: bold;
}
.export-btn:hover {
    background: #1e8449;
}
</style>

</head>

<body>

<div class="header">OLAP ANALYTICS DASHBOARD</div>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="students.php">Students</a>
    <a href="subjects.php">Subjects</a>
    <a href="grades.php">Grades</a>
    <a href="archive.php">Archive</a>
</div>

<div class="actions">
    <a class="export-btn" href="export.php">📥 Export CSV Report</a>
</div>

<!-- ================= CHARTS ================= -->

<div class="card">
<h2>Students per Course</h2>
<canvas id="courseChart"></canvas>
</div>

<div class="card">
<h2>Students per Year Level</h2>
<canvas id="yearChart"></canvas>
</div>

<div class="card">
<h2>Subject Difficulty (Average Grade)</h2>
<canvas id="subjectChart"></canvas>
</div>

<!-- ================= TABLES ================= -->

<div class="card">
<h2>Students per Course (Slice)</h2>
<table>
<tr><th>Course</th><th>Total Students</th></tr>

<?php foreach ($courses as $i => $c): ?>
<tr>
    <td><?= $c ?></td>
    <td><?= $courseTotals[$i] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<div class="card">
<h2>Students per Year Level (Drill Down)</h2>
<table>
<tr><th>Year Level</th><th>Total Students</th></tr>

<?php foreach ($years as $i => $y): ?>
<tr>
    <td><?= $y ?></td>
    <td><?= $yearTotals[$i] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<div class="card">
<h2>Subject Difficulty Ranking</h2>
<table>
<tr><th>Subject</th><th>Average Grade</th></tr>

<?php foreach ($subjects as $i => $s): ?>
<tr>
    <td><?= $s ?></td>
    <td><?= $subjectAvg[$i] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- ================= TOge STUDENTS ================= -->
<div class="card">
<h2>Average  🟢 (1.0 - 2.0)</h2>

<table>
<tr><th>Student</th><th>Average Grade</th></tr>

<?php
$res = $conn->query("
SELECT Students.FirstName,
ROUND(AVG(Grades.average), 2) AS avg_grade
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
GROUP BY Students.StudentID
HAVING AVG(Grades.average) <= 2.0
ORDER BY avg_grade ASC
");

if ($res->num_rows > 0):
    while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['FirstName'] ?></td>
    <td><?= $row['avg_grade'] ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="2">No top students yet</td></tr>
<?php endif; ?>

</table>
</div>



<div class="card">
<h2>Average 🟢 (2.1 - 2.9)</h2>

<table>
<tr><th>Student</th><th>Average Grade</th></tr>

<?php
$res = $conn->query("
SELECT Students.FirstName,
ROUND(AVG(Grades.average), 2) AS avg_grade
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
GROUP BY Students.StudentID
HAVING AVG(Grades.average) > 2.0
AND AVG(Grades.average) < 3.0
ORDER BY avg_grade ASC
");

if ($res->num_rows > 0):
    while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['FirstName'] ?></td>
    <td><?= $row['avg_grade'] ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="2">No Average students yet</td></tr>
<?php endif; ?>

</table>
</div>

<!-- ================= AT-RISK STUDENTS ================= -->
<div class="card">
<h2>At-Risk Students 🔴 (3.5 - 5.0)</h2>

<table>
<tr><th>Student</th><th>Average Grade</th></tr>

<?php
$res = $conn->query("
SELECT Students.FirstName,
ROUND(AVG(Grades.average), 2) AS avg_grade
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
GROUP BY Students.StudentID
HAVING AVG(Grades.average) >= 3.5
ORDER BY avg_grade DESC
");

if ($res->num_rows > 0):
    while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['FirstName'] ?></td>
    <td><?= $row['avg_grade'] ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="2">No at-risk students 🎉</td></tr>
<?php endif; ?>

</table>
</div>

<!-- ================= CHART JS ================= -->
<script>
new Chart(document.getElementById('courseChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($courses) ?>,
        datasets: [{
            label: 'Students',
            data: <?= json_encode($courseTotals) ?>,
            backgroundColor: '#3498db'
        }]
    }
});

new Chart(document.getElementById('yearChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($years) ?>,
        datasets: [{
            label: 'Students',
            data: <?= json_encode($yearTotals) ?>,
            backgroundColor: '#e67e22'
        }]
    }
});

new Chart(document.getElementById('subjectChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($subjects) ?>,
        datasets: [{
            label: 'Average Grade',
            data: <?= json_encode($subjectAvg) ?>,
            backgroundColor: '#2ecc71'
        }]
    }
});
</script>

</body>
</html>