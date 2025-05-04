<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['users_file'])) {
    // Import users from CSV
    $file = $_FILES['users_file']['tmp_name'];

    // Open the CSV file
    $handle = fopen($file, 'r');
    if ($handle === false) {
        die('Error opening file.');
    }

    // Skip the header row
    fgetcsv($handle);

    // Process each row
    while (($row = fgetcsv($handle)) !== false) {
        $username = trim($row[0]);
        $first_name = trim($row[1]);
        $last_name = trim($row[2]);
        $role = trim($row[3]);
        $password = password_hash(trim($row[4]), PASSWORD_DEFAULT); // Hash the password

        // Check if the user already exists
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Update existing user
            $update_sql = "UPDATE users SET FirstName = ?, LastName = ?, role = ?, password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssss", $first_name, $last_name, $role, $password, $username);
            $update_stmt->execute();
        } else {
            // Insert new user
            $insert_sql = "INSERT INTO users (username, password, role, FirstName, LastName) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssss", $username, $password, $role, $first_name, $last_name);
            $insert_stmt->execute();
        }
    }

    fclose($handle);
    echo "Users imported successfully!";
} elseif (isset($_GET['export'])) {
    // Export users to CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_export.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write the column headers
    fputcsv($output, ['Username', 'First Name', 'Last Name', 'Role', 'Password']);

    // Fetch data from the `users` table
    $sql = "SELECT username, FirstName, LastName, role, '' AS password FROM users";
    $result = $conn->query($sql);

    // Write each row to the CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export and Import Users</title>
</head>
<body>
    <h1>Export and Import Users</h1>

    <!-- Export Users -->
    <form method="GET" action="createuser.php">
        <button type="submit" name="export">Export Users to CSV</button>
    </form>

    <br>

    <!-- Import Users -->
    <form method="POST" action="createuser.php" enctype="multipart/form-data">
        <label for="users_file">Import Users from CSV:</label>
        <input type="file" name="users_file" accept=".csv" required>
        <button type="submit">Import Users</button>
    </form>
</body>
</html>