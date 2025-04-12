<?php
session_start();
include_once '../../includes/db_connection.php'; // Corrected path
include_once '../../includes/header.php'; // Corrected path

// Check if the user is logged in and is a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Fetch the logged-in student's grades
$stmt = $conn->prepare("SELECT subject, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading` FROM grades WHERE student_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

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
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($student_name); ?>!</h1>
    <h2>Your Grades</h2>
    <table border="1">
        <tr>
            <th>Subject</th>
            <th>1st Grading</th>
            <th>2nd Grading</th>
            <th>3rd Grading</th>
            <th>4th Grading</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['1stGrading']); ?></td>
                <td><?php echo htmlspecialchars($row['2ndGrading']); ?></td>
                <td><?php echo htmlspecialchars($row['3rdGrading']); ?></td>
                <td><?php echo htmlspecialchars($row['4thGrading']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>