<?php
$conn = new mysqli("localhost", "root", "", "studentrecordsV1");
if ($conn->connect_error) {
    die("DB Connection failed");
}

/* ================= RESTORE ================= */
if (isset($_GET['restore_student'])) {
    $id = $_GET['restore_student'];

    $conn->query("INSERT INTO Students 
                  SELECT * FROM Students_Archive WHERE StudentID=$id");

    $conn->query("DELETE FROM Students_Archive WHERE StudentID=$id");

    header("Location: archive.php");
    exit();
}

if (isset($_GET['restore_subject'])) {
    $id = $_GET['restore_subject'];

    $conn->query("INSERT INTO Subjects 
                  SELECT * FROM Subjects_Archive WHERE subject_id=$id");

    $conn->query("DELETE FROM Subjects_Archive WHERE subject_id=$id");

    header("Location: archive.php");
    exit();
}

if (isset($_GET['restore_grade'])) {
    $id = $_GET['restore_grade'];

    $conn->query("INSERT INTO Grades 
                  SELECT * FROM Grades_Archive WHERE grade_id=$id");

    $conn->query("DELETE FROM Grades_Archive WHERE grade_id=$id");

    header("Location: archive.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Archive</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
}

/* HEADER */
.header {
    text-align: center;
    padding: 20px;
    font-size: 30px;
    font-weight: bold;
    color: white;
    text-shadow: 2px 2px 5px black;
}

/* NAV */
.nav {
    text-align: center;
    margin-bottom: 20px;
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

/* SECTION TITLE */
h2 {
    text-align: center;
    color: white;
    text-shadow: 2px 2px 5px black;
    margin-top: 30px;
}

/* TABLE */
table {
    width: 90%;
    margin: 10px auto 30px auto;
    background: white;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

th {
    background: #2c3e50;
    color: white;
    padding: 12px;
}

td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    font-size: 13px;
}

/* RESTORE BUTTON */
.restore-btn {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    background: #27ae60;
    color: white;
    text-decoration: none;
    font-size: 13px;
    transition: 0.2s;
}

.restore-btn:hover {
    transform: scale(1.05);
    background: #1e8449;
}
</style>
</head>

<body>

<div class="header">RECYCLE BIN</div>

<div class="nav">
    <a href="index.php">🏠 Main Menu</a>
</div>

<!-- ================= STUDENTS ================= -->
<h2>Students Archive</h2>
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM Students_Archive");

while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['StudentID'] ?></td>
    <td><?= $row['FirstName'] . " " . $row['LastName'] ?></td>
    <td>
        <a class="restore-btn" href="archive.php?restore_student=<?= $row['StudentID'] ?>">
            Restore
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<!-- ================= SUBJECTS ================= -->
<h2>Subjects Archive</h2>
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM Subjects_Archive");

while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['subject_id'] ?></td>
    <td><?= $row['subject_name'] ?></td>
    <td>
        <a class="restore-btn" href="archive.php?restore_subject=<?= $row['subject_id'] ?>">
            Restore
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<!-- ================= GRADES ================= -->
<h2>Grades Archive</h2>
<table>
<tr>
    <th>Student ID</th>
    <th>Subject ID</th>
    <th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM Grades_Archive");

while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['student_id'] ?></td>
    <td><?= $row['subject_id'] ?></td>
    <td>
        <a class="restore-btn" href="archive.php?restore_grade=<?= $row['grade_id'] ?>">
            Restore
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>