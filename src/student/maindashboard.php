<?php
session_start();
include_once '../../includes/db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$student_name = $name_result->fetch_assoc()['full_name'];

// School year and year level 
$details_stmt = $conn->prepare("
    SELECT 
        CONCAT(sy.year_start, '-', sy.year_end) AS school_year,
        yl.level_name AS year_level
    FROM grades g
    INNER JOIN school_year sy ON g.school_year_id = sy.id
    INNER JOIN yearlevel yl ON g.yearlevel_id = yl.id
    WHERE g.student_id = ?
    LIMIT 1
");
$details_stmt->bind_param("i", $_SESSION['user_id']);
$details_stmt->execute();
$details_result = $details_stmt->get_result();
$details = $details_result->fetch_assoc();

$schoolYear = $details['school_year'];
$yearLevel = $details['year_level'];
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
    <div class="container ">
        <?php include 'sidebar.php'; ?> 

        <div class="content">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <h1 style="width: fit-content;"><?php echo htmlspecialchars($student_name); ?></h1>
                <h3 style="width: fit-content">School Year: <?php echo htmlspecialchars($schoolYear); ?></h3>
                <small style="width: fit-content; margin-bottom: 1rem">Year Level: <?php echo htmlspecialchars($yearLevel); ?></small>
            </div>

            <div style="width: 100%;">
                <div class="dash-student-card">
                    <div>
                        <h2>All Students</h2>
                        <h2>Future Functions</h2>
                    </div>
                </div>
                <div class="dash-student-card">
                    <div>
                        <h2>All Students</h2>
                        <h2>Future Functions</h2>
                    </div>
                </div>
                <div class="student-info-box">
                    <p>For any concerns regarding your assessment, please visit the Cashier Office or contact them at 0999-999-9999.</p>
                </div>
            </div>
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>
</html>