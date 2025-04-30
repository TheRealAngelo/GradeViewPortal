<?php 
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
include_once '../../includes/db_connection.php';

$query = "
    SELECT YEAR(created_at) AS year, COUNT(id) AS users
    FROM users
    WHERE role = 'student'
    GROUP BY YEAR(created_at)
    ORDER BY YEAR(created_at)
";

if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Failed to execute query']);
}
?>