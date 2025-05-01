<?php
// Fetch current school year
$current_school_year_sql = "SELECT id, year_start, year_end FROM school_year WHERE year_start = 2025 AND year_end = 2026"; // Update dynamically as needed
$current_school_year_result = $conn->query($current_school_year_sql);
$current_school_year = $current_school_year_result->fetch_assoc();
$current_school_year_id = $current_school_year['id'];

// Fetch unenrolled students
$unenrolled_students_sql = "SELECT u.id, CONCAT(u.LastName, ', ', u.FirstName) AS student_name
FROM users u
LEFT JOIN grades g ON u.id = g.student_id AND g.school_year_id = ?
WHERE u.role = 'student' AND g.id IS NULL";
$unenrolled_students_stmt = $conn->prepare($unenrolled_students_sql);
$unenrolled_students_stmt->bind_param("i", $current_school_year_id);
$unenrolled_students_stmt->execute();
$unenrolled_students_result = $unenrolled_students_stmt->get_result();

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll_students'])) {
    $selected_students = $_POST['students'] ?? [];
    $yearlevel_id = intval($_POST['yearlevel_id']);

    if (!empty($selected_students)) {
        $subject_sql = "SELECT id FROM subject WHERE yearlevel_id = ?";
        $subject_stmt = $conn->prepare($subject_sql);
        $subject_stmt->bind_param("i", $yearlevel_id);
        $subject_stmt->execute();
        $subject_result = $subject_stmt->get_result();

        $grade_stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading`, yearlevel_id, school_year_id, created_by) VALUES (?, ?, 0, 0, 0, 0, ?, ?, ?)");

        foreach ($selected_students as $student_id) {
            // check student enrollment
            $check_grade_sql = "SELECT COUNT(*) AS grade_count FROM grades WHERE student_id = ? AND school_year_id = ?";
            $check_grade_stmt = $conn->prepare($check_grade_sql);
            $check_grade_stmt->bind_param("ii", $student_id, $current_school_year_id);
            $check_grade_stmt->execute();
            $check_grade_result = $check_grade_stmt->get_result();
            $grade_count = $check_grade_result->fetch_assoc()['grade_count'];

            if ($grade_count > 0) {
                continue;
            }

            // Insert grades for the student
            while ($subject_row = $subject_result->fetch_assoc()) {
                $subject_id = $subject_row['id'];
                $grade_stmt->bind_param("iiiii", $student_id, $subject_id, $yearlevel_id, $current_school_year_id, $student_id);
                $grade_stmt->execute();
            }
            $subject_result->data_seek(0); // Reset the result pointer for the next student
        }

        $grade_stmt->close();
        $subject_stmt->close();
        echo "<script>alert('Selected students have been successfully enrolled and grades have been created!');</script>";
        } else {
        echo "<script>alert('Please select at least one student to enroll.');</script>";

        //ito rin u can also use inpage/in-div message
        // $message = "Selected students have been successfully enrolled and grades have been created!"; 
        //} else {
        //$message = "Please select at least one student to enroll.";//

    }
}
?>