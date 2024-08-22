<?php
// Include database connection file
include './database/database.php';

$conn = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $timer_name = $_POST['timer_name'];
    $timer_state = $_POST['timer_state'];
    $state_update = $_POST['state_update'];
    $time_update = $_POST['time_update'];

    // Insert data into Timer table
    $sql = "INSERT INTO Timer (timer_name, timer_state, state_update, time_update) 
            VALUES ('$timer_name', '$timer_state', '$state_update', '$time_update')";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        // Redirect to timer list page with success message
        header("Location: Timer_list.php?status=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<body>
<div class="d-flex">
    <?php include("./dist/partials/sidebar.php"); ?>
    <div class="col">
        <div class="text-center bg-title p-4 mb-4"><h2><b>Add new timer</b></h2></div>
        <div class="container">
            <form action="addTimerForm.php" method="post">
                <div class="form-group">
                    <label for="timer_name">Timer Name:</label>
                    <input type="text" id="timer_name" name="timer_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="text" id="timer_state" name="timer_state" class="form-control" hidden value="ON">
                </div>
                <div class="form-group">
                    <label for="state_update">State Update:</label>
                    <input type="text" id="state_update" name="state_update" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="time_update">Time Update:</label>
                    <input type="time" id="time_update" name="time_update" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Timer</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>