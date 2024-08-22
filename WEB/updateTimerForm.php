<?php
require_once './database/database.php';
if (isset($_POST['button_timer'])) {
    $button_id = $_POST['button_timer'];
    $timer_id = $_POST['timer_id'];

    $conn = Database::connect();
    $sql = "INSERT INTO `T-B` (`button_id`, `timer_id`) VALUES ('$button_id', '$timer_id')";
    $stmt = $conn->prepare($sql);

    if($stmt->execute()){
        header('Location: updateTimerForm.php?id=' . $timer_id);
        exit();
    }
    else echo '<div class="alert alert-danger" role="alert">Button added successfully!</div>';
}

if (isset($_POST['state'])) {
    $id = $_POST['id'];
    $state = $_POST['state'];
    $name = $_POST['name'];
    $time = $_POST['time'];

    $conn = Database::connect();
    $sql = "UPDATE `Timer` SET `state_update` = '$state', `timer_name` = '$name', `time_update` = '$time' WHERE `id` = '$id'";
    $stmt = $conn->prepare($sql);

    if($stmt->execute()){
        header('Location: Timer_list.php?status=success');
        exit();
    }
    else echo '<div class="alert alert-danger" role="alert">Timer state updated successfully!</div>';
}

$id = $_GET['id'];
$conn = Database::connect();
$sql = "SELECT * FROM Timer WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$Timer = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<body>
<div class="d-flex">
    <?php include("./dist/partials/sidebar.php"); ?>
    <div class="col">
        <div class="text-center bg-title p-4 mb-4"><h2><b>Update Timer</b></h2></div>
        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="a nav-link active" href="#updateTimer">Update timer</a>
                        </li>
                        <li class="nav-item">
                            <a class="a nav-link" href="#updateButton">Update devices</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div id="updateButton" hidden class="tab-pane">
                        <?php
                        $conn = Database::connect();
                        $sql = "SELECT * FROM `T-B` WHERE `timer_id` = '$id'";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $buttons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Device ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($buttons as $button) { ?>
                                    <tr>
                                        <td><?php echo $button['button_id']; ?></td>
                                        <td>
                                            <form method="POST" action="deleteButton.php">
                                                <input type="hidden" name="id" value="<?php echo $button['id']; ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <?php
                        $sql = "SELECT * FROM `buttons_status` 
                                WHERE `id` NOT IN (SELECT `button_id` FROM `T-B` WHERE `timer_id` = '$id')";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $buttons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <form method="POST" action="updateTimerForm.php">
                            <select name="button_timer" id="button_timer">
                            <?php foreach ($buttons as $button) { ?>
                                <option value="<?php echo $button['id']; ?>"><?php echo $button['button_name']; ?></option>
                            <?php } ?>
                            </select> <!-- Add this closing tag -->
                            <input type="hidden" name="timer_id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-primary">Add New Device</button>
                        </form>
                    </div>    

                    <div class="tab-pane" id="updateTimer">
                        <form method="POST" action="updateTimerForm.php">
                            <div class="form-group">
                                <label for="TimerId">Timer ID</label>
                                <input type="text" class="form-control" id="TimerId" name="id" value="<?php echo $Timer['id']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="TimerState">New state update</label>
                                <input type="text" class="form-control" id="TimerState" name="state" value="<?php echo $Timer['timer_state']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="TimerState">New name</label>
                                <input type="text" class="form-control" id="TimerName" name="name" value="<?php echo $Timer['timer_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="TimerState">New update time</label>
                                <input type="time" class="form-control" id="TimerUpdate" name="time" value="<?php echo $Timer['time_update']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
    // Select all nav items
    var navItems = document.querySelectorAll('.nav-item .nav-link');

    // Add click event listener to each nav item
    navItems.forEach(function(navItem) {
        navItem.addEventListener('click', function(event) {
            // Remove active class from all nav items
            navItems.forEach(function(navItem) {
                navItem.classList.remove('active');
            });

            // Add active class to clicked nav item
            event.target.classList.add('active');
        });
    });

    window.addEventListener( 'popstate', function(event) {
        var tabPanes = document.querySelectorAll('.tab-pane');

        // Remove active class from all nav items
        tabPanes.forEach(function(tab) {
            tab.removeAttribute('hidden');
        });

        // select element with id
        if (window.location.hash === '#updateButton') {
            var specificTab = document.getElementById('updateTimer');
        }
        if (window.location.hash === '#updateTimer') {
            var specificTab = document.getElementById('updateButton');
        }

        // add active class for specific element
        specificTab.setAttribute('hidden', 'true');;
    });  
</script>
</body>
</html>