<?php 
    include_once '../../includes/db_connection.php';

    $query = "
    SELECT YEAR(created_at) AS year, COUNT(id) AS users
    FROM users
    WHERE role = 'student'
    GROUP BY YEAR(created_at)
    ORDER BY YEAR(created_at)
    ";

$result = $conn->query($query);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

    echo json_encode($data); 
?>