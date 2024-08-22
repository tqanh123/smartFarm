<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<?php require './database/database.php'; ?>

<body>
    <!-- <?php include("./dist/partials/nav.php"); ?> -->
    <div class="d-flex">
        <?php include("./dist/partials/sidebar.php"); ?>
        <div class="col py-3">
            <div class="text-center h-4"><h4><b>On Off Led</b></h4></div>
            <div class="row">
                <div class="d-flex justify-content-sm-evenly py-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Led</h5>
                            <form id="ledForm">
                                <label class="switch">
                                    <?php
                                    // Fetch the LED status from the database
                                    $conn = Database::connect();
                                    $sql = "SELECT led_state FROM leds_status";
                                    $result = $conn->query($sql);
                                    $ledStatus = 0; // Default to off
                                    if ($result->rowCount() > 0) {
                                        $row = $result->fetch(PDO::FETCH_ASSOC);
                                        $ledStatus = $row['led_state'] == 'ON' ? 1 : 0;
                                    }
                                    Database::disconnect();
                                    ?>
                                    <input type="checkbox" id="ledCheckbox" <?php echo $ledStatus ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php include("./dist/partials/footer.php"); ?>
        </div>
    </div>

    <script type="text/javascript" src="dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var ledCheckbox = document.getElementById('ledCheckbox');
            ledCheckbox.addEventListener('change', function() {
                var ledState = ledCheckbox.checked ? 'ON' : 'OFF';
                // alert('LED state: ' + ledState);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', './database/updateLedState.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    console.log('Response status:', xhr.status); // Log the response status
                        if (xhr.status === 200) {
                            console.log('LED state updated:', xhr.responseText); // Log the response text
                        } else {
                            console.error('Error updating LED state:', xhr.responseText); // Log any errors
                        }
                };
                xhr.send('status=' + ledState);
            });
        });
    </script>
</body>
</html>