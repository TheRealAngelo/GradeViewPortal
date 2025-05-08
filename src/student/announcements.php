<?php
session_start();
include_once '../../includes/db_connection.php';
include 'announcementsfunc.php';
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
                        <small><?php echo htmlspecialchars($row['created_at']); ?></small>
                        <div class="announcementsTitle-left">
                            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p><?php echo htmlspecialchars($row['created_by']); ?></p>
                        </div>
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