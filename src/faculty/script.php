<?php 
    $crat = "SELECT id, FirstName, LastName, created_at 
        FROM users 
        WHERE role='student',
    ";

    $studCount = "SELECT COUNT(id)
        FROM users
        WHERE role='student'
    ";

    $res1 = $conn->query($crat); 
    $count = []; 

    while($row = $res1->fetch_assoc()){
    array_push($data, $row); 

    }

    echo json_encode($data); 
?>