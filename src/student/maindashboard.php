<?php
session_start();
include_once '../../includes/db_connection.php';
include 'maindashboardfunc.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="bigDIV">
                <div style="display: flex; flex-direction: column;">
                    <h1 style="font-family: Mnt-black ; width: fit-content;"><?php echo htmlspecialchars($student_name); ?></h1>
                    <h3 style="font-family: Mnt; margin-bottom: 0.25rem ; margin-top:0; width: fit-content">School Year: <?php echo htmlspecialchars($schoolYear); ?></h3>
                    <small style="font-family: Mnt-light; width: fit-content; margin-bottom: 1rem">Year Level: <?php echo htmlspecialchars($yearLevel); ?></small>
                </div>

                <div style="width: 100%;">
                    <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                        <div class="dash-student-card">
                            <img src="../../assets/icons/icons8-whiteAssessment-100.png" alt="User Icon" style="width: auto; height: 1.5rem;">
                            <div class="carding">
                                <h2>Grade Status</h2>
                                <p class="carding" style="font-size: 20px; margin-bottom: 0; margin-left: 1.5rem;"><?php echo htmlspecialchars($grades_status); ?></p>
                            </div>
                        </div>
                    </a>
                    <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                        <div class="dash-student-card">
                            <img src="../../assets/icons/icons8-whiteAssessment-100.png" alt="User Icon" style="width: auto; height: 1.5rem;">
                            <div class="carding">
                                <h2>Top Performing</h2>
                                <p style="margin-bottom: 0; margin-left: 1.5rem;">
                                    <?php
                                    echo !empty($top_subjects) ? htmlspecialchars(implode(', ', $top_subjects)) : ' Grades On Process';
                                    ?>
                                </p>
                            </div>
                        </div>
                    </a>
                    <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                        <div class="dash-student-card">
                            <img src="../../assets/icons/icons8-whiteAssessment-100.png" alt="User Icon" style="width: auto; height: 1.5rem;">
                            <div class="carding">
                                <h2>Lowest Performing</h2>
                                <p style="margin-bottom: 0; margin-left: 1.5rem;">
                                    <?php
                                    echo !empty($lowest_subjects) ? htmlspecialchars(implode(', ', $lowest_subjects)) : ' Grades On Process';
                                    ?>
                                </p>
                            </div>
                        </div>
                    </a>
                    <a href="announcements.php" style="text-decoration: none; color: inherit;">
                        <div class="dash-student-card">
                            <img src="../../assets/icons/icons8-whiteAnnouncement-100.png" alt="User Icon" style="width: auto; height: 1.5rem;">
                            <div class="carding">
                                <h2>Latest Announcement</h2>
                                <p style="margin-bottom: 0; margin-left: 1.5rem;"><?php echo '" ' . htmlspecialchars($announcement_title) . ' " ' . ' Created by: ' . htmlspecialchars($announcement_creator); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="student-info-box">
                    <p>For any concerns regarding your grade assessment, please visit the Registrar Office or contact them at 0999-999-9999.</p>
                </div>
            </div>
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>

</html>