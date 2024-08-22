<?php
// Include database connection file
include './database/database.php';

$conn = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['button_id'];
    $button_name = $_POST['button_name'];
    $button_state = $_POST['button_state'];

    $sql_check = "SELECT * FROM `buttons_status` WHERE `id` = '$id'";
    $result = $conn->query($sql_check);
    $row = $result->rowCount();
    if ($id < 1 & $id > 32) {
        echo "<script>alert('Error: Invalid device id. Please enter a number between 1 and 32');</script>";
    } else if ($row > 0) {
        echo "<script>alert('Error: duplicate device id. Please enter a unique device id');</script>";
    }
    else {
        // Insert data into button table
        $sql = "INSERT INTO `buttons_status` (id, button_name, button_state, time, date) 
                VALUES ('$id', '$button_name', '$button_state', NOW(), NOW())";
        $stmt = $conn->prepare($sql);
    
        if ($stmt->execute()) {
            // Redirect to timer list page with success message
            header("Location: button_list.php?status=success");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $stmt->close();
    }
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<body>
<div class="d-flex">
    <?php include("./dist/partials/sidebar.php"); ?>
    <div class="col">
        <div class="text-center bg-title p-4 mb-4"><h2><b>Add new device</b></h2></div>
        <div class="container">
            <form action="addDeviceForm.php" method="post">
                <div class="form-group">
                    <label for="button_id">Device id:</label>
                    <input type="text" id="button_id" name="button_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="button_name">Device Name:</label>
                    <input type="text" id="button_name" name="button_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="time_update">Device state:</label>
                    <input type="text" id="button_state" name="button_state" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Device</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>