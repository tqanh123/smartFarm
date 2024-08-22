<?php
require_once 'database.php'; // Include the Database class

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);  

// Read the raw POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!empty($data)) {
    // Connect to the database
    $conn = Database::connect();
    if (!$conn) {
        die(json_encode(array("status" => "Connection failed: " . mysqli_connect_error())));
    }
    
    $stmt = $conn->prepare("SELECT * FROM buttons_status");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " - Button State: " . $row['button_state'] . "<br>";
    }
    
    Database::disconnect();
    
    // Return the JSON response
    // echo json_encode($myObj);
} else {
    // echo json_encode(array("status" => "No POST data received"));
    echo "No POST data received";
}
?>