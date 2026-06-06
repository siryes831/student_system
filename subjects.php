<?php
session_start();

$conn = new mysqli("localhost", "root", "", "studentrecordsV1");
if ($conn->connect_error) {
    die("DB Connection failed");
}

$page = $_GET['action'] ?? 'view';

/* ================= ADD ================= */
if (isset($_POST['add_subject'])) {

    $stmt = $conn->prepare("
        INSERT INTO Subjects (subject_name)
        VALUES (?)
    ");

    $stmt->bind_param("s", $_POST['SubjectName']);
    $stmt->execute();

    header("Location: subjects.php");
    exit();
}

/* ================= UPDATE ================= */
if (isset($_POST['update_subject'])) {

    $stmt = $conn->prepare("
        UPDATE Subjects 
        SET subject_name=?
        WHERE subject_id=?
    ");

    $stmt->bind_param(
        "si",
        $_POST['SubjectName'],
        $_POST['id']
    );

    $stmt->execute();

    header("Location: subjects.php");
    exit();
}

/* ================= ARCHIVE ================= */
if ($page == "archive") {

    $id = $_GET['id'];

    $conn->query("INSERT INTO Subjects_Archive 
                  SELECT * FROM Subjects WHERE subject_id=$id");

    $conn->query("DELETE FROM Subjects WHERE subject_id=$id");

    header("Location: subjects.php");
    exit();
}

/* ================= EDIT DATA ================= */
$row = null;

if ($page == "edit") {
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM Subjects WHERE subject_id=$id");
    $row = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Subjects</title>

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
    font-size: 28px;
    color: white;
    font-weight: bold;
    text-shadow: 2px 2px 5px black;
}

/* NAV */
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

/* container */
.container {
    width: 95%;
    margin: auto;
}

/* buttons */
.btn {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    margin: 2px;
    display: inline-block;
    font-size: 13px;
}

.btn:hover {
    transform: scale(1.05);
}

.add-btn { background: #27ae60; }
.edit-btn { background: #f39c12; }
.archive-btn { background: #e74c3c; }

/* table */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 20px;
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

/* card */
.card {
    background: white;
    padding: 20px;
    margin-top: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 60%;
    margin-left: auto;
    margin-right: auto;
}

/* inputs */
input {
    padding: 10px;
    margin: 5px;
    width: 90%;
    border: 1px solid #ccc;
    border-radius: 6px;
}

/* button */
button {
    padding: 10px 15px;
    border: none;
    background: #3498db;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #2980b9;
}

/* back */
.back {
    display: inline-block;
    margin: 10px 0;
    padding: 10px 15px;
    background: #555;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}
.back:hover {
    background: #333;
}
</style>
</head>

<body>

<div class="header">SUBJECT MANAGEMENT</div>

<div class="nav">
    <a href="index.php">🏠 Back to Main Menu</a>
    <a href="subjects.php?action=add">➕ Add Subject</a>
</div>

<div class="container">

<?php if ($page == "add"): ?>

<a class="back" href="subjects.php">⬅ Back</a>

<div class="card">
<h2>Add Subject</h2>

<form method="POST">
<input name="SubjectName" placeholder="Subject Name" required>
<br><br>
<button name="add_subject">Add Subject</button>
</form>
</div>

<?php elseif ($page == "edit"): ?>

<a class="back" href="subjects.php">⬅ Back</a>

<div class="card">
<h2>Edit Subject</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $row['subject_id'] ?>">
<input name="SubjectName" value="<?= $row['subject_name'] ?>">

<br><br>
<button name="update_subject">Update</button>
</form>
</div>

<?php else: ?>

<h2 style="text-align:center;color:white;">Subject List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Subject Name</th>
    <th>Actions</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM Subjects");

while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['subject_id'] ?></td>
    <td><?= $row['subject_name'] ?></td>

    <td>
        <a class="btn edit-btn" href="subjects.php?action=edit&id=<?= $row['subject_id'] ?>">Edit</a>
        <a class="btn archive-btn" href="subjects.php?action=archive&id=<?= $row['subject_id'] ?>">Archive</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<?php endif; ?>

</div>

</body>
</html>