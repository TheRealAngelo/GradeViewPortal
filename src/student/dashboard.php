<?php
session_start();
include_once '../../includes/db_connection.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Fetch the student's full name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$student_name = $name_result->fetch_assoc()['full_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <h2>Your Grades</h2>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>1st Grading</th>
                    <th>2nd Grading</th>
                    <th>3rd Grading</th>
                    <th>4th Grading</th>
                </tr>
                <?php
                // Fetch the student's grades
                $sql = "SELECT s.subject_name, g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`
                        FROM grades g
                        JOIN subject s ON g.subject_id = s.id
                        WHERE g.student_id = ? AND g.school_year_id = (SELECT id FROM school_year ORDER BY year_start DESC LIMIT 1)
                        ORDER BY s.subject_name";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['1stGrading']); ?></td>
                        <td><?php echo htmlspecialchars($row['2ndGrading']); ?></td>
                        <td><?php echo htmlspecialchars($row['3rdGrading']); ?></td>
                        <td><?php echo htmlspecialchars($row['4thGrading']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>