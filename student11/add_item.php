<?php
ini_set("session.cookie_domain", ".cis355.com");//should be done in the .htaccess file 
session_start();
$current_user_described_by_email = $_SESSION["user"];
$current_user_id = $_SESSION["id"];
$current_users_location = $_SESSION["location"];
include("functions.php");
connect();


echo "<!DOCTYPE html>
<html lang='en'> 
<head>
      <script src ='add_item.js'></script>

</head>
<body>         
          <form role='form' style='min-width: 400px;'>
            <div class='form-group'>
                <label for='jewelryName'>Type of Jewelry</label>
                <input type='text' class='form-control' name = 'jewelry_name' id='jewelryName'placeholder='Type of Jewelry' required>
                <label for='quality'>Quality</label>
                <input type='text' class='form-control' name = 'quality' id='quality'placeholder='Quality' required=>
                <label for='price'>Price Paid</label>
               <input type='number' class='form-control 'name='price' id='price' placeholder='Price Paid' required> 
                <label for='discounts'>Discounts</label>
                <input type='number' class='form-control' name = 'discounts' id='discounts' placeholder='Enter Amount of Discount' required>
                <label for='location_id'>Location Bought</label>";
                $sql_statement = $conn_obj->stmt_init();
                if($sql_statement = $conn_obj->prepare("SELECT * FROM locations")){
                  $sql_statement->execute();
                  $sql_statement->bind_result($id,$location_name);
                  echo "<select class='form-control' name = 'location_id' id='location'>";
                  while($sql_statement->fetch()){
                    if($id ===$current_users_location){
                      echo"<option value='$id' selected='selected'>$location_name</option>";
                    }
                    else{
                      echo"<option value='$id' >$location_name</option>";
                    }
                  }
                  echo "</select>";
                  
                }
                echo"<label for='user_id' style='display:none'>Current user (read only)</label>
                <input type='number'  style='display:none'class='form-control' name = 'user_id' id='user_id' value='$current_user_id' readonly>
            </div>
            <button type='submit'  id='submit'  class='btn btn-default'>Add Item</button>

          </form>
</body>
</html>"
?>
