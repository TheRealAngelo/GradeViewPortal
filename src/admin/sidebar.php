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
            <li><a class="bar" onMouseOver="dashboardChange()" id="hoverDashboard" href="dashboard.php" class="active"><img src="../../assets/icons/icons8-dashboard-96.png">Dashboard</a></li>
            <li><a class="bar" onMouseOver="gradeChange()" id="hoverGrade" href="adminenroll.php" class="active"><img src="../../assets/icons/icons8-assessment-100.png">Enroll Students</a></li>
            <li><a class="bar" href="admincreate.php" class="active"><img src="../../assets/icons/icons8-assessment-100.png">Add User</a></li>
        </ul>
        <form method="POST" action="../index.php">
            <button type="submit" class="logout"><img src="../../assets/icons/icons8-log-out-90.png">Logout</button>
        </form>
        <script src="sidebar.js"></script>
    </div>
</div>