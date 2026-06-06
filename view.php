<?php
$conn = mysqli_connect("localhost", "root", "", "studentrecordsV1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$type = $_GET['type'] ?? 'students';
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .buttons a {
            padding: 10px 20px;
            margin: 5px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .buttons a:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #343a40;
            color: white;
        }
    </style>
</head>
<body>

<h2>View Records</h2>

<div class="buttons">
    <a href="view.php?type=students">Students</a>
    <a href="view.php?type=subjects">Subjects</a>
    <a href="view.php?type=grades">Grades</a>
</div>

<table>
<?php
/* ================= STUDENTS ================= */
if ($type === 'students') {
    echo "
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>Address</th>
        <th>Contact</th>
        <th>Course</th>
        <th>Gender</th>
        <th>Year Level</th>
    </tr>";

    $result = mysqli_query($conn, "SELECT * FROM students");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['StudentID']}</td>
            <td>{$row['FirstName']}</td>
            <td>{$row['LastName']}</td>
            <td>{$row['Age']}</td>
            <td>{$row['Address']}</td>
            <td>{$row['Contact']}</td>
            <td>{$row['Course']}</td>
            <td>{$row['Gender']}</td>
            <td>{$row['YearLevel']}</td>
        </tr>";
    }
}

/* ================= SUBJECTS ================= */
elseif ($type === 'subjects') {
    echo "
    <tr>
        <th>Subject ID</th>
        <th>Subject Name</th>
        
    </tr>";

    $result = mysqli_query($conn, "SELECT * FROM subjects");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['subject_id']}</td>
            <td>{$row['subject_name']}</td>
            
        </tr>";
    }
}

/* ================= GRADES ================= */
elseif ($type === 'grades') {
    

    $result = mysqli_query($conn, "SELECT * FROM grades");

    while ($row = mysqli_fetch_assoc($result)) {
       $sql = "SELECT Grades.grade_id, Students.FirstName, Students.LastName, Subjects.subject_name,
                       Grades.midterm, Grades.final, Grades.average, Grades.remarks
                FROM Grades
                INNER JOIN Students ON Grades.student_id = Students.StudentID
                INNER JOIN Subjects ON Grades.subject_id = Subjects.subject_id";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            echo "<h2>Grade Records</h2>";
            echo "<table><tr>
                    <th>First Name</th><th>Last Name</th><th>Subject</th>
                    <th>Midterm</th><th>Final</th><th>Average</th><th>Remarks</th>
                  </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr>
                        <td>{$row['FirstName']}</td>
                        <td>{$row['LastName']}</td>
                        <td>{$row['subject_name']}</td>
                        <td>{$row['midterm']}</td>
                        <td>{$row['final']}</td>
                        <td>{$row['average']}</td>
                        <td>{$row['remarks']}</td>
                        
                      </tr>";
            }
            echo "</table>";
        } else { echo "<p>No grades found.</p>"; }
}
}
?>
</table>

</body>
</html>
