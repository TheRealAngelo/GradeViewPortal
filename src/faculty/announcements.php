<!--HTML FILE-->
<?php
session_start();
include_once '../../includes/db_connection.php';
include_once 'announcementsfunc.php';
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
    <div class="modalBlackbg" id="showMe">
        <div class="modalContainer">
            <h2>Update Announcements</h2>
            <!-- Balik lang ang question mark sa start for debuggin -->
            <!--<php if (!empty($message)): ?>
            <p><php echo $message; ?></p>
            <php endif; ?> -->
            <form method="POST" action="announcements.php">
                <input type="hidden" name="action" value="add">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="5" required></textarea>
                <button id="addButt" onClick="addingAnnouncement" type="submit">Add Announcement</button>
            </form>  
        </div>
    </div>
            
        <?php include 'sidebar.php'; ?>
        <div class="content noCenter">
        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.1); display: flex; flex-direction: row; align-items: center; justify-content: space-between; width: 100%;">
            <h1 style="margin-bottom: 1rem; ">All Announcements</h1>
            <button class="addButt" id="showButt" onClick="showAddAnnouncements"><img style="margin: auto; height: 1rem;" src="../../assets/icons/icons8-plus-24.png"></button>
        </div>
            <script>
                const showMeButt = document.getElementById("showButt");
                const announcementModal = document.getElementById("showMe"); 

                showMeButt.onclick = function showAddAnnouncements(){
                    announcementModal.classList.toggle("show")
                };

                window.onclick = function(event){
                    if(event.target == announcementModal){
                        announcementModal.classList.remove("show");
                    }
                }
                
            </script>
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