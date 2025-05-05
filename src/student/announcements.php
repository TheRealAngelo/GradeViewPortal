<?php
session_start();
include_once '../../includes/db_connection.php';

// checkniya kung user kai student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login/login.php");
    exit();
}

// mao jaoun full name concat
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$student_name = $name_result->fetch_assoc()['full_name'];

// fetch announcements ni
$sql = "SELECT a.title, a.content, a.created_at, CONCAT(u.FirstName, ' ', u.LastName) AS created_by
        FROM announcements a
        JOIN users u ON a.created_by = u.id
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <h1 style="font-family: Mnt-bold;">Announcements</h1>
            <div class="announcements-wrapper">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="announcementsDIV">
                    <div class="announcementsTitle">
                        <div class="announcementsTitle-left">
                            <h2><?php echo htmlspecialchars($row['title']); ?> </h2>
                            <p><?php echo htmlspecialchars($row['created_by']); ?><br></p>
                        </div>
                        <small><?php echo htmlspecialchars($row['created_at']); ?></small>
                    </div>
                    <div class="announcementsContent">
                        <?php echo htmlspecialchars($row['content']); ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <table>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['content']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>
</html>