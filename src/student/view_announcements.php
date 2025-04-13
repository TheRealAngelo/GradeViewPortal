<?php
include('../includes/db_connection.php');

// AYAWG HILABTA PLEASE
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error fetching announcements: " . mysqli_error($conn);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="container">
        <h1>Announcements</h1>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p><?php echo htmlspecialchars($row['message']); ?></p>
                        <small><?php echo htmlspecialchars($row['created_at']); ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No announcements available.</p>
        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>