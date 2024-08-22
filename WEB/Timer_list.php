<?php
require_once './database/database.php';

$conn = Database::connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM `Timer` WHERE `id` = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    header('Location: Timer_list.php');
    exit();
}

$sql = "SELECT * FROM Timer";
$result = $conn->query($sql);
$timers = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./dist/partials/head.php"); ?>

<body>
<div class="d-flex">
    <?php include("./dist/partials/sidebar.php"); ?>
    <div class="col">
        <div class="text-center bg-title p-4 mb-4"><h2><b>Timers List</b></h2></div>
        <div class="container">
            <div id="status-alert" class="alert alert-success" role="alert">
                <!-- Status messages will be displayed here -->
                Succes Timer added!
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timer Name</th>
                        <th>Timer State</th>
                        <th>Update time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timers as $timer): ?>
                        <tr>
                            <td><?php echo $timer['id']; ?></td>
                            <td><?php echo $timer['timer_name']; ?></td>
                            <td>
                            <label class="switch m-2">
                                    <input type="checkbox" class="button-toggle" data-id="<?php echo $timer['id']; ?>"  <?php echo ($timer['timer_state'] == 'ON') ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>    
                            <?php  ?>
                            </td>
                            <td><?php echo $timer['time_update']; ?></td>
                            <td>
                                <a href="updateTimerForm.php?id=<?php echo $timer['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="Timer_list.php?id=<?php echo $timer['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="addTimerForm.php" class="btn btn-success">Add New Timer</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.querySelectorAll('.button-toggle');
        
        buttons.forEach(function(button) {
            button.addEventListener('change', function() {
                var buttonId = this.getAttribute('data-id');
                var buttonState = this.checked ? 'ON' : 'OFF';
                
                // alert('Button ' + buttonId + ' state: ' + buttonState);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', './updateButton.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Button state updated successfully');
                        } else {
                            alert('Error updating button state: ' + xhr.statusText);
                        }
                    }
                };
                xhr.send('timer_id=' + buttonId + '&timer_state=' + buttonState);
            });
        });
    });

    // Hide the alert after 3 seconds
    setTimeout(function() {
        var alert = document.getElementById('status-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000);
</script>
</body>
</html>