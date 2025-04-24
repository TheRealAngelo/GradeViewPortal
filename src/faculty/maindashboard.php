<?php
session_start();
include_once '../../includes/db_connection.php';

// Check if user is Faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit();
}

// Get faculty full name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

// Count active students
$student_count_stmt = $conn->prepare("SELECT COUNT(*) AS student_count FROM users WHERE role = 'student'");
$student_count_stmt->execute();
$student_count_result = $student_count_stmt->get_result();
$student_count = $student_count_result->fetch_assoc()['student_count'];

// Count active faculty
$faculty_count_stmt = $conn->prepare("SELECT COUNT(*) AS faculty_count FROM users WHERE role = 'faculty'");
$faculty_count_stmt->execute();
$faculty_count_result = $faculty_count_stmt->get_result();
$faculty_count = $faculty_count_result->fetch_assoc()['faculty_count'];

// Count announcements
$announcement_count_stmt = $conn->prepare("SELECT COUNT(*) AS announcement_count FROM announcements");
$announcement_count_stmt->execute();
$announcement_count_result = $announcement_count_stmt->get_result();
$announcement_count = $announcement_count_result->fetch_assoc()['announcement_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: max-content; width: fit-content; font-style: bold;">
                <h2 style="width: fit-content;"><?php echo htmlspecialchars($faculty_name); ?></h2>
                <div class="dash-faculty-stats">
                    <div class="dash-faculty-card">
                        <h3>Total Enrolled Students</h3>
                        <p><?php echo $student_count; ?></p>
                    </div>
                    <div class="dash-faculty-card">
                        <h3>Total Faculty</h3>
                        <p><?php echo $faculty_count; ?></p>
                    </div>
                    <div class="dash-faculty-card">
                        <h3>Total Announcements</h3>
                        <p><?php echo $announcement_count; ?></p>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <script src="../../includes/timeout.js"></script>
</body>
</html>