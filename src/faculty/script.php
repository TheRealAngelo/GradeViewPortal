<?php 
//-- AYAWG HILABTI CONSULT GELO MUNA PLEASE--//
include_once '../../includes/db_connection.php';

$query = "
    SELECT YEAR(g.created_at) AS year, COUNT(DISTINCT u.id) AS users
    FROM users u
    INNER JOIN grades g ON u.id = g.student_id
    WHERE u.role = 'student'
    GROUP BY YEAR(g.created_at)
    ORDER BY YEAR(g.created_at)
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