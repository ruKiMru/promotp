<?php

include 'database.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch client data from the database
$sql = "SELECT id_client, first_name, middle_name, last_name FROM clients WHERE deleted = 0";
$result = $conn->query($sql);

// Create an array to store client data
$clients = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Send the client data as JSON
header('Content-Type: application/json');
echo json_encode($clients);

// Close the database connection
$conn->close();
?>
