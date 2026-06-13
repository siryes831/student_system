<?php
session_start();

$conn = new mysqli("localhost", "root", "", "studentrecordsV1");
if ($conn->connect_error) {
    die("DB Connection failed");
}

$page = $_GET['action'] ?? 'view';

/* ================= ADD ================= */
if (isset($_POST['add_student'])) {

    $first = $_POST['FirstName'];
    $last = $_POST['LastName'];

    // 🔒 VALIDATION: no numbers/symbols in names
    if (!preg_match("/^[a-zA-Z\s]+$/", $first) || !preg_match("/^[a-zA-Z\s]+$/", $last)) {
        echo "<script>alert('Names must only contain letters!'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("
        INSERT INTO Students 
        (FirstName, LastName, Age, Address, Contact, Course, Gender, YearLevel)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssisssss",
        $first,
        $last,
        $_POST['Age'],
        $_POST['Address'],
        $_POST['Contact'],
        $_POST['Course'],
        $_POST['Gender'],
        $_POST['YearLevel']
    );

    $stmt->execute();
    header("Location: students.php");
    exit();
}

/* ================= UPDATE ================= */
if (isset($_POST['update_student'])) {

    $first = $_POST['FirstName'];
    $last = $_POST['LastName'];

    // 🔒 VALIDATION AGAIN
    if (!preg_match("/^[a-zA-Z\s]+$/", $first) || !preg_match("/^[a-zA-Z\s]+$/", $last)) {
        echo "<script>alert('Names must only contain letters!'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("
        UPDATE Students SET
        FirstName=?, LastName=?, Age=?, Address=?, Contact=?, Course=?, Gender=?, YearLevel=?
        WHERE StudentID=?
    ");

    $stmt->bind_param(
        "ssisssssi",
        $first,
        $last,
        $_POST['Age'],
        $_POST['Address'],
        $_POST['Contact'],
        $_POST['Course'],
        $_POST['Gender'],
        $_POST['YearLevel'],
        $_POST['id']
    );

    $stmt->execute();
    header("Location: students.php");
    exit();
}

/* ================= ARCHIVE ================= */
if ($page == "archive") {

    $id = $_GET['id'];

    $conn->query("INSERT INTO Students_Archive SELECT * FROM Students WHERE StudentID=$id");
    $conn->query("DELETE FROM Students WHERE StudentID=$id");

    header("Location: students.php");
    exit();
}

/* ================= EDIT ================= */
$row = null;

if ($page == "edit") {
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM Students WHERE StudentID=$id");
    $row = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Students</title>

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
    transition: 0.2s;
}

.nav a:hover {
    background: rgba(0,0,0,0.7);
    transform: scale(1.05);
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
    display: inline-block;
    font-size: 13px;
}

.add-btn { background: #27ae60; }
.edit-btn { background: #f39c12; }
.archive-btn { background: #e74c3c; }

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

.card {
    background: white;
    padding: 20px;
    margin-top: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

input {
    padding: 10px;
    margin: 5px;
    width: 180px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

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

.back {
    display: inline-block;
    margin: 10px 0;
    padding: 10px 15px;
    background: #555;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="header">STUDENT MANAGEMENT</div>

<div class="nav">
    <a href="index.php">🏠 Back to Main Menu</a>
    <a href="students.php?action=add">➕ Add Student</a>
</div>

<div class="container">

<?php if ($page == "add"): ?>

<a class="back" href="students.php">⬅ Back</a>

<div class="card">
<h2>Add Student</h2>

<form method="POST">
<input name="FirstName" placeholder="First Name" required pattern="[A-Za-z\s]+">
<input name="LastName" placeholder="Last Name" required pattern="[A-Za-z\s]+">
<input name="Age" placeholder="Age" required>
<input name="Address" placeholder="Address" required>
<input name="Contact" placeholder="Contact" required>
<input name="Course" placeholder="Course" required>
<input name="Gender" placeholder="Gender" required>
<input name="YearLevel" placeholder="Year Level" required>

<br><br>
<button name="add_student">Add Student</button>
</form>
</div>

<?php elseif ($page == "edit"): ?>

<a class="back" href="students.php">⬅ Back</a>

<div class="card">
<h2>Edit Student</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $row['StudentID'] ?>">

<input name="FirstName" value="<?= $row['FirstName'] ?>" pattern="[A-Za-z\s]+">
<input name="LastName" value="<?= $row['LastName'] ?>" pattern="[A-Za-z\s]+">
<input name="Age" value="<?= $row['Age'] ?>">
<input name="Address" value="<?= $row['Address'] ?>">
<input name="Contact" value="<?= $row['Contact'] ?>">
<input name="Course" value="<?= $row['Course'] ?>">
<input name="Gender" value="<?= $row['Gender'] ?>">
<input name="YearLevel" value="<?= $row['YearLevel'] ?>">

<br><br>
<button name="update_student">Update</button>
</form>
</div>

<?php else: ?>

<h2 style="text-align:center;color:white;">Student List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>Age</th>
    <th>Address</th>
    <th>Contact</th>
    <th>Course</th>
    <th>Gender</th>
    <th>Year Level</th>
    <th>Actions</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM Students");

while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $row['StudentID'] ?></td>
    <td><?= $row['FirstName'] . " " . $row['LastName'] ?></td>
    <td><?= $row['Age'] ?></td>
    <td><?= $row['Address'] ?></td>
    <td><?= $row['Contact'] ?></td>
    <td><?= $row['Course'] ?></td>
    <td><?= $row['Gender'] ?></td>
    <td><?= $row['YearLevel'] ?></td>

    <td>
        <a class="btn edit-btn" href="students.php?action=edit&id=<?= $row['StudentID'] ?>">Edit</a>
        <a class="btn archive-btn" href="students.php?action=archive&id=<?= $row['StudentID'] ?>">Archive</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<?php endif; ?>

</div>

</body>
</html>