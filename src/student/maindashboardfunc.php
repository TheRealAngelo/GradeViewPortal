<?php
// Check user student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login/login.php");
    exit();
}

// student full name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$student_name = $name_result->fetch_assoc()['full_name'];

// school year
$current_sy_stmt = $conn->prepare("
    SELECT CONCAT(year_start, '-', year_end) AS school_year
    FROM school_year
    ORDER BY year_start DESC
    LIMIT 1
");
$current_sy_stmt->execute();
$current_sy_result = $current_sy_stmt->get_result();
$current_school_year = $current_sy_result->fetch_assoc()['school_year'] ?? 'No School Year Available';

// student year level for the current school year
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

$schoolYear = $current_school_year; // Always display the current school year
$yearLevel = $details['year_level'] ?? "Student Not Enrolled";

// if the student is enrolled
if ($yearLevel === "Student Not Enrolled") {
    $grades_status = "Student Not Enrolled";
    $top_subjects = ["Student Not Enrolled"];
    $lowest_subjects = ["Student Not Enrolled"];
} else {
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

    // grades view process
    if ($in_process_count > 0) {
        $grades_status = "In Process: $in_process_count";
    } else {
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
        if ($average == 0) {
            continue;
        }
        if ($average > $highest_avg) {
            $highest_avg = $average;
            $top_subjects = [$row['subject_name']];
        } elseif ($average == $highest_avg) {
            $top_subjects[] = $row['subject_name'];
        }
        if ($average < $lowest_avg) {
            $lowest_avg = $average;
            $lowest_subjects = [$row['subject_name']];
        } elseif ($average == $lowest_avg) {
            $lowest_subjects[] = $row['subject_name'];
        }
    }
    if (!empty(array_intersect($top_subjects, $lowest_subjects))) {
        $lowest_subjects = [];
    }
}
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
