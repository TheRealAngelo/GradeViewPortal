<div class="sidebar">
            <h3>Welcome <?php echo htmlspecialchars($faculty_name); ?>!</h3>
            <a href="dashboard.php" class="active">Update Grades</a>
            <a href="announcements.php">Update Announcements</a>
            <form method="POST" action="../logout.php">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>