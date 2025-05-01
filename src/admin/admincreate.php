<?php
include '../../includes/db_connection.php';
include 'create.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty or Student</title>
    <link rel="stylesheet" href="../../assets/css/styles.css"> 
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <h1 style="border-bottom: 1px solid rgba(0, 0, 0, 0.1); padding-bottom: 0.5rem; font-family: Mnt-bold;">Add Faculty or Student</h1>
            <div class="adminDIV">
                
                    <form method="POST" action="admincreate.php">
                        <div class="adminAddName">
                            <div class="admin-wrapper">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="admin-wrapper">
                                <label for="last_name">Last Name:</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="adminAddDetails">
                            <div class="admin-wrapper">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" required>
                            </div>
                            <div class="admin-wrapper">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="adminAddRole">
                        <label for="role">Role:</label>
                            <select id="role" name="role" required>
                                <option value="faculty">Faculty</option>
                                <option value="student">Student</option>
                            </select>
                        </div>

                        <button type="submit">Add User</button>
                    </form>

                    <?php if (!empty($message)): ?>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                    </div>
            </div>
        </div>
</body>
</html>