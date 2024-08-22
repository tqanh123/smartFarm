<?php
require_once './database/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $state = $_POST['state'];
    $name = $_POST['name'];

    $conn = Database::connect();
    $sql = "UPDATE `buttons_status` SET `button_state` = :state, `button_name` = :name WHERE `id` = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);
    if($stmt->execute()){
        header('Location: button_list.php?status=success');
        exit();
    }
    else echo '<div class="alert alert-danger" role="alert">Button state updated successfully!</div>';
}

$id = $_GET['id'];
$conn = Database::connect();
$sql = "SELECT * FROM Buttons_status WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$button = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<body>
<div class="d-flex">
    <?php include("./dist/partials/sidebar.php"); ?>
    <div class="col">
        <div class="text-center bg-title p-4 mb-4"><h2><b>Update Device</b></h2></div>
        <div class="container mt-5">
            <form method="POST" action="updateButtonForm.php">
                <div class="form-group">
                    <label for="buttonId">Device ID</label>
                    <input type="text" class="form-control" id="buttonId" name="id" value="<?php echo $button['id']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="buttonState">New State</label>
                    <input type="text" class="form-control" id="buttonState" name="state" value="<?php echo $button['button_state']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="buttonState">New Name</label>
                    <input type="text" class="form-control" id="buttonName" name="name" value="<?php echo $button['button_name']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>