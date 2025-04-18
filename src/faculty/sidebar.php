<div class="sidebar">
    <h3>Welcome <?php echo htmlspecialchars($faculty_name); ?>!</h3>
    <div>
        <ul>
            <li><a href="dashboard.php" class="active"><img src="../../assets/icons/icons8-assessment-100.png">Update Grades</a></li>
            <li><a href="announcements.php"><img src="../../assets/icons/icons8-announcement-100.png">Update Announcements</a></li>
        </ul>
        <form method="POST" action="../logout.php">
            <button type="submit" class="logout"><img src="../../assets/icons/icons8-log-out-90.png">Logout</button>
        </form>
    </div>
</div>