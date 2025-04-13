<?php
session_start();
include_once '../../includes/db_connection.php';

// mag check ni if user is Faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit();
}

// Script pang kuha og buo of full name ayawg na hilbta pls
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

// submission for announcements ni
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $created_by = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO announcements (title, content, created_by) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $created_by);

        if ($stmt->execute()) {
            $message = "Announcement added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "All fields are required.";
    }
}

// deleting announcements ni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $announcement_id = intval($_POST['announcement_id']);

    if ($announcement_id > 0) {
        $sql = "DELETE FROM announcements WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $announcement_id);

        if ($stmt->execute()) {
            $message = "Announcement deleted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "Invalid announcement ID.";
    }
}

// fetch all announcements
$sql = "SELECT a.id, a.title, a.content, a.created_at, CONCAT(u.FirstName, ' ', u.LastName) AS created_by
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
    <title>Update Announcements</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
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
</body>
</html>