<?php
// Check if user is Faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch the most recent school year
$school_year_stmt = $conn->prepare("SELECT CONCAT(year_start, '-', year_end) AS school_year FROM school_year ORDER BY year_start DESC LIMIT 1");
$school_year_stmt->execute();
$school_year_result = $school_year_stmt->get_result();
$school_year = $school_year_result->fetch_assoc()['school_year'];

// Get faculty full name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

// Count active students
$student_count_stmt = $conn->prepare("SELECT YEAR(g.created_at) AS year, COUNT(DISTINCT u.id) AS student_count FROM users u INNER JOIN grades g ON u.id = g.student_id WHERE u.role = 'student' GROUP BY YEAR(g.created_at) ORDER BY YEAR(g.created_at)");
if (!$student_count_stmt) {
    die("Error preparing student count query: " . $conn->error);
}
$student_count_stmt->execute();
$student_count_result = $student_count_stmt->get_result();
$student_count = $student_count_result->fetch_assoc()['student_count'] ?? 0;

// Count active faculty
$faculty_count_stmt = $conn->prepare("SELECT COUNT(*) AS faculty_count FROM users WHERE role = 'faculty'");
if (!$faculty_count_stmt) {
    die("Error preparing faculty count query: " . $conn->error);
}
$faculty_count_stmt->execute();
$faculty_count_result = $faculty_count_stmt->get_result();
$faculty_count = $faculty_count_result->fetch_assoc()['faculty_count'] ?? 0;

// Count announcements
$announcement_count_stmt = $conn->prepare("SELECT COUNT(*) AS announcement_count FROM announcements");
if (!$announcement_count_stmt) {
    die("Error preparing announcement count query: " . $conn->error);
}
$announcement_count_stmt->execute();
$announcement_count_result = $announcement_count_stmt->get_result();
$announcement_count = $announcement_count_result->fetch_assoc()['announcement_count'] ?? 0;
?>