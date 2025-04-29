<?php
session_start();
include_once '../../includes/db_connection.php';
require 'dashboardfunc.php';
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
            const subjectDropdown = document.getElementById('subject');
            subjectDropdown.innerHTML = '<option value="0">Loading...</option>'; // Show loading state

            // Fetch subjects dynamically based on the selected year level
            fetch(`dashboard.php?ajax=subjects&yearlevel=${yearLevelId}`)
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
        <div class="content noCenter">
            <h2>Search Students' Grades</h2>
            <div class="searchDIV">
                <form method="GET" action="dashboard.php">
                    <input type="text" name="search" placeholder="Search Name" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="yearlevel" id="yearlevel" onchange="updateSubjects(this.value)">
                        <option value="0">All Year Levels</option>
                        <option value="1" <?php echo $filter_yearlevel == 1 ? 'selected' : ''; ?>>Grade 7</option>
                        <option value="2" <?php echo $filter_yearlevel == 2 ? 'selected' : ''; ?>>Grade 8</option>
                        <option value="3" <?php echo $filter_yearlevel == 3 ? 'selected' : ''; ?>>Grade 9</option>
                        <option value="4" <?php echo $filter_yearlevel == 4 ? 'selected' : ''; ?>>Grade 10</option>
                    </select>
                    <select name="subject" id="subject">
                        <option value="0">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" <?php echo $filter_subject == $subject['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="school_year">
                        <option value="0">All School Years</option>
                        <?php
                        $school_years = $conn->query("SELECT id, year_start, year_end FROM school_year ORDER BY year_start DESC");
                        while ($row = $school_years->fetch_assoc()) {
                            $selected = ($filter_school_year == $row['id']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>{$row['year_start']}-{$row['year_end']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Search</button>
            </div>
            
            <form method="POST" action="update_grades.php">
                <table class="noMarginTop noRoundTopLeftCorner">
                    <tr>
                        <th>Student Name</th>
                        <th>Subject</th>
                        <th>1st Grading</th>
                        <th>2nd Grading</th>
                        <th>3rd Grading</th>
                        <th>4th Grading</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="stud-name noHover" rowspan="<?php echo count($student['grades']); ?>"><?php echo htmlspecialchars($student['name']); ?></td>
                            <?php $first_grade = array_shift($student['grades']); ?>
                            <td><?php echo htmlspecialchars($first_grade['subject_name']); ?></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][1stGrading]" value="<?php echo htmlspecialchars($first_grade['1stGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][2ndGrading]" value="<?php echo htmlspecialchars($first_grade['2ndGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][3rdGrading]" value="<?php echo htmlspecialchars($first_grade['3rdGrading']); ?>"></td>
                            <td><input type="number" name="grades[<?php echo $first_grade['grade_id']; ?>][4thGrading]" value="<?php echo htmlspecialchars($first_grade['4thGrading']); ?>"></td>
                            <td>
                                <button type="submit" name="update" value="<?php echo $first_grade['grade_id']; ?>"><img src="../../assets/icons/icons8-save-96.png"></button>
                            </td>
                        </tr>
                        <?php foreach ($student['grades'] as $grade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][1stGrading]" value="<?php echo htmlspecialchars($grade['1stGrading']); ?>"></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][2ndGrading]" value="<?php echo htmlspecialchars($grade['2ndGrading']); ?>"></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][3rdGrading]" value="<?php echo htmlspecialchars($grade['3rdGrading']); ?>"></td>
                                <td><input type="number" name="grades[<?php echo $grade['grade_id']; ?>][4thGrading]" value="<?php echo htmlspecialchars($grade['4thGrading']); ?>"></td>
                                <td>
                                    <button type="submit" name="update" value="<?php echo $grade['grade_id']; ?>"><img src="../../assets/icons/icons8-save-96.png"></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>
</body>
</html>