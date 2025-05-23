<?php
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
session_start();
include_once '../../includes/db_connection.php';
require 'dashboardfunc.php';
//yawg hilabta ang mga code please

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grades'])) {
    foreach ($_POST['grades'] as $grade_id => $grade_data) {
        $first_grading = intval($grade_data['1stGrading']);
        $second_grading = intval($grade_data['2ndGrading']);
        $third_grading = intval($grade_data['3rdGrading']);
        $fourth_grading = intval($grade_data['4thGrading']);

        // Validate grades
        if (
            $first_grading < 0 || $first_grading > 100 ||
            $second_grading < 0 || $second_grading > 100 ||
            $third_grading < 0 || $third_grading > 100 ||
            $fourth_grading < 0 || $fourth_grading > 100
        ) {
            header("Location: dashboard.php?error=Invalid grade input. Grades must be between 0 and 100.");
            exit();
        }

        // Update the grades in the database
        $sql = "UPDATE grades 
                SET `1stGrading` = ?, `2ndGrading` = ?, `3rdGrading` = ?, `4thGrading` = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiii", $first_grading, $second_grading, $third_grading, $fourth_grading, $grade_id);
        $stmt->execute();
    }

    header("Location: dashboard.php?message=Grades updated successfully");
    exit();
} else {
    echo "Invalid request.";
}
?>