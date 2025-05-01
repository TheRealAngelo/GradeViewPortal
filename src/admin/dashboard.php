<?php
include '../../includes/db_connection.php';
include 'dashboardfunc.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <h1 style="margin-top: 0; padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.1);">Dashboard</h1>
            <div class="adminDIV">
                <div class="card-wrapper">
                    <div class="cards">
                        <h2>Students</h2>
                        <p><?php echo $total_students; ?></p>
                    </div>
                    <div class="cards">
                        <h2>Enrolled Students</h2>
                        <p><?php echo $total_students_with_grades; ?></p>
                    </div>
                    <div class="cards">
                        <h2>Active Faculty</h2>
                        <p><?php echo $total_faculty; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>

</html>