<?php
session_start();
$conn = new mysqli("localhost", "root", "", "studentrecordsV1");

if ($conn->connect_error) {
    die("DB Connection failed");
}

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {
    $admin_user = "admin";
    $admin_pass = "1234";

    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['logged_in'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid login!";
    }
}

/* ================= LOGOUT ================= */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

/* ================= COUNTS (FIXED OLTP ACCURACY) ================= */
$studentsCount = $conn->query("SELECT COUNT(*) AS c FROM Students")->fetch_assoc()['c'] ?? 0;
$subjectsCount = $conn->query("SELECT COUNT(*) AS c FROM Subjects")->fetch_assoc()['c'] ?? 0;
$gradesCount   = $conn->query("SELECT COUNT(*) AS c FROM Grades")->fetch_assoc()['c'] ?? 0;
$archiveCount  = $conn->query("
    SELECT 
        (SELECT COUNT(*) FROM Students_Archive) +
        (SELECT COUNT(*) FROM Subjects_Archive) +
        (SELECT COUNT(*) FROM Grades_Archive)
    AS c
")->fetch_assoc()['c'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
}

/* CENTER LOGIN */
.center {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* LOGIN BOX */
.login-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    width: 300px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.login-box input {
    width: 90%;
    padding: 10px;
    margin: 8px 0;
}

.login-box button {
    padding: 10px;
    width: 100%;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 6px;
}

/* PORTAL BUTTON */
.portal-btn {
    display: block;
    margin-bottom: 10px;
    padding: 10px;
    background: #27ae60;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

/* DASHBOARD HEADER */
.header {
    text-align: center;
    padding: 25px;
    font-size: 32px;
    color: white;
    font-weight: bold;
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

/* CARDS */
.dashboard {
    width: 90%;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    transition: 0.2s;
}

.card:hover {
    transform: scale(1.05);
}

.card h2 {
    margin: 0;
    font-size: 18px;
}

.card p {
    font-size: 28px;
    font-weight: bold;
    margin: 10px 0;
}

.card a {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

/* COLORS */
.students { border-left: 6px solid #27ae60; }
.subjects { border-left: 6px solid #f39c12; }
.grades   { border-left: 6px solid #e74c3c; }
.archive  { border-left: 6px solid #555; }

.error {
    color: red;
}
</style>
</head>

<body>

<?php if (!isset($_SESSION['logged_in'])): ?>

<div class="center">
    <div class="login-box">
        <h2>Admin Login</h2>

        <a class="portal-btn" href="view.php">Student Portal</a>

        <form method="POST">
            <input name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="login">Login</button>
        </form>

        <p class="error"><?= $error ?? "" ?></p>
    </div>
</div>

<?php else: ?>

<div class="header">📊 Student System Dashboard</div>

<div class="nav">
    <a href="students.php">Students</a>
    <a href="subjects.php">Subjects</a>
    <a href="grades.php">Grades</a>
    <a href="analytics.php">Analytics</a>
    <a href="archive.php">Archive</a>
    <a href="?logout=1">Logout</a>
</div>

<div class="dashboard">

    <div class="card students">
        <h2>Students</h2>
        <p><?= $studentsCount ?></p>
        
    </div>

    <div class="card subjects">
        <h2>Subjects</h2>
        <p><?= $subjectsCount ?></p>
        
    </div>

    <div class="card grades">
        <h2>Grades</h2>
        <p><?= $gradesCount ?></p>
        
    </div>

    <div class="card archive">
        <h2>Archive</h2>
        <p><?= $archiveCount ?></p>
        
    </div>

</div>

<?php endif; ?>

</body>
</html>