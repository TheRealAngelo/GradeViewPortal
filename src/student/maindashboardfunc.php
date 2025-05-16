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
$name_stmt->close();

// Get selected school year from session or fallback to latest
if (isset($_SESSION['school_year_id'])) {
    $school_year_id = $_SESSION['school_year_id'];
} else {
    $sy_res = $conn->query("SELECT id FROM school_year ORDER BY year_start DESC LIMIT 1");
    $school_year_id = $sy_res->fetch_assoc()['id'];
}

// Get school year display string
$schoolYear = '';
$sy_stmt = $conn->prepare("SELECT CONCAT(year_start, '-', year_end) AS sy FROM school_year WHERE id = ?");
$sy_stmt->bind_param("i", $school_year_id);
$sy_stmt->execute();
$sy_result = $sy_stmt->get_result();
if ($sy_row = $sy_result->fetch_assoc()) {
    $schoolYear = $sy_row['sy'];
}
$sy_stmt->close();

// student year level for the selected school year
$details_stmt = $conn->prepare("
    SELECT yl.level_name AS year_level
    FROM grades g
    INNER JOIN yearlevel yl ON g.yearlevel_id = yl.id
    WHERE g.student_id = ? AND g.school_year_id = ?
    LIMIT 1
");
$details_stmt->bind_param("ii", $_SESSION['user_id'], $school_year_id);
$details_stmt->execute();
$details_result = $details_stmt->get_result();
$details = $details_result->fetch_assoc();
$yearLevel = $details['year_level'] ?? "Student Not Enrolled";
$details_stmt->close();

// if the student is enrolled
if ($yearLevel === "Student Not Enrolled") {
    $grades_status = "Student Not Enrolled";
    $top_subjects = ["Student Not Enrolled"];
    $lowest_subjects = ["Student Not Enrolled"];
} else {
    // Check for grades in process for the selected school year
    $in_process_stmt = $conn->prepare("
        SELECT COUNT(*) AS in_process_count
        FROM grades
        WHERE student_id = ? AND school_year_id = ? AND (
            `1stGrading` = 0 OR 
            `2ndGrading` = 0 OR 
            `3rdGrading` = 0 OR 
            `4thGrading` = 0
        )
    ");
    $in_process_stmt->bind_param("ii", $_SESSION['user_id'], $school_year_id);
    $in_process_stmt->execute();
    $in_process_result = $in_process_stmt->get_result();
    $in_process_count = $in_process_result->fetch_assoc()['in_process_count'];
    $in_process_stmt->close();

    // grades view process
    if ($in_process_count > 0) {
        $grades_status = "In Process: $in_process_count";
    } else {
        $grades_stmt = $conn->prepare("
            SELECT g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`
            FROM grades g
            WHERE g.student_id = ? AND g.school_year_id = ?
        ");
        $grades_stmt->bind_param("ii", $_SESSION['user_id'], $school_year_id);
        $grades_stmt->execute();
        $grades_result = $grades_stmt->get_result();

        $failed_count = 0;
        while ($row = $grades_result->fetch_assoc()) {
            $average = ($row['1stGrading'] + $row['2ndGrading'] + $row['3rdGrading'] + $row['4thGrading']) / 4;
            if ($average < 75) {
                $failed_count++;
            }
        }
        $grades_stmt->close();

        if ($failed_count > 0) {
            $grades_status = "Failed: $failed_count";
        } else {
            $grades_status = "PASSED";
        }
    }

    // Top and lowest performing subjects for the selected school year
    $grades_stmt = $conn->prepare("
        SELECT s.subject_name, g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`
        FROM grades g
        INNER JOIN subject s ON g.subject_id = s.id
        WHERE g.student_id = ? AND g.school_year_id = ?
    ");
    $grades_stmt->bind_param("ii", $_SESSION['user_id'], $school_year_id);
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
    $grades_stmt->close();

    if (!empty(array_intersect($top_subjects, $lowest_subjects))) {
        $lowest_subjects = [];
    }
}

// Latest announcement
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
$announcement_stmt->close();

$announcement_title = $latest_announcement['title'] ?? 'No announcements available';
$announcement_creator = $latest_announcement['created_by'] ?? 'N/A';
?>
