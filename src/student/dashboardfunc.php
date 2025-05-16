<?php
// Check if the user is a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Fetch the student
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$student_name = $name_result->fetch_assoc()['full_name'];
$name_stmt->close();

// Get selected school year 
if (isset($_SESSION['school_year_id'])) {
    $school_year_id = $_SESSION['school_year_id'];
} else {
    $sy_res = $conn->query("SELECT id FROM school_year ORDER BY year_start DESC LIMIT 1");
    $school_year_id = $sy_res->fetch_assoc()['id'];
}

// Get school year
$sy_display = '';
$sy_stmt = $conn->prepare("SELECT CONCAT(year_start, '-', year_end) AS sy FROM school_year WHERE id = ?");
$sy_stmt->bind_param("i", $school_year_id);
$sy_stmt->execute();
$sy_result = $sy_stmt->get_result();
if ($sy_row = $sy_result->fetch_assoc()) {
    $sy_display = $sy_row['sy'];
}
$sy_stmt->close();
