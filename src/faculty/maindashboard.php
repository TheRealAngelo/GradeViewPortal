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
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content content-dashboard noCenter">
            <div style="display: flex; flex-direction: column; justify-content: center; height: max-content; width: 100%; font-style: bold;">
                <h2 style="width: 100%; margin-bottom: 2rem;">School Year <?php echo htmlspecialchars($school_year); ?></h2>
                <div class="dash-faculty-stats">
                    <div class="dash-faculty-card">
                        <h3 style="border-bottom: 1px solid rgba(0, 0, 0, 0.2); width: 100%;">Enrolled Students</h3>
                        <p><?php echo $student_count; ?></p>
                    </div>
                    <div class="dash-faculty-card">
                        <h3 style="border-bottom: 1px solid rgba(0, 0, 0, 0.2); width: 100%;">Total Faculty</h3>
                        <p><?php echo $faculty_count; ?></p>
                    </div>
                    <div class="dash-faculty-card">
                        <h3 style="border-bottom: 1px solid rgba(0, 0, 0, 0.2); width: 100%;">Total Announcements</h3>
                        <p><?php echo $announcement_count; ?></p>
                    </div>
                </div>


                <h2 style="width: 100%; margin-top: 4rem; margin-bottom: 2rem;">Number of students per Year</h2>
                <div style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 50%;">
                    <canvas id="myChart"></canvas>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    const ctx = document.getElementById('myChart');

                    fetch("script.php")
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            createChart(data, 'bar')
                        });

                    function createChart(chartData, type) {
                        new Chart(ctx, {
                            type: type,
                            data: {
                                labels: chartData.map(row => row.year),
                                datasets: [{
                                    label: '# of Students',
                                    data: chartData.map(row => row.users),
                                    borderWidth: 1,
                                    backgroundColor: 'rgba(0, 128, 0, 0.2)',
                                    borderColor: 'rgb(0, 235, 0)'
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
    <script src="../../includes/timeout.js"></script>
</body>

</html>