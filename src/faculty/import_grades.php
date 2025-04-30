<?php
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
include_once '../../includes/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['grades_file'])) {
    $file = $_FILES['grades_file']['tmp_name'];

    //-- excute uploaded CSV file--//
    $handle = fopen($file, 'r');
    if ($handle === false) {
        die('Error opening file.');
    }
    fgetcsv($handle);

    //--Process--//
    while (($row = fgetcsv($handle)) !== false) {
        $student_name = trim($row[0]);
        $subject_name = trim($row[1]);
        $first_grading = intval($row[2]);
        $second_grading = intval($row[3]);
        $third_grading = intval($row[4]);
        $fourth_grading = intval($row[5]);
        $school_year = trim($row[6]);

        //-- Student from database--//
        $student_stmt = $conn->prepare("SELECT id FROM users WHERE CONCAT(LastName, ', ', FirstName) = ?");
        $student_stmt->bind_param("s", $student_name);
        $student_stmt->execute();
        $student_result = $student_stmt->get_result();
        $student_id = $student_result->fetch_assoc()['id'];

        $subject_stmt = $conn->prepare("SELECT id FROM subject WHERE subject_name = ?");
        $subject_stmt->bind_param("s", $subject_name);
        $subject_stmt->execute();
        $subject_result = $subject_stmt->get_result();
        $subject_id = $subject_result->fetch_assoc()['id'];

        $school_year_parts = explode('-', $school_year);
        $school_year_stmt = $conn->prepare("SELECT id FROM school_year WHERE year_start = ? AND year_end = ?");
        $school_year_stmt->bind_param("ii", $school_year_parts[0], $school_year_parts[1]);
        $school_year_stmt->execute();
        $school_year_result = $school_year_stmt->get_result();
        $school_year_id = $school_year_result->fetch_assoc()['id'];

        //--Insert or update grades--//
        if ($student_id && $subject_id && $school_year_id) {
            $created_by = $_SESSION['user_id']; // Use the logged-in faculty's ID
            $stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, school_year_id, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading`, created_by) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE 
                                    `1stGrading` = VALUES(`1stGrading`), 
                                    `2ndGrading` = VALUES(`2ndGrading`), 
                                    `3rdGrading` = VALUES(`3rdGrading`), 
                                    `4thGrading` = VALUES(`4thGrading`), 
                                    created_by = VALUES(created_by)");
            $stmt->bind_param("iiiiiiii", $student_id, $subject_id, $school_year_id, $first_grading, $second_grading, $third_grading, $fourth_grading, $created_by);
            $stmt->execute();
        }
    }

    fclose($handle);
    echo "Grades imported successfully!";
    header("Location: dashboard.php");
    exit();
}
?>