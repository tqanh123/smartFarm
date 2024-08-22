<?php
require_once './database/database.php';

$conn = Database::connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM `Buttons_status` WHERE `id` = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    header('Location: button_list.php');
    exit();
}

$sql = "SELECT * FROM Buttons_status";
$result = $conn->query($sql);
$buttons = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./dist/partials/head.php"); ?>

<body>
<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'success'): ?>
        <div class="alert alert-success" role="alert" id="status-alert">
            Device state updated successfully!
        </div>
    <?php endif; ?>
<?php endif; ?>
<div class="d-flex">
    <?php include("./dist/partials/sidebar.php"); ?>
    <div class="col">
    <div class="text-center bg-title p-4 mb-4"><h2><b>Devices List</b></h2></div>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Device Name</th>
                        <th>Device State</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($buttons as $button): ?>
                        <tr>
                            <td><?php echo $button['id']; ?></td>
                            <td><?php echo $button['button_name']; ?></td>
                            <td><?php echo $button['button_state']; ?></td>
                            <td>
                                <a href="updateButtonForm.php?id=<?php echo $button['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="button_list.php?id=<?php echo $button['id']; ?>" class="btn btn-danger">Delete</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="./addDeviceForm.php" class="btn btn-success">Add New Device</a>
        </div>
    </div>
    <script>
    // Hide the alert after 3 seconds
    setTimeout(function() {
        var alert = document.getElementById('status-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000);
</script>
</div>
</body>
</html>