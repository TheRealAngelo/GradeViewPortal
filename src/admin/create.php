<?php
//error message niya
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';

    // Validate required fields
    if (empty($username) || empty($password) || empty($role) || empty($first_name) || empty($last_name)) {
        $message = "All fields are required.";
    } else {
        // Check if the username already exists
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Error: Username already exists. Please choose a different username.";
        } else {
            // Hash browns amp
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $insert_sql = "INSERT INTO users (username, password, role, FirstName, LastName) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssss", $username, $hashedPassword, $role, $first_name, $last_name);

            if ($insert_stmt->execute()) {
                echo "<script>alert('User added successfully!');</script>";
                //$message = "User added successfully!";
            } else {
                echo "<script>alert('Error: " . addslashes($insert_stmt->error) . "');</script>";
                //$message = "Error: " . $insert_stmt->error;
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
    }
}
?>