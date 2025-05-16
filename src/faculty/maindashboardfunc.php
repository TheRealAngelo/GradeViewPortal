<?php
// Check if user is Faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login/login.php");
    exit();
}

// Get selected school year
if (isset($_SESSION['school_year_id'])) {
    $school_year_id = $_SESSION['school_year_id'];
} else {
    $sy_res = $conn->query("SELECT id FROM school_year ORDER BY year_start DESC LIMIT 1");
    $school_year_id = $sy_res->fetch_assoc()['id'];
}
$school_year = '';
$sy_stmt = $conn->prepare("SELECT CONCAT(year_start, '-', year_end) AS sy FROM school_year WHERE id = ?");
$sy_stmt->bind_param("i", $school_year_id);
$sy_stmt->execute();
$sy_result = $sy_stmt->get_result();
if ($sy_row = $sy_result->fetch_assoc()) {
    $school_year = $sy_row['sy'];
}
$sy_stmt->close();

// Get faculty 
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];
$name_stmt->close();

// Count enrolled students 
$student_count_stmt = $conn->prepare("
    SELECT COUNT(DISTINCT g.student_id) AS student_count
    FROM grades g
    WHERE g.school_year_id = ?
");
$student_count_stmt->bind_param("i", $school_year_id);
$student_count_stmt->execute();
$student_count_result = $student_count_stmt->get_result();
$student_count = $student_count_result->fetch_assoc()['student_count'] ?? 0;
$student_count_stmt->close();

// Count active faculty
$faculty_count_stmt = $conn->prepare("SELECT COUNT(*) AS faculty_count FROM users WHERE role = 'faculty'");
$faculty_count_stmt->execute();
$faculty_count_result = $faculty_count_stmt->get_result();
$faculty_count = $faculty_count_result->fetch_assoc()['faculty_count'] ?? 0;
$faculty_count_stmt->close();

// Count announcements 
$announcement_count_stmt = $conn->prepare("SELECT COUNT(*) AS announcement_count FROM announcements");
$announcement_count_stmt->execute();
$announcement_count_result = $announcement_count_stmt->get_result();
$announcement_count = $announcement_count_result->fetch_assoc()['announcement_count'] ?? 0;
$announcement_count_stmt->close();
?>