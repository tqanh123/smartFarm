<?php
require './database/database.php';

if (isset($_POST['timer_id']) && isset($_POST['timer_state'])) {
    $id = $_POST['timer_id'];
    $state = $_POST['timer_state'];
    // Connect to the database
    $conn = Database::connect();

    // Update the database
    $sql = "UPDATE `Timer` SET `timer_state` = '$state' WHERE `id` = '$id'";
    $stmt = $conn->prepare($sql);           
    if ($stmt->execute()) {
        echo json_encode(["Timer ID" => $id, " Timer State" => $state]);
    } else {
        echo "Error updating Button state";
    }

    Database::disconnect();
    header("Location: Timer_list.php"); // Redirect back to the button page
    exit();
}

if (isset($_POST['id']) && isset($_POST['state'])) {
    $id = $_POST['id'];
    $state = $_POST['state'];

    // Connect to the database
    $conn = Database::connect();

    // Update the database
    $sql = "UPDATE `Buttons_status` SET `button_state` = '$state', `time` = NOW(), `date` = NOW() WHERE `id` = '$id'";
    $stmt = $conn->prepare($sql);           
    if ($stmt->execute()) {
        echo json_encode(["Button ID" => $id, "Button State" => $state]);
    } else {
        echo "Error updating Button state";
    }

    Database::disconnect();
    header("Location: button.php"); // Redirect back to the button page
    exit();
}


?>