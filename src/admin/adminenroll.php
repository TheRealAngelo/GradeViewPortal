<?php
include '../../includes/db_connection.php';
include 'enroll.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Students</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Enroll Students for <?php echo $current_school_year['year_start'] . '-' . $current_school_year['year_end']; ?></h1>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="adminenroll.php">
        <label for="yearlevel_id">Year Level:</label>
        <select id="yearlevel_id" name="yearlevel_id" required>
            <option value="1">Grade 7</option>
            <option value="2">Grade 8</option>
            <option value="3">Grade 9</option>
            <option value="4">Grade 10</option>
        </select><br><br>

        <h2>Unenrolled Students</h2>
        <table>
            <tr>
                <th>Select</th>
                <th>Student Name</th>
            </tr>
            <?php while ($row = $unenrolled_students_result->fetch_assoc()): ?>
                <tr>
                    <td><input type="checkbox" name="students[]" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <button type="submit" name="enroll_students">Enroll Selected Students</button>
    </form>
</body>
</html>