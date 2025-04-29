<?php
session_start();
include_once '../../includes/db_connection.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script>
        function updateSubjects(yearLevelId) {
            const subjectDropdown = document.getElementById('subject');
            subjectDropdown.innerHTML = '<option value="0">Loading...</option>'; // Show loading state

            // Fetch subjects dynamically based on the selected year level
            fetch(`dashboard.php?ajax=subjects&yearlevel=${yearLevelId}`)
                .then(response => response.json())
                .then(data => {
                    subjectDropdown.innerHTML = '<option value="0">All Subjects</option>'; // Reset options
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.subject_name;
                        subjectDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching subjects:', error);
                    subjectDropdown.innerHTML = '<option value="0">Error loading subjects</option>';
                });
        }
    </script>
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content noCenter">
            <h2>All Students' Grades</h2>
            <div class="searchDIV">
                <form method="GET" action="dashboard.php">
                    <input type="text" name="search" placeholder="Search Name" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="yearlevel" id="yearlevel" onchange="updateSubjects(this.value)">
                        <option value="0">All Year Levels</option>
                        <option value="1" <?php echo $filter_yearlevel == 1 ? 'selected' : ''; ?>>Grade 7</option>
                        <option value="2" <?php echo $filter_yearlevel == 2 ? 'selected' : ''; ?>>Grade 8</option>
                        <option value="3" <?php echo $filter_yearlevel == 3 ? 'selected' : ''; ?>>Grade 9</option>
                        <option value="4" <?php echo $filter_yearlevel == 4 ? 'selected' : ''; ?>>Grade 10</option>
                    </select>
                    <select name="subject" id="subject">
                        <option value="0">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" <?php echo $filter_subject == $subject['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </option>
                        <?php endforeach; ?>
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
            </div>
            
            <form method="POST" action="update_grades.php">
                <table class="noMarginTop noRoundTopLeftCorner">
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
                            <td class="stud-name noHover" rowspan="<?php echo count($student['grades']); ?>"><?php echo htmlspecialchars($student['name']); ?></td>
                            <?php $first_grade = array_shift($student['grades']); ?>
                            <td><?php echo htmlspecialchars($first_grade['subject_name']); ?></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][1stGrading]" value="<?php echo htmlspecialchars($first_grade['1stGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][2ndGrading]" value="<?php echo htmlspecialchars($first_grade['2ndGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][3rdGrading]" value="<?php echo htmlspecialchars($first_grade['3rdGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][4thGrading]" value="<?php echo htmlspecialchars($first_grade['4thGrading']); ?>"></td>
                            <td>
                                <button type="submit" name="update" value="<?php echo $first_grade['grade_id']; ?>"><img src="../../assets/icons/icons8-save-96.png"></button>
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
                                    <button type="submit" name="update" value="<?php echo $grade['grade_id']; ?>"><img src="../../assets/icons/icons8-save-96.png"></button>
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