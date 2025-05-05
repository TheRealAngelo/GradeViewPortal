<?php
session_start();
include '../../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school_year_id = intval($_POST['school_year']);
    $_SESSION['school_year_id'] = $school_year_id;

    echo '<script>
        alert("School Year Changed");
        window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";
    </script>';
    exit();
}
?>