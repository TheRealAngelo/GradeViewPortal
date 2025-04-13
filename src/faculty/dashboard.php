<?php
session_start();
include_once '../../includes/db_connection.php';

// mag check ni if user is Faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit();
}

// Full name parin kai ECONCAT ang latname og firstname
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

// pang search function ayawg hilbati pls
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_yearlevel = isset($_GET['yearlevel']) ? intval($_GET['yearlevel']) : 0;
$filter_school_year = isset($_GET['school_year']) ? intval($_GET['school_year']) : 0;

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
if ($filter_school_year > 0) {
    $sql .= " AND g.school_year_id = ?";
}
$sql .= " ORDER BY sy.year_start DESC, u.LastName, u.FirstName, s.subject_name";

$stmt = $conn->prepare($sql);

if (!empty($search) && $filter_yearlevel > 0 && $filter_school_year > 0) {
    $search_param = "%$search%";
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $filter_yearlevel, $filter_school_year);
} elseif (!empty($search) && $filter_yearlevel > 0) {
    $search_param = "%$search%";
    $stmt->bind_param("sssi", $search_param, $search_param, $search_param, $filter_yearlevel);
} elseif (!empty($search) && $filter_school_year > 0) {
    $search_param = "%$search%";
    $stmt->bind_param("sssi", $search_param, $search_param, $search_param, $filter_school_year);
} elseif ($filter_yearlevel > 0 && $filter_school_year > 0) {
    $stmt->bind_param("ii", $filter_yearlevel, $filter_school_year);
} elseif (!empty($search)) {
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
} elseif ($filter_yearlevel > 0) {
    $stmt->bind_param("i", $filter_yearlevel);
} elseif ($filter_school_year > 0) {
    $stmt->bind_param("i", $filter_school_year);
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
//hanggan sa duloooo ng joke ito taman ng search function nice noh
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
    <?php include 'sidebar.php'; ?>
        <div class="content">
            <h2>All Students' Grades</h2>

            <form method="GET" action="dashboard.php">
                <input type="text" name="search" placeholder="Search by name or subject" value="<?php echo htmlspecialchars($search); ?>">
                <select name="yearlevel">
                    <option value="0">All Year Levels</option>
                    <option value="1" <?php echo $filter_yearlevel == 1 ? 'selected' : ''; ?>>Grade 7</option>
                    <option value="2" <?php echo $filter_yearlevel == 2 ? 'selected' : ''; ?>>Grade 8</option>
                    <option value="3" <?php echo $filter_yearlevel == 3 ? 'selected' : ''; ?>>Grade 9</option>
                    <option value="4" <?php echo $filter_yearlevel == 4 ? 'selected' : ''; ?>>Grade 10</option>
                </select>
                <select name="school_year">
                    <option value="0">All School Years</option>
                    <?php
                    $school_years = $conn->query("SELECT id, year_start, year_end FROM school_year ORDER BY year_start DESC");
                    while ($row = $school_years->fetch_assoc()) {
                        $selected = ($filter_school_year == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['year_start']}-{$row['year_end']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Search</button>
            </form>

            <form method="POST" action="update_grades.php">
                <table>
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
                            <td><?php echo htmlspecialchars($first_grade['subject_name']); ?></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][1stGrading]" value="<?php echo htmlspecialchars($first_grade['1stGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][2ndGrading]" value="<?php echo htmlspecialchars($first_grade['2ndGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][3rdGrading]" value="<?php echo htmlspecialchars($first_grade['3rdGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][4thGrading]" value="<?php echo htmlspecialchars($first_grade['4thGrading']); ?>"></td>
                            <td>
                                <button type="submit" name="update" value="<?php echo $first_grade['grade_id']; ?>">Save</button>
                            </td>
                        </tr>
                        <?php foreach ($student['grades'] as $grade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][1stGrading]" value="<?php echo htmlspecialchars($grade['1stGrading']); ?>"></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][2ndGrading]" value="<?php echo htmlspecialchars($grade['2ndGrading']); ?>"></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][3rdGrading]" value="<?php echo htmlspecialchars($grade['3rdGrading']); ?>"></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][4thGrading]" value="<?php echo htmlspecialchars($grade['4thGrading']); ?>"></td>
                                <td>
                                    <button type="submit" name="update" value="<?php echo $grade['grade_id']; ?>">Save</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>
</body>
</html>