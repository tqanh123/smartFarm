<html lang="en">
<?php include("./dist/partials/head.php"); ?>
<?php require './database/database.php'; ?>

<body>
    <!-- <?php include("./dist/partials/nav.php"); ?> -->
    <div class="d-flex">
        <?php include("./dist/partials/sidebar.php"); ?>
        <div class="col">
            <div class="text-center bg-title p-4 mb-4"><h2><b>Dashboard</b></h2></div>
            <!-- SQL select -->
            <?php
                $conn = Database::connect();
                $sql = "SELECT * FROM Buttons_status Where button_state='ON'";
                $result = $conn->query($sql);
                $button_on = $result->rowCount();

                $sql = "SELECT * FROM weather_conditions";
                $result = $conn->query($sql);
                $weather_conditions = $result->fetch();
                $temp = $weather_conditions['Temperature'];
                $hum = $weather_conditions['Humidity'];
                $light = $weather_conditions['light'];
                $soil = $weather_conditions['soil_moisture'];
            ?>
            
            <div class="row py-4 ">
                <div class="d-flex justify-content-evenly">
                    <div class="db-state py-4">
                        <i class="db-icon px-1 fa-solid fa-toggle-on px-1"></i>
                        <div class="px-1">
                            <h3> <?php echo $button_on; ?> </h3>
                            <p>Button on</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-4 ">
                <div class="d-flex justify-content-evenly">
                    <div class="db-state py-4">
                        <i class="db-icon px-1 fa-solid fa-temperature-high"></i>
                        <div class="px-1">
                            <h3> <?php echo $temp; ?> </h3>
                            <p>Temperature</p>
                        </div>
                    </div>
                    <div class="db-state py-4">
                        <i class="db-icon px-1 fa-solid fa-droplet"></i>
                        <div class="px-1">
                            <h3> <?php echo $hum; ?> </h3>
                            <p>Humidity</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-4 ">
                <div class="d-flex justify-content-evenly">
                    <div class="db-state py-4">
                        <i class="db-icon px-1 fa-solid fa-sun"></i>
                        <div class="px-1">
                            <h3> <?php echo $light; ?> </h3>
                            <p>Light Intensive</p>
                        </div>
                    </div>
                    <div class="db-state py-4">
                        <i class="db-icon px-1 fa-solid fa-water"></i>
                        <div class="px-1">
                            <h3> <?php echo $soil; ?> </h3>
                            <p>Soil Moisture</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap.min.js"></script>
</body>
</html>