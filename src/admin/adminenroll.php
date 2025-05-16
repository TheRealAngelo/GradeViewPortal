<?php
include '../../includes/db_connection.php';
include 'enroll.php';

// Fetch all school years
$school_years = $conn->query("SELECT id, CONCAT(year_start, '-', year_end) AS sy FROM school_year ORDER BY year_start DESC");
$selected_school_year_id = isset($_GET['school_year']) ? intval($_GET['school_year']) : $current_school_year['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Students</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <h1 style="font-family: Mnt-bold; border-bottom: 1px solid rgba(0, 0, 0, 0.1); padding-bottom: 0.5rem;">Enroll Students</h1>

            <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="GET" action="adminenroll.php">
                <label for="school_year">School Year:</label>
                <select id="school_year" name="school_year" onchange="this.form.submit()">
                    <?php while ($sy = $school_years->fetch_assoc()): ?>
                        <option value="<?php echo $sy['id']; ?>" <?php if ($sy['id'] == $selected_school_year_id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($sy['sy']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <label for="search" style="margin-left: 1rem;">Search Student:</label>
                <input type="text" id="search" name="search" placeholder="Enter ID or Name" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit">Search</button>
            </form>

            <div class="adminDIV">
                <form method="POST" action="adminenroll.php?school_year=<?php echo $selected_school_year_id; ?>">
                    <label style="vertical-align: middle" for="yearlevel_id">Year level to enroll to:</label>
                    <select style="font-family: Mnt-lightEx; padding: 0.25rem; border-radius: 0.5rem; border: 1.5px solid rgba(0, 0, 0, 0.2); border-style: outset; margin-right: 6px;" id="yearlevel_id" name="yearlevel_id" required>
                        <option value="1">Grade 7</option>
                        <option value="2">Grade 8</option>
                        <option value="3">Grade 9</option>
                        <option value="4">Grade 10</option>
                    </select><br><br>
                    <h2 style="font-family: Mnt-bold; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(0, 0, 0, 0.1);">Unenrolled Students</h2>
                    <table style="margin-bottom: 1rem; box-shadow: 4px 2px 2px 0 rgba(0, 0, 0, 0.1);">
                        <tr>
                            <th>Select</th>
                            <th>User ID</th>
                            <th>Student Name</th>
                        </tr>
                        <?php if ($unenrolled_students_result->num_rows === 0): ?>
                            <tr>
                                <td colspan="3" style="text-align:center;">No students found.</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $unenrolled_students_result->fetch_assoc()): ?>
                                <tr>
                                    <td><input type="checkbox" name="students[]" value="<?php echo $row['id']; ?>"></td>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </table>
                    <button type="submit" name="enroll_students" onclick="window.location.reload();">Enroll Selected Students</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>