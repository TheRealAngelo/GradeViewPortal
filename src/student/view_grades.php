<?php
session_start();
include('../includes/db_connection.php');

// kani sad Ayawg hilabti thanks
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT subject, grade FROM grades WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $grades = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $grades = [];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Grades</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="container">
        <h1>Your Grades</h1>
        <?php if (!empty($grades)): ?>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grade['subject']); ?></td>
                        <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No grades available.</p>
        <?php endif; ?>
    </div>
<script src="../../includes/timeout.js"></script>
</body>
</html>