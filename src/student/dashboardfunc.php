<?php
// Check if the user is a student
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

// Fetch the current school year
$school_year_stmt = $conn->prepare("SELECT CONCAT(year_start, ' - ', year_end) AS school_year FROM school_year ORDER BY year_start DESC LIMIT 1");
$school_year_stmt->execute();
$school_year_result = $school_year_stmt->get_result();
$schoolYear = $school_year_result->fetch_assoc()['school_year'];
?>