<?php
include '../../includes/db_connection.php';

//error message niya
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are set
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $yearlevel_id = isset($_POST['yearlevel_id']) ? intval($_POST['yearlevel_id']) : null;

    // Validate required fields
    if (empty($username) || empty($password) || empty($role) || empty($first_name) || empty($last_name) || ($role === 'student' && empty($yearlevel_id))) {
        $message = "All fields are required.";
    } else {
        // Proceed with the rest of the logic
        // verify kung naa na
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Error: Username already exists. Please choose a different username.";
        } else {
            // Hash browns amp
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create ni og user
            $sql = "INSERT INTO users (username, password, role, FirstName, LastName) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $username, $hashedPassword, $role, $first_name, $last_name);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id; 

                if ($role === 'student') {
                    
                    $school_year_sql = "SELECT id FROM school_year WHERE year_start = 2025 AND year_end = 2026"; // Update dynamically as needed
                    $school_year_result = $conn->query($school_year_sql);
                    $school_year_id = $school_year_result->fetch_assoc()['id'];

                    $subject_sql = "SELECT id FROM subject WHERE yearlevel_id = ?";
                    $subject_stmt = $conn->prepare($subject_sql);
                    $subject_stmt->bind_param("i", $yearlevel_id);
                    $subject_stmt->execute();
                    $subject_result = $subject_stmt->get_result();

                    $grade_stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading`, yearlevel_id, school_year_id, created_by) VALUES (?, ?, 0, 0, 0, 0, ?, ?, ?)");
                    while ($subject_row = $subject_result->fetch_assoc()) {
                        $subject_id = $subject_row['id'];
                        $grade_stmt->bind_param("iiiii", $user_id, $subject_id, $yearlevel_id, $school_year_id, $user_id);
                        $grade_stmt->execute();
                    }
                    $grade_stmt->close();
                    $subject_stmt->close();
                }

                $message = "User and grades added successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty or Student</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- mao ni css file nato -->
</head>
<body>
    <h1>Add Faculty or Student</h1>
    <form method="POST" action="admincreate.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required onchange="toggleYearLevel(this.value)">
            <option value="faculty">Faculty</option>
            <option value="student">Student</option>
        </select><br><br>

        <div id="yearlevel-container" style="display: none;">
            <label for="yearlevel_id">Year Level:</label>
            <select id="yearlevel_id" name="yearlevel_id">
                <option value="1">Grade 7</option>
                <option value="2">Grade 8</option>
                <option value="3">Grade 9</option>
                <option value="4">Grade 10</option>
            </select><br><br>
        </div>

        <button type="submit">Add User</button>
    </form>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <script>
        function toggleYearLevel(role) {
            const yearLevelContainer = document.getElementById('yearlevel-container');
            if (role === 'student') {
                yearLevelContainer.style.display = 'block';
            } else {
                yearLevelContainer.style.display = 'none';
            }
        }
    </script>
</body>
</html>