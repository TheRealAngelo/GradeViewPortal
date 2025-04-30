<?php
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
include_once '../../includes/db_connection.php';

// Get POST from the form
$filter_yearlevel = isset($_POST['yearlevel']) ? intval($_POST['yearlevel']) : 0;
$filter_subject = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
$filter_school_year = isset($_POST['school_year']) ? intval($_POST['school_year']) : 0;

// SQL query with filters
$sql = "SELECT u.id AS student_id, CONCAT(u.LastName, ', ', u.FirstName) AS student_name, 
        s.subject_name, g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`, sy.year_start, sy.year_end
        FROM grades g
        JOIN users u ON g.student_id = u.id
        JOIN subject s ON g.subject_id = s.id
        JOIN school_year sy ON g.school_year_id = sy.id
        WHERE 1=1";

if ($filter_yearlevel > 0) {
    $sql .= " AND g.yearlevel_id = $filter_yearlevel";
}
if ($filter_subject > 0) {
    $sql .= " AND g.subject_id = $filter_subject";
}
if ($filter_school_year > 0) {
    $sql .= " AND g.school_year_id = $filter_school_year";
}

$sql .= " ORDER BY sy.year_start DESC, u.LastName, u.FirstName, s.subject_name";

$result = $conn->query($sql);

//--Set CSV download--//
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="grades_export.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['Student Name', 'Subject', '1st Grading', '2nd Grading', '3rd Grading', '4th Grading', 'School Year']);
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['student_name'],
        $row['subject_name'],
        $row['1stGrading'],
        $row['2ndGrading'],
        $row['3rdGrading'],
        $row['4thGrading'],
        $row['year_start'] . '-' . $row['year_end']
    ]);
}

//--End CSV download-//
fclose($output);
exit();
?>