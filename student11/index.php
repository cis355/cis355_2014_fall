<?php
ini_set("session.cookie_domain", ".cis355.com");//should be done in the .htaccess file 
session_start();
$current_user_described_by_email = $_SESSION["user"];
$current_user_id = $_SESSION["id"];
$current_users_location = $_SESSION["location"];
//seeing if this is an ajax request 
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  if(isset($_POST['delete'])){//deleting the record if it is a delete ajax request
    include("functions.php");
    global $conn_obj,$table;
    connect();// checks connection to the database
    //need to bind param here too
    $sql_statement = $conn_obj->stmt_init();
    if($sql_statement = $conn_obj->prepare("DELETE FROM $table WHERE id =$_POST[jew_id]")){
      $sql_statement->execute();
      $sql_statement->close();
      echo "$_POST[delete]  $_POST[jew_id]";
    }
  } 
  if(isset($_GET['update'])){//updating the record if it is a update ajax request
    include("functions.php");
    global $conn_obj,$table;
    connect();// checks connection to the database
    //TODO: Bind params when i can remember
    $sql_statement = $conn_obj->stmt_init();
    if($sql_statement = $conn_obj->prepare("UPDATE $table SET jewlery_type ='$_GET[jewelry_name]',quality ='$_GET[quality]',price_paid ='$_GET[price]',discount ='$_GET[discounts]',user_id='$_GET[user_id]',location_id='$_GET[location_id]' WHERE id =$_GET[jew_id]")){
      $sql_statement->execute();
      $sql_statement->close();
      // echo$sql_statement->affected_rows;

    }
    $sql_statement2 = $conn_obj->stmt_init();
    //bind params when can remember
    if($sql_statement2 = $conn_obj->prepare("SELECT id,jewlery_type,quality,price_paid,discount,user_id,location_id FROM $table WHERE id = $_GET[jew_id]")){
      $sql_statement2->execute();
      $sql_statement2->bind_result($id,$jewlery_type,$quality,$price_paid,$discount,$user_id,$location_id);
      $sql_statement2->fetch();
      $table_row_boiler_plate = "<tr>
        <input type='hidden' id='id' value='$id'> 
            <td class='jewlery_type'>$jewlery_type</td>
            <td class='quality'>$quality</td>
            <td class='price_paid'>$price_paid</td>
            <td class='discount'>$discount</td>
            <td class='user_id'>$user_id</td>
            <td class='location_id'>$location_id</td>
            <td class='date_purchased' ><a class='update_item btn btn-primary disable' href='#'role='button'>Update Item  </a></td>
          </tr>";
          $sql_statement2->close();
      echo($table_row_boiler_plate);
    }else{
         echo "Error on connection point 2 ERROR: ".$sql_statement->error;
      }
  }
}
else{//not an ajax request
  //including external functions
  //and makes the connection to the database
  // echo"<p> User:".$current_user_described_by_email." Id: ".$current_user_id."location".$current_users_location."</p>";
  $logo_bottom_div_content = "";
  if(empty($current_user_id)){//if the user is not logged in display link to log in
    $logo_bottom_div_content ="<span class='login_prompt'><p><a  href='//www.cis355.com/student14/landing.php'>Login</a> <span class='access_features'>to access features.</span></p></span>";
  }else{//display the user's id with a welcome message
    $logo_bottom_div_content ="<span class='top-border-user-content'><p class='user_name'>Hello $current_user_described_by_email </br><a class='not_you_link' href='//www.cis355.com/student14/landing.php'>NOT YOU?</a></p></span>";
  }
  include("functions.php");
  connect();// checks connection to the database
  echo "<!DOCTYPE html>
  <html lang='en'>
    <head>
      <meta charset='utf-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>LWIP-Jewelry</title>
      <!-- bootstrap widget theme -->
      <link rel='stylesheet' href='tablesorter-master/css/theme.dropbox.css'>
      <link rel='stylesheet'href='index.css'>
      <!-- <script scr = 'colorbox-master/jquery.json'></script> -->
      <!-- <script src = 'colorbox-master/colorbox.ai'></script> -->
      <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
      <link rel='stylesheet' type='text/css' href='colorbox-master/example1/colorbox.css' />
      <script src = 'colorbox-master/jquery.colorbox.js'></script>
      <script src ='index.js'></script>
      <script src ='functions.php'></script>
      <script src='tablesorter-master/js/jquery.tablesorter.js' type='text/javascript'/>
      </script>
      <!-- tablesorter widget file - loaded after the plugin -->
      <script src='tablesorter-master/js/jquery.tablesorter.widgets.js'></script>
      <script src='backEnd.php'></script>
       <script type='text/javascript' src='http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.min.js'></script>
      <link href='//ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/ui-lightness/jquery-ui.css' rel='stylesheet' type='text/css' />
      <!-- Latest compiled and minified CSS -->
      <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
     <!-- Optional theme -->
      <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
        <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
      <![endif]-->
      <!-- Latest compiled and minified JavaScript -->
      <script src='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>

  <body>
  <div class='container-fluid col-md-12 top-border' >
      <a href='//www.cis355.com/student14/landing.php'><img src='//www.cis355.com/student14/LWIP_logo.png' style='margin-top: 5px;'></a>
        $logo_bottom_div_content
    </div>
    <div class='row-fluid'>
      <div class='col-sm-9 col-md-9 ' >
        <!--Body content-->
       <ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
         <li class='active'><a href='#tabs-1' data-toggle='tab'>Jewelry-Add/Remove</a></li>
         <li><a href='#tabs-2' data-toggle='tab'>Jewelry-Update/Comments</a></li>
       </ul>
       <div id='my-tab-content' class='tab-content'>
       <div class='tab-pane active' id='tabs-1'>
       <table id='myTable' class='tablesorter '> 
          <thead> 
          <tr> 
              <th>Type of Jewelry</th> 
              <th>Quality</th> 
              <th>Price Paid</th> 
              <th>Discounts</th>
              <th>Purchaser</th>
              <th>Location Bought</th>";
              if(!empty($current_user_id)){
                echo" <th>Action</th>"; 
              }
          echo "</tr> 
          </thead> 
          <tbody class='main_table_body'> ";
          construct_data_table_from_database_main_pane($current_user_described_by_emai,$current_user_id,$current_users_location);
        echo "</tbody> 
        </table> ";
        if(!empty($current_user_id)){
           echo "<a class='add_item btn btn-primary disable'href = 'add_item.php' role='button'>Add Item</a>";
	}  
	echo "<a class='bio btn btn-primary disable' href = 'bio.html'role='button' >Bio </a>";
       echo "</div><!--end of tab 1-->
        <div class='tab-pane' id='tabs-2'>
        <form>
        <table id='myTable2' class='tablesorter'> 
          <thead> 
          <tr> 
              <th>Type of Jewelry</th> 
              <th>Quality</th> 
              <th>Price Paid</th> 
              <th>Discounts</th>
              <th>Purchaser</th>
              <th>Location Bought</th>";
              if(!empty($current_user_id)){
                echo" <th>Action</th>"; 
              }
          echo "</tr> 
          </thead> 
          <tbody class='update_table_body'>";
          construct_data_table_from_database_update_pane($current_user_described_by_emai,$current_user_id,$current_users_location);
         echo "</tbody>
          </table>
          <!--Hidden form for colorbox-->
          <div style='display:none'>
            <form role='form'  >
              <div class='form-group' style='min-width: 400px;'>
                  <label for='jewelryName'>Type of Jewelry</label>
                  <input type='text' class='form-control' name = 'jewelry_name' id='jewName'placeholder='Type of Jewelry' required>
                  <label for='quality'>Quality</label>
                  <input type='text' class='form-control' name = 'quality' id='qual' placeholder='Quality' required=>
                  <label for='price'>Price Paid</label>
                  <input type='number' class='form-control 'name='price' id='pri' placeholder='Price Paid' required> 
                  <label for='discounts'>Discounts</label>
                  <input type='number' class='form-control' name = 'discounts' id='discnt' placeholder='Enter Amount of Discount' required>
                  <label for='location'>Location Bought</label>";
                  $sql_statement = $conn_obj->stmt_init();
                  if($sql_statement = $conn_obj->prepare("SELECT * FROM locations")){
                    $sql_statement->execute();
                    $sql_statement->bind_result($id,$location_name);
                    echo "<select class='form-control' name = 'location_id' id='location_id' val='1'>";
                    while($sql_statement->fetch()){
                      echo"<option value='$id' >$location_name</option>";
                    }
                    echo "</select>";
                    
                  }
                  $sql_statement->close();
                  echo"<input type='hidden' id='purchased_by'>
                  <input type='hidden' id='date_reported'>
              </br>
              <button type='submit'  id='submit_button'  class='btn btn-default'>Update Item</button>
              </div>
            </form>
          </div>
          </div><!--end of tab 2-->
         </div><!--end of tab content pane-->
        </div><!-- end of second column-->
      </div><!--row-->
    </div> <!--main container-->
    


  </body>


  </html> ";
}


?>
