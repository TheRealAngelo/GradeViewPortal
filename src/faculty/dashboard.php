<?php
session_start();
include_once '../../includes/db_connection.php'; // Corrected path
include_once '../../includes/header.php'; // Corrected path

// Check if the user is logged in and is a faculty member
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit();
}

// Fetch the faculty member's full name
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

// Handle grade updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grade_id'])) {
    $grade_id = $_POST['grade_id'];
    $first_grading = $_POST['1stGrading'];
    $second_grading = $_POST['2ndGrading'];
    $third_grading = $_POST['3rdGrading'];
    $fourth_grading = $_POST['4thGrading'];

    $stmt = $conn->prepare("UPDATE grades SET `1stGrading` = ?, `2ndGrading` = ?, `3rdGrading` = ?, `4thGrading` = ? WHERE id = ?");
    $stmt->bind_param("iiiii", $first_grading, $second_grading, $third_grading, $fourth_grading, $grade_id);

    if ($stmt->execute()) {
        $message = "Grades updated successfully!";
    } else {
        $message = "Failed to update grades: " . $stmt->error;
    }
}

// Handle search and subject filter functionality
$search_query = "";
$subject_filter = "";
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
}
if (isset($_GET['subject'])) {
    $subject_filter = trim($_GET['subject']);
}

// Fetch all students' grades grouped by student
$sql = "SELECT u.id AS student_id, CONCAT(u.LastName, ', ', u.FirstName) AS student_name, g.id AS grade_id, g.subject, g.`1stGrading`, g.`2ndGrading`, g.`3rdGrading`, g.`4thGrading`
        FROM grades g
        JOIN users u ON g.student_id = u.id
        WHERE u.role = 'student'";

if (!empty($search_query)) {
    $sql .= " AND (u.FirstName LIKE ? OR u.LastName LIKE ?)";
}
if (!empty($subject_filter)) {
    $sql .= " AND g.subject = ?";
}

$sql .= " ORDER BY u.LastName, u.FirstName, g.subject";

$stmt = $conn->prepare($sql);

if (!empty($search_query) && !empty($subject_filter)) {
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("sss", $search_term, $search_term, $subject_filter);
} elseif (!empty($search_query)) {
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("ss", $search_term, $search_term);
} elseif (!empty($subject_filter)) {
    $stmt->bind_param("s", $subject_filter);
}

$stmt->execute();
$result = $stmt->get_result();

// Group grades by student
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[$row['student_id']]['name'] = $row['student_name'];
    $students[$row['student_id']]['grades'][] = $row;
}

// Fetch distinct subjects for the filter dropdown
$subject_stmt = $conn->prepare("SELECT DISTINCT subject FROM grades ORDER BY subject");
$subject_stmt->execute();
$subject_result = $subject_stmt->get_result();
$subjects = [];
while ($subject_row = $subject_result->fetch_assoc()) {
    $subjects[] = $subject_row['subject'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($faculty_name); ?>!</h1>
    <h2>All Students' Grades</h2>

    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Search and Filter Form -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by student name" value="<?php echo htmlspecialchars($search_query); ?>">
        <select name="subject">
            <option value="">All Subjects</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?php echo htmlspecialchars($subject); ?>" <?php echo ($subject_filter === $subject) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($subject); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <table border="1">
        <tr>
            <th>Student Name</th>
            <th>Subject</th>
            <th>1st Grading</th>
            <th>2nd Grading</th>
            <th>3rd Grading</th>
            <th>4th Grading</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($students as $student): ?>
            <tr>
                <td rowspan="<?php echo count($student['grades']); ?>"><?php echo htmlspecialchars($student['name']); ?></td>
                <?php $first_grade = array_shift($student['grades']); ?>
                <td><?php echo htmlspecialchars($first_grade['subject']); ?></td>
                <td><input type="number" name="1stGrading" value="<?php echo htmlspecialchars($first_grade['1stGrading']); ?>" required></td>
                <td><input type="number" name="2ndGrading" value="<?php echo htmlspecialchars($first_grade['2ndGrading']); ?>" required></td>
                <td><input type="number" name="3rdGrading" value="<?php echo htmlspecialchars($first_grade['3rdGrading']); ?>" required></td>
                <td><input type="number" name="4thGrading" value="<?php echo htmlspecialchars($first_grade['4thGrading']); ?>" required></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="grade_id" value="<?php echo htmlspecialchars($first_grade['grade_id']); ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            <?php foreach ($student['grades'] as $grade): ?>
                <tr>
                    <td><?php echo htmlspecialchars($grade['subject']); ?></td>
                    <td><input type="number" name="1stGrading" value="<?php echo htmlspecialchars($grade['1stGrading']); ?>" required></td>
                    <td><input type="number" name="2ndGrading" value="<?php echo htmlspecialchars($grade['2ndGrading']); ?>" required></td>
                    <td><input type="number" name="3rdGrading" value="<?php echo htmlspecialchars($grade['3rdGrading']); ?>" required></td>
                    <td><input type="number" name="4thGrading" value="<?php echo htmlspecialchars($grade['4thGrading']); ?>" required></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="grade_id" value="<?php echo htmlspecialchars($grade['grade_id']); ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</body>
</html>