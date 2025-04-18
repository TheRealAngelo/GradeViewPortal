<div class="sidebar">
    <h3>Welcome <?php echo htmlspecialchars($student_name); ?>!</h3>
    <small>student id</small>
    <div>
        <ul>
            <li><a href="dashboard.php"><img src="../../assets/icons/icons8-assessment-100.png">View Grades</a></li>
            <li><a href="announcements.php"><img src="../../assets/icons/icons8-announcement-100.png">View Announcements</a></li>
        </ul>
        <form method="POST" action="../logout.php">
            <button type="submit" class="logout"><img src="../../assets/icons/icons8-log-out-90.png">Logout</button>
        </form>
    </div>
</div>