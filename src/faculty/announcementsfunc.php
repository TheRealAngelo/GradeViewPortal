<?php
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
//--authentication user is Faculty--//
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login/login.php");
    exit();
}

//--Pang kuha og buo of full name ayawg na hilbta pls--//
$name_stmt = $conn->prepare("SELECT CONCAT(LastName, ', ', FirstName) AS full_name FROM users WHERE id = ?");
$name_stmt->bind_param("i", $_SESSION['user_id']);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$faculty_name = $name_result->fetch_assoc()['full_name'];

//--submission for announcements ni--//
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $created_by = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO announcements (title, content, created_by) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $created_by);

        if ($stmt->execute()) {
            $message = "Announcement added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "All fields are required.";
    }
}

//--deleting announcements ni--//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $announcement_id = intval($_POST['announcement_id']);

    if ($announcement_id > 0) {
        $sql = "DELETE FROM announcements WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $announcement_id);

        if ($stmt->execute()) {
            $message = "Announcement deleted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "Invalid announcement ID.";
    }
}

//--fetch all announcements--//
$sql = "SELECT a.id, a.title, a.content, a.created_at, CONCAT(u.FirstName, ' ', u.LastName) AS created_by
        FROM announcements a
        JOIN users u ON a.created_by = u.id
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);
?>