<?php
include '../includes/db_connection.php';
//yawg hilabta ang mga code please
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'faculty') {
    header('Location: ../login.php');
    exit();
}

$announcement = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcement = trim($_POST['announcement']);

    if (empty($announcement)) {
        $error = 'Announcement text cannot be empty.';
    } else {
        $stmt = $conn->prepare("INSERT INTO announcements (announcement_text, created_at) VALUES (?, NOW())");
        $stmt->bind_param("s", $announcement);

        if ($stmt->execute()) {
            header('Location: dashboard.php?success=Announcement posted successfully.');
            exit();
        } else {
            $error = 'Failed to post announcement. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Announcement</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Update Announcement</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <textarea name="announcement" rows="5" required><?php echo htmlspecialchars($announcement); ?></textarea>
            <button type="submit">Post Announcement</button>
        </form>
    </div>
</body>
</html>