// DAN AYAWG NI HILBATA
<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['students'])) {
    $selected_students = $_POST['students'];


    $new_school_year_sql = "SELECT id FROM school_year ORDER BY year_start DESC LIMIT 1";
    $new_school_year_result = $conn->query($new_school_year_sql);
    $new_school_year_id = $new_school_year_result->fetch_assoc()['id'];

    $grades_sql = "SELECT student_id, subject_id, yearlevel_id, created_by 
                   FROM grades 
                   WHERE student_id = ? AND school_year_id = (SELECT id FROM school_year WHERE year_start = 2025 AND year_end = 2026)";
    $stmt = $conn->prepare($grades_sql);

    $insert_stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading`, yearlevel_id, school_year_id, created_by) VALUES (?, ?, 0, 0, 0, 0, ?, ?, ?)");

    foreach ($selected_students as $student_id) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $subject_id = $row['subject_id'];
            $yearlevel_id = $row['yearlevel_id'];
            $created_by = $row['created_by'];

            $insert_stmt->bind_param("iiiiii", $student_id, $subject_id, $yearlevel_id, $new_school_year_id, $created_by);
            $insert_stmt->execute();
        }
    }

    $insert_stmt->close();
    $stmt->close();

    echo "Grades duplicated successfully for the selected students!";
} else {
    echo "No students selected.";
}
?>

<a href="select_students.php">Select Students for Next School Year</a>