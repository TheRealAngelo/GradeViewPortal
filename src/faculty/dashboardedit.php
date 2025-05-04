<?php
//--HTML FILE--//
session_start();
include_once '../../includes/db_connection.php';
require 'dashboardfunc.php';

if (isset($_GET['ajax']) && $_GET['ajax'] === 'subjects' && isset($_GET['yearlevel'])) {
    $yearlevel = intval($_GET['yearlevel']);
    $subjects = [];

    if ($yearlevel > 0) {
        // Fetch subjects for the selected year level
        $stmt = $conn->prepare("SELECT id, subject_name FROM subject WHERE yearlevel_id = ?");
        $stmt->bind_param("i", $yearlevel);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    }

    echo json_encode($subjects);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script>
        function updateSubjects(yearLevelId) {
            const subjectDropdown = document.getElementById('export_subject');
            subjectDropdown.innerHTML = '<option value="0">Loading...</option>'; // Show loading state

            // Fetch subjects dynamically based on the selected year level
            fetch(`dashboardedit.php?ajax=subjects&yearlevel=${yearLevelId}`)
                .then(response => response.json())
                .then(data => {
                    subjectDropdown.innerHTML = '<option value="0">All Subjects</option>'; // Reset options
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.subject_name;
                        subjectDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching subjects:', error);
                    subjectDropdown.innerHTML = '<option value="0">Error loading subjects</option>';
                });
        }
    </script>
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <h2>Edit Students' Grades</h2>

            <h3 style="margin-bottom: 1rem;">Import Grade File</h3>
            <div class="bigDIV importForms">

                <!-- Import Grades Form -->
                <form method="POST" action="import_grades.php" enctype="multipart/form-data" style="display: inline;">   
                    <input type="file" name="grades_file" accept=".csv" required>
                    <button type="submit">Import Grades</button> 
                </form>
            
            </div>

            <h3 style="margin-bottom: 1rem;">Export Student Grades</h3>
            <div class="bigDIV exportForms">
                <form method="GET" action="dashboardedit.php">
                    <!--EXPORT IMPORT GRADES FUNC-->
                </form>
                <!-- Export Grades Form -->
                <form method="POST" action="export_grades.php" style="display: inline;">
                    <div class="exportFormsTop">
                        <label for="export_yearlevel">Year Level:
                            <select name="yearlevel" id="export_yearlevel" onchange="updateSubjects(this.value)">
                                <option value="0">All Year Levels</option>
                                <option value="1">Grade 7</option>
                                <option value="2">Grade 8</option>
                                <option value="3">Grade 9</option>
                                <option value="4">Grade 10</option>
                            </select>
                        </label>

                        <label for="export_subject">Subject:
                            <select name="subject" id="export_subject">
                                <option value="0">All Subjects</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo $subject['id']; ?>"><?php echo htmlspecialchars($subject['subject_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label for="export_school_year">School Year:
                            <select name="school_year" id="export_school_year">
                                <option value="0">All School Years</option>
                                <?php
                                $school_years = $conn->query("SELECT id, year_start, year_end FROM school_year ORDER BY year_start DESC");
                                while ($row = $school_years->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['year_start']}-{$row['year_end']}</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                    <button type="submit">Export Grades</button>
                </form>
            <!--EXPORT IMPORT GRADES FUNC-->
            </div>
        </div>
    </div>
</body>
</html>