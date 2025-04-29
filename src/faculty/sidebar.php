<div class="sidebar">
    <div class="sidebar-content">
    <style>
            .sidebar-content{
                display: flex; 
                flex-direction:column;
                justify-content: space-between; 
                height: 100%;
            }
    </style>
        <ul>
            <li><a href="maindashboard.php"><img src="../../assets/icons/icons8-dashboard-96.png">Dashboard</a></li>
            <li><a href="dashboard.php" class="active"><img src="../../assets/icons/icons8-assessment-100.png">Search Grades</a></li>
            <li><a href="dashboardedit.php" class="active"><img src="../../assets/icons/icons8-assessment-100.png">Update Grades</a></li>
            <li><a href="announcements.php"><img src="../../assets/icons/icons8-announcement-100.png">Update Announcements</a></li>
        </ul>
        <form method="POST" action="../logout.php">
            <button type="submit" class="logout"><img src="../../assets/icons/icons8-log-out-90.png">Logout</button>
        </form>
    </div>
</div>