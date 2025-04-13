//AYAWG HILBTA
<?php
include '../includes/db_connection.php';

$sql = "SELECT u.id, CONCAT(u.LastName, ', ', u.FirstName) AS student_name, y.level_name
        FROM users u
        JOIN grades g ON u.id = g.student_id
        JOIN yearlevel y ON g.yearlevel_id = y.id
        WHERE u.role = 'student'
        GROUP BY u.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Students for Next School Year</title>
</head>
<body>
    <h1>Select Students for Next School Year</h1>
    <form method="POST" action="duplicate_grades.php">
        <table border="1">
            <tr>
                <th>Select</th>
                <th>Student Name</th>
                <th>Year Level</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><input type="checkbox" name="students[]" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['level_name']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit">Proceed to Next School Year</button>
    </form>
</body>
</html>