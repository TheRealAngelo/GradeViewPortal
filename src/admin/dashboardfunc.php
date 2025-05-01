<?php
// count STUDENTS
$query = "SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_students = $row['total_students'];



// school year
$current_school_year_query = "SELECT id FROM school_year WHERE year_start <= YEAR(CURDATE()) AND year_end >= YEAR(CURDATE()) LIMIT 1";
$current_school_year_result = mysqli_query($conn, $current_school_year_query);
$current_school_year_row = mysqli_fetch_assoc($current_school_year_result);
$current_school_year_id = $current_school_year_row['id'] ?? 0;

// Count ENROLLED STUDENTS
$query = "
                            SELECT COUNT(DISTINCT g.student_id) AS total_students_with_grades
                            FROM grades g
                            INNER JOIN users u ON g.student_id = u.id
                            WHERE u.role = 'student' AND g.school_year_id = $current_school_year_id
                        ";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_students_with_grades = $row['total_students_with_grades'];


// Count FACULTY
$query = "SELECT COUNT(*) AS total_faculty FROM users WHERE role = 'faculty'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_faculty = $row['total_faculty'];
?>