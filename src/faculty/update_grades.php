<?php
include '../includes/db_connection.php';

session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'faculty') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $grade = $_POST['grade'];

    if (empty($student_id) || empty($grade)) {
        $error_message = "Both fields are required.";
    } else {
        $query = "UPDATE grades SET grade = ? WHERE student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $grade, $student_id);

        if ($stmt->execute()) {
            $success_message = "Grades updated successfully.";
        } else {
            $error_message = "Error updating grades. Please try again.";
        }

        $stmt->close();
    }
}

$query = "SELECT * FROM students";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Grades</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Update Grades</h1>

    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="student_id">Student ID:</label>
        <select name="student_id" id="student_id" required>
            <option value="">Select a student</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="grade">Grade:</label>
        <input type="text" name="grade" id="grade" required>

        <button type="submit">Update Grade</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>