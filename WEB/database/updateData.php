<?php
require './database.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['temperature']) && isset($data['humidity']) && isset($data['soilMoisture']) && isset($data['lightIntensity'])) {
    $id = $data['id'];
    $temperature = $data['temperature'];
    $humidity = $data['humidity'];
    $soilMoisture = $data['soilMoisture'];
    $lightIntensity = $data['lightIntensity'];

    // Connect to the database
    $conn = Database::connect();

    // Insert or update the weather data
    $sql = "UPDATE `weather_conditions` 
            SET `Temperature` = '$temperature', `Humidity` = '$humidity', `soil_moisture` = '$soilMoisture' , `light` = '$lightIntensity' 
            WHERE `id` = '$id'";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo json_encode(["status" => "SUCCESS", "message" => "Weather data updated successfully"]);
    } else {
        echo json_encode(["status" => "FAILED", "message" => "Error updating weather data"]);
    }

    Database::disconnect();
} else {
    echo json_encode(["status" => "FAILED", "message" => "Invalid input"]);
}
?>