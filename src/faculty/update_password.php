<?php
session_start();
include '../../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if ($new_password !== $confirm_password) {
        echo '<script>
            alert("Passwords do not match.");
            window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";
        </script>';
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!password_verify($current_password, $user['password'])) {
        echo '<script>
            alert("Current password is incorrect.");
            window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";
        </script>';
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $hashed_password, $user_id);
    $update_stmt->execute();

    echo '<script>
        alert("Password updated successfully.");
        window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";
    </script>';
    exit();
}
?>