<!-- filepath: c:\xampp\htdocs\SoftwareEngineering2Final\src\admin.php -->
<?php
include '../includes/db_connection.php';

// Initialize variables for error/success messages
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    // Validate inputs
    if (empty($username) || empty($password) || empty($role) || empty($first_name) || empty($last_name)) {
        $message = "All fields are required.";
    } else {
        // Check if the username already exists
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Error: Username already exists. Please choose a different username.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into the users table
            $sql = "INSERT INTO users (username, password, role, FirstName, LastName) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $username, $hashedPassword, $role, $first_name, $last_name);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id; // Get the ID of the newly created user

                // If the user is a student, initialize their grades
                if ($role === 'student') {
                    $subjects = ['Mathematics', 'Science', 'English', 'History']; // Example subjects
                    $grade_stmt = $conn->prepare("INSERT INTO grades (student_id, subject, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading`, created_by) VALUES (?, ?, 0, 0, 0, 0, ?)");
                    foreach ($subjects as $subject) {
                        $grade_stmt->bind_param("isi", $user_id, $subject, $user_id); // `created_by` is set to the student's ID for simplicity
                        $grade_stmt->execute();
                    }
                    $grade_stmt->close();
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
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Optional CSS -->
</head>
<body>
    <h1>Add Faculty or Student</h1>
    <form method="POST" action="admin.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="faculty">Faculty</option>
            <option value="student">Student</option>
        </select><br><br>

        <button type="submit">Add User</button>
    </form>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>