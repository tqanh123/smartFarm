<?php
require_once 'database.php'; // Include the Database class

$conn = Database::connect();

// Get the current time
$current_time = date('H:i:s');

// Fetch timers where the current time matches time_update
$sql = "SELECT tb.button_id, t.state_update 
        FROM Timer t 
        JOIN `T-B` tb ON t.id = tb.timer_id 
        JOIN buttons_status b ON tb.button_id = b.id 
        WHERE t.time_update = :current_time AND t.timer_state = 'ON';";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':current_time', $current_time);
$stmt->execute();
$timers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update the button_state for each matching timer
foreach ($timers as $timer) {
    $sql_update = "UPDATE buttons_status 
                   SET button_state = :state_update 
                   WHERE id = :button_id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':state_update', $timer['state_update']);
    $stmt_update->bindParam(':button_id', $timer['button_id']);
    $stmt_update->execute();
}

echo "Button states updated successfully.";

$conn = null;
?>