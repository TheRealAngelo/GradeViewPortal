<div class="sidebar">
            <h3>Welcome <?php echo htmlspecialchars($student_name); ?>!</h3>
            <a href="dashboard.php">View Grades</a>
            <a href="announcements.php">View Announcements</a>
            <form method="POST" action="../logout.php">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
<style> 
</style>