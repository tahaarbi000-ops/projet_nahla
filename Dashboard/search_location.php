<?php
require_once("../connect.php");

$query = $_GET['q'] ?? '';

if($query != '') {
    // Escape the input to prevent SQL injection
    $queryEscaped = mysqli_real_escape_string($conn, $query);

    // Search admins by first or last name
    $sql = "SELECT description, type,adresse,prix,classification,id
            FROM location 
            WHERE prop_id = $query
            AND etat = 'libre'
             ";

    $result = mysqli_query($conn, $sql);

    $admins = [];
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $location[] = $row;
        }
    }

    echo json_encode($location);
}
?>
