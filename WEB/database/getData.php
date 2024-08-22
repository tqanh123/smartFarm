<?php
  include 'database.php';
  $conn = Database::connect();
  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    // keep track post values
    $id = $_POST['id'];
    
    $myObj = (object)array();
    
    //........................................ 
    $sql = 'SELECT * FROM replace_with_your_table_name WHERE id="' . $id . '"';
    foreach ($conn->query($sql) as $row) {
      $myObj->id = $row['id'];
      $myObj->status = $row['led_state'];
      
      $myJSON = json_encode($myObj);
      
      echo $myJSON;
    }
    Database::disconnect();
    //........................................ 
  }
?>