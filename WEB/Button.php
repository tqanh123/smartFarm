<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<?php require './database/database.php'; ?>

<body>
    <!-- <?php include("./dist/partials/nav.php"); ?> -->
    <div class="d-flex">
        <?php include("./dist/partials/sidebar.php"); ?>
        <div class="col" >
        <div class="text-center bg-title p-4 mb-4"><h2><b>Control Devices</b></h2></div>
        <div class="row">
            <div class="d-flex justify-content-sm-evenly flex-wrap">
                <?php
                // Assuming you have fetched the data from the database and stored it in an array called $relays
                $conn = Database::connect();
                $sql = "SELECT * FROM Buttons_status";
                $result = $conn->query($sql);
                $buttons = $result->fetchAll(PDO::FETCH_ASSOC);
                foreach ($buttons as $button) {
                    $id = $button['id'];
                    $name = $button['button_name'];
                    $state = $button['button_state'];
                ?>
                    <div class="button_state d-flex bg-state m-4 py-3">
                        <div>
                            <h5 class="px-2">Button <?php echo $id; ?></h5>
                            <p><?php echo $name; ?></p>
                        </div>
                        <div>
                            <label class="switch m-2">
                                <input type="checkbox" class="button-toggle" data-id="<?php echo $id; ?>" <?php echo ($state == 'ON') ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var buttons = document.querySelectorAll('.button-toggle');
            
            buttons.forEach(function(button) {
                button.addEventListener('change', function() {
                    var buttonId = this.getAttribute('data-id');
                    var buttonState = this.checked ? 'ON' : 'OFF';
                    
                    // check values
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
                    xhr.send('id=' + buttonId + '&state=' + buttonState);
                });
            });
        });
</script>
</body>

</html>