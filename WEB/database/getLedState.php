<?php
require_once 'database.php'; // Include the Database class

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);  

// Read the raw POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!empty($data) && isset($data['id'])) {
    // Connect to the database
    $conn = Database::connect();
    if (!$conn) {
        die(json_encode(array("status" => "Connection failed: " . mysqli_connect_error())));
    }
    
    $id = $data['id'];
    // echo $id;
    $myObj = (object)array();
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT led_state FROM leds_status WHERE id = '$id'");
    $stmt->execute();
    
    $stmt->bindColumn('led_state', $ledState);
    if ($stmt->fetch()) {
        echo $ledState;
    } else {
        echo "ID not found";
    }
    
    Database::disconnect();
    
    // Return the JSON response
    // echo json_encode($myObj);
} else {
    // echo json_encode(array("status" => "No POST data received"));
    echo "No POST data received";
}
?>