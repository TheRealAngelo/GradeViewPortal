<?php
session_start();
include_once '../../includes/db_connection.php';

// monkey terms: student user so user in dashboard
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// full name be like "Ger, Nig" Lastname, Firstname
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
        <img src="../../assets/images/vis-mis-page.jpg" 
        style="padding: 1rem 2rem 2rem 2rem;
                width: max-content;
                height: 60rem;
                margin-left: auto;
                margin-right: auto;
                overflow: auto;
                max-width: 100%;
                max-height: 100%;
                display: block;
                margin: 0 auto;     ">
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>
</html>