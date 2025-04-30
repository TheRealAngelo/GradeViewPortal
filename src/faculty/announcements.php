<?php
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
session_start();
include_once '../../includes/db_connection.php';
include_once 'announcementsfunc.php';
//HTML FILE//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Announcements</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content noCenter">
            <h2>Update Announcements</h2>
            <?php if (!empty($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="announcements.php">
                <input type="hidden" name="action" value="add">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required><br><br>
                <label for="content">Content:</label><br>
                <textarea id="content" name="content" rows="5" required></textarea><br><br>
                <button type="submit">Add Announcement</button>
            </form>
            <h3>All Announcements</h3>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['content']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <form method="POST" action="announcements.php" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="announcement_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>
</html>