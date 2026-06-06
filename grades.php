<?php
session_start();

$conn = new mysqli("localhost", "root", "", "studentrecordsV1");
if ($conn->connect_error) {
    die("DB Connection failed");
}

$page = $_GET['action'] ?? 'view';

/* ================= ADD ================= */
if (isset($_POST['add_grade'])) {

    $stmt = $conn->prepare("
        INSERT INTO Grades (student_id, subject_id, midterm, final, average, remarks, teacher_name)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $average = ($_POST['midterm'] + $_POST['final']) / 2;

    $stmt->bind_param(
        "iiddsss",
        $_POST['student_id'],
        $_POST['subject_id'],
        $_POST['midterm'],
        $_POST['final'],
        $average,
        $_POST['remarks'],
        $_POST['teacher_name']
    );

    $stmt->execute();
    header("Location: grades.php");
    exit();
}

/* ================= UPDATE ================= */
if (isset($_POST['update_grade'])) {

    $stmt = $conn->prepare("
        UPDATE Grades SET
        student_id=?, subject_id=?, midterm=?, final=?, average=?, remarks=?, teacher_name=?
        WHERE grade_id=?
    ");

    $average = ($_POST['midterm'] + $_POST['final']) / 2;

    $stmt->bind_param(
        "iiddsssi",
        $_POST['student_id'],
        $_POST['subject_id'],
        $_POST['midterm'],
        $_POST['final'],
        $average,
        $_POST['remarks'],
        $_POST['teacher_name'],
        $_POST['id']
    );

    $stmt->execute();
    header("Location: grades.php");
    exit();
}

/* ================= ARCHIVE ================= */
if ($page == "archive") {

    $id = $_GET['id'];

    $conn->query("INSERT INTO Grades_Archive SELECT * FROM Grades WHERE grade_id=$id");
    $conn->query("DELETE FROM Grades WHERE grade_id=$id");

    header("Location: grades.php");
    exit();
}

/* ================= EDIT DATA ================= */
$row = null;

if ($page == "edit") {
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM Grades WHERE grade_id=$id");
    $row = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Grades</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
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
}

.nav a:hover {
    background: rgba(0,0,0,0.7);
}

.container {
    width: 95%;
    margin: auto;
}

.btn {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    margin: 2px;
}

.edit-btn { background: #f39c12; }
.archive-btn { background: #e74c3c; }

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 20px;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background: #2c3e50;
    color: white;
    padding: 12px;
}

td {
    padding: 10px;
    text-align: center;
}

.card {
    background: white;
    padding: 20px;
    margin-top: 20px;
    border-radius: 12px;
}

input, select {
    padding: 10px;
    margin: 5px;
    width: 200px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    padding: 10px 15px;
    border: none;
    background: #3498db;
    color: white;
    border-radius: 6px;
}

.back-container {
    text-align: center;
    margin-top: 10px;
}

.back {
    display: inline-block;
    padding: 10px 15px;
    background: #555;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}
</style>

</head>

<body>

<div class="header">GRADE MANAGEMENT</div>

<div class="nav">
    <a href="index.php">🏠 Main Menu</a>
    <a href="grades.php?action=add">➕ Add Grade</a>
</div>

<div class="container">

<!-- ================= ADD ================= -->
<?php if ($page == "add"): ?>

<div class="back-container">
    <a class="back" href="grades.php">⬅ Back</a>
</div>

<div class="card">
<h2>Add Grade</h2>

<form method="POST">

<select name="student_id" required>
<option value="">Select Student</option>
<?php
$students = $conn->query("SELECT * FROM Students");
while ($s = $students->fetch_assoc()):
?>
<option value="<?= $s['StudentID'] ?>">
<?= $s['FirstName'] . " " . $s['LastName'] ?>
</option>
<?php endwhile; ?>
</select>

<select name="subject_id" required>
<option value="">Select Subject</option>
<?php
$subjects = $conn->query("SELECT * FROM Subjects");
while ($sub = $subjects->fetch_assoc()):
?>
<option value="<?= $sub['subject_id'] ?>">
<?= $sub['subject_name'] ?>
</option>
<?php endwhile; ?>
</select>

<input name="midterm" placeholder="Midterm" required>
<input name="final" placeholder="Final" required>
<input name="remarks" placeholder="Remarks" required>
<input name="teacher_name" placeholder="Teacher Name" required>

<br><br>
<button name="add_grade">Add Grade</button>
</form>
</div>

<!-- ================= EDIT ================= -->
<?php elseif ($page == "edit"): ?>

<div class="back-container">
    <a class="back" href="grades.php">⬅ Back</a>
</div>

<div class="card">
<h2>Edit Grade</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $row['grade_id'] ?>">

<select name="student_id">
<?php
$students = $conn->query("SELECT * FROM Students");
while ($s = $students->fetch_assoc()):
$selected = ($s['StudentID'] == $row['student_id']) ? "selected" : "";
?>
<option value="<?= $s['StudentID'] ?>" <?= $selected ?>>
<?= $s['FirstName'] . " " . $s['LastName'] ?>
</option>
<?php endwhile; ?>
</select>

<select name="subject_id">
<?php
$subjects = $conn->query("SELECT * FROM Subjects");
while ($sub = $subjects->fetch_assoc()):
$selected = ($sub['subject_id'] == $row['subject_id']) ? "selected" : "";
?>
<option value="<?= $sub['subject_id'] ?>" <?= $selected ?>>
<?= $sub['subject_name'] ?>
</option>
<?php endwhile; ?>
</select>

<input name="midterm" value="<?= $row['midterm'] ?>">
<input name="final" value="<?= $row['final'] ?>">
<input name="remarks" value="<?= $row['remarks'] ?>">
<input name="teacher_name" value="<?= $row['teacher_name'] ?>">

<br><br>
<button name="update_grade">Update</button>
</form>
</div>

<!-- ================= VIEW ================= -->
<?php else: ?>

<h2 style="text-align:center;color:white;">Grade List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Subject</th>
    <th>Teacher</th>
    <th>Midterm</th>
    <th>Final</th>
    <th>Average</th>
    <th>Remarks</th>
    <th>Actions</th>
</tr>

<?php
$res = $conn->query("
SELECT Grades.*, Students.FirstName, Subjects.subject_name
FROM Grades
JOIN Students ON Grades.student_id = Students.StudentID
JOIN Subjects ON Grades.subject_id = Subjects.subject_id
");

while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['grade_id'] ?></td>
    <td><?= $row['FirstName'] ?></td>
    <td><?= $row['subject_name'] ?></td>
    <td><?= $row['teacher_name'] ?></td>
    <td><?= $row['midterm'] ?></td>
    <td><?= $row['final'] ?></td>
    <td><?= $row['average'] ?></td>
    <td><?= $row['remarks'] ?></td>

    <td>
        <a class="btn edit-btn" href="grades.php?action=edit&id=<?= $row['grade_id'] ?>">Edit</a>
        <a class="btn archive-btn" href="grades.php?action=archive&id=<?= $row['grade_id'] ?>">Archive</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<?php endif; ?>

</div>

</body>
</html>