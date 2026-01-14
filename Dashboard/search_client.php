<?php
require_once("../connect.php");

$query = $_GET['q'] ?? '';

if($query != '') {
    // Escape the input to prevent SQL injection
    $queryEscaped = mysqli_real_escape_string($conn, $query);

    // Search admins by first or last name
    $sql = "SELECT nom, prenom,cin,id
            FROM users 
            WHERE role='client' AND (nom LIKE '%$queryEscaped%' OR prenom LIKE '%$queryEscaped%') 
            LIMIT 10";

    $result = mysqli_query($conn, $sql);

    $admins = [];
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $client[] = $row;
        }
    }

    echo json_encode($client);
}
?>
