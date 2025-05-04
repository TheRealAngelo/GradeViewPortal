<?php
session_start();
include_once '../../includes/db_connection.php';

// Check if the user is a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch the student's full name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$student_name = $name_result->fetch_assoc()['full_name'];

// Fetch the current school year and year level
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

// Count grades that are still "in process" (any grading column is 0)
$in_process_stmt = $conn->prepare("
    SELECT COUNT(*) AS in_process_count
    FROM grades
    WHERE student_id = ? AND (
        `1stGrading` = 0 OR 
        `2ndGrading` = 0 OR 
        `3rdGrading` = 0 OR 
        `4thGrading` = 0
    )
");
$in_process_stmt->bind_param("i", $_SESSION['user_id']);
$in_process_stmt->execute();
$in_process_result = $in_process_stmt->get_result();
$in_process_count = $in_process_result->fetch_assoc()['in_process_count'];

// Check if there are grades still in process
if ($in_process_count > 0) {
    $grades_status = "In Process: $in_process_count";
} else {
    // Calculate the number of failed grades
    $grades_stmt = $conn->prepare("
        SELECT g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`
        FROM grades g
        WHERE g.student_id = ?
    ");
    $grades_stmt->bind_param("i", $_SESSION['user_id']);
    $grades_stmt->execute();
    $grades_result = $grades_stmt->get_result();

    $failed_count = 0;

    while ($row = $grades_result->fetch_assoc()) {
        $average = ($row['1stGrading'] + $row['2ndGrading'] + $row['3rdGrading'] + $row['4thGrading']) / 4;

        if ($average < 75) {
            $failed_count++;
        }
    }

    if ($failed_count > 0) {
        $grades_status = "Failed: $failed_count";
    } else {
        $grades_status = "PASSED";
    }
}

// Fetch grades and calculate averages
$grades_stmt = $conn->prepare("
    SELECT s.subject_name, g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`
    FROM grades g
    INNER JOIN subject s ON g.subject_id = s.id
    WHERE g.student_id = ?
");
$grades_stmt->bind_param("i", $_SESSION['user_id']);
$grades_stmt->execute();
$grades_result = $grades_stmt->get_result();

$top_subjects = [];
$lowest_subjects = [];
$highest_avg = -1;
$lowest_avg = 101;

while ($row = $grades_result->fetch_assoc()) {
    $average = ($row['1stGrading'] + $row['2ndGrading'] + $row['3rdGrading'] + $row['4thGrading']) / 4;

    // Check for top-performing subjects
    if ($average > $highest_avg) {
        $highest_avg = $average;
        $top_subjects = [$row['subject_name']]; // Reset and add the new top subject
    } elseif ($average == $highest_avg) {
        $top_subjects[] = $row['subject_name']; // Add to the list of top subjects
    }

    // Check for lowest-performing subjects
    if ($average < $lowest_avg) {
        $lowest_avg = $average;
        $lowest_subjects = [$row['subject_name']]; // Reset and add the new lowest subject
    } elseif ($average == $lowest_avg) {
        $lowest_subjects[] = $row['subject_name']; // Add to the list of lowest subjects
    }
}

// Fetch the latest announcement and its creator
$announcement_stmt = $conn->prepare("
    SELECT a.title, CONCAT(u.FirstName, ' ', u.LastName) AS created_by
    FROM announcements a
    INNER JOIN users u ON a.created_by = u.id
    ORDER BY a.created_at DESC
    LIMIT 1
");
$announcement_stmt->execute();
$announcement_result = $announcement_stmt->get_result();
$latest_announcement = $announcement_result->fetch_assoc();

$announcement_title = $latest_announcement['title'] ?? 'No announcements available';
$announcement_creator = $latest_announcement['created_by'] ?? 'N/A';
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
            <div style="display: flex; flex-direction: column; align-items: center;">
                <h1 style="width: fit-content;"><?php echo htmlspecialchars($student_name); ?></h1>
                <h3 style="width: fit-content">School Year: <?php echo htmlspecialchars($schoolYear); ?></h3>
                <small style="width: fit-content; margin-bottom: 1rem">Year Level: <?php echo htmlspecialchars($yearLevel); ?></small>
            </div>

            <div style="width: 100%;">
                <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                    <div class="dash-student-card">
                        <div>
                            <h2>Grade Status</h2>
                            <h2><?php echo htmlspecialchars($grades_status); ?></h2>
                        </div>
                    </div>
                </a>
                <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                    <div class="dash-student-card">
                        <div>
                            <h2>Top Performing</h2>
                            <h2><?php echo htmlspecialchars(implode(', ', $top_subjects)); ?></h2>
                        </div>
                    </div>
                </a>
                <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                    <div class="dash-student-card">
                        <div>
                            <h2>Lowest Performing</h2>
                            <h2><?php echo htmlspecialchars(implode(', ', $lowest_subjects)); ?></h2>
                        </div>
                    </div>
                </a>
                <a href="announcements.php" style="text-decoration: none; color: inherit;">
                    <div class="dash-student-card">
                        <div>
                            <h2>Latest Announcement</h2>
                            <h2><?php echo '" ' . htmlspecialchars($announcement_title) . ' " ' . ' Created by: ' . htmlspecialchars($announcement_creator); ?></h2>
                        </div>
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