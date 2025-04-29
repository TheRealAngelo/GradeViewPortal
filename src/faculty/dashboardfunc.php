<?php
// Check if user is Faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit();
}

// Get faculty name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

// Filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_yearlevel = isset($_GET['yearlevel']) ? intval($_GET['yearlevel']) : 0;
$filter_subject = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$filter_school_year = isset($_GET['school_year']) ? intval($_GET['school_year']) : 0;

// Fetch subjects dynamically based on year level
$subjects = [];
if ($filter_yearlevel > 0) {
    // Fetch subjects for the selected year level
    $subject_stmt = $conn->prepare("SELECT id, subject_name FROM subject WHERE yearlevel_id = ?");
    $subject_stmt->bind_param("i", $filter_yearlevel);
    $subject_stmt->execute();
    $subject_result = $subject_stmt->get_result();
    while ($row = $subject_result->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    // Fetch all subjects if "All Year Levels" is selected
    $subject_result = $conn->query("SELECT id, subject_name FROM subject");
    while ($row = $subject_result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Handle AJAX request for subjects
if (isset($_GET['ajax']) && $_GET['ajax'] === 'subjects' && isset($_GET['yearlevel'])) {
    $yearlevel = intval($_GET['yearlevel']);
    $subjects = [];

    if ($yearlevel > 0) {
        // Fetch subjects for the selected year level
        $stmt = $conn->prepare("SELECT id, subject_name FROM subject WHERE yearlevel_id = ?");
        $stmt->bind_param("i", $yearlevel);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    } else {
        // Fetch all subjects if "All Year Levels" is selected
        $result = $conn->query("SELECT id, subject_name FROM subject");
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    }

    echo json_encode($subjects);
    exit();
}

// SQL query for fetching students and grades
$sql = "SELECT u.id AS student_id, CONCAT(u.LastName, ', ', u.FirstName) AS student_name, 
        s.subject_name, g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`, g.id AS grade_id, sy.year_start, sy.year_end
        FROM grades g
        JOIN users u ON g.student_id = u.id
        JOIN subject s ON g.subject_id = s.id
        JOIN school_year sy ON g.school_year_id = sy.id
        WHERE u.role = 'student'";

if (!empty($search)) {
    $sql .= " AND (u.FirstName LIKE ? OR u.LastName LIKE ? OR s.subject_name LIKE ?)";
}
if ($filter_yearlevel > 0) {
    $sql .= " AND g.yearlevel_id = ?";
}
if ($filter_subject > 0) {
    $sql .= " AND g.subject_id = ?";
}
if ($filter_school_year > 0) {
    $sql .= " AND g.school_year_id = ?";
}
$sql .= " ORDER BY sy.year_start DESC, u.LastName, u.FirstName, s.subject_name";

// Dynamically build the query and bind parameters
$params = [];
$types = ''; // This will store the types for bind_param (e.g., 's', 'i')

// Add filters to the query dynamically
if (!empty($search)) {
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'sss'; // Three string parameters for search
}
if ($filter_yearlevel > 0) {
    $params[] = $filter_yearlevel;
    $types .= 'i'; // Integer parameter for yearlevel
}
if ($filter_subject > 0) {
    $params[] = $filter_subject;
    $types .= 'i'; // Integer parameter for subject
}
if ($filter_school_year > 0) {
    $params[] = $filter_school_year;
    $types .= 'i'; // Integer parameter for school_year
}

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param($types, ...$params); // Use spread operator to pass parameters
}

$stmt->execute();
if ($stmt->error) {
    error_log("Database error: " . $stmt->error);
}
$result = $stmt->get_result();
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[$row['student_id']]['name'] = $row['student_name'];
    $students[$row['student_id']]['grades'][] = $row;
}

$stmt->execute();
if ($stmt->error) {
    error_log("Database error: " . $stmt->error);
}
$result = $stmt->get_result();
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[$row['student_id']]['name'] = $row['student_name'];
    $students[$row['student_id']]['grades'][] = $row;
}
?>