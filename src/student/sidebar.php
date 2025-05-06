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
            <li><a class="bar" onMouseOver="vmChange()" id="hoverVm" href="vmpage.php"><img id="vmIcon" src="../../assets/icons/icons8-about-64.png">Vision Mission</a></li>
            <li><a class="bar" onMouseOver="dashboardChange()" id="hoverDashboard" href="maindashboard.php"><img id="dashboardIcon" src="../../assets/icons/icons8-dashboard-96.png">Dashboard</a></li>
            <li><a class="bar" onMouseOver="gradeChange()" id="hoverGrade" href="dashboard.php"><img id="gradeIcon" src="../../assets/icons/icons8-assessment-100.png">View Grades</a></li>
            <li><a class="bar" onMouseOver="announcementChange()" id="hoverAnnouncement" href="announcements.php"><img id="announcementIcon" src="../../assets/icons/icons8-announcement-100.png">View Announcements</a></li>
        </ul>
        <script src="sidebar.js"></script>

        <form method="POST" action="../login/logout.php">
            <button type="submit" class="logout"><img src="../../assets/icons/icons8-log-out-90.png">Logout</button>
        </form>
    </div>
</div>