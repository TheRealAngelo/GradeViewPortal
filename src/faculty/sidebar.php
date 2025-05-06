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
            <li ><a class="bar" onMouseOver="dashboardChange()" id="hoverDashboard" href="maindashboard.php"><img id="dashIcon" src="../../assets/icons/icons8-dashboard-96.png">Dashboard</a></li>
            <li><a class="bar" onMouseOver="searchChange()" id="hoverSearch" href="dashboard.php" class="active"><img id="searchIcon" src="../../assets/icons/icons8-search-100.png">Search Grades</a></li>
            <li><a class="bar" onMouseOver="gradeChange()" id="hoverGrade" href="dashboardedit.php" class="active"><img id="gradeIcon" src="../../assets/icons/icons8-assessment-100.png">Update Grades</a></li>
            <li><a class="bar" onMouseOver="announcementChange()" id="hoverAnnouncement" href="announcements.php"><img id="announcementIcon" src="../../assets/icons/icons8-announcement-100.png">Update Announcements</a></li>
        </ul>
        <script src="sidebar.js"></script>

        <form method="POST" action="../login/logout.php">
            <button type="submit" class="logout"><img src="../../assets/icons/icons8-log-out-90.png">Logout</button>
        </form>
    </div>
</div>