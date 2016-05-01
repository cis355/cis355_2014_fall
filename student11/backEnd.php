<?php
    $server="localhost";
    $loginName="user01";
    $password="cis355lwip";
    $db_handle="lwip";
    $table="table11";
    $conn_obj = new mysqli($server,$loginName,$password,$db_handle);

  foreach($_GET as $key=>$value)
  {
    $$key=$value;
  }
  connect();  
  write_to_db();
  get_new_record_for_ajax_request();
  
  //checks to see if there is a connection error 
  function connect(){
    if($conn_obj->connect_error){
      echo "Could not make connection at point 1 ERROR: ".$conn_obj->connect_error ;
      die;
    }
  }
  //this function writes to the database 
  function write_to_db(){
      global $jewelry_name,$quality,$price,$discounts,$user_id,$location_id,$table,$conn_obj;
      $sql_statement = $conn_obj->stmt_init();
      if($sql_statement = $conn_obj->prepare("INSERT INTO $table (jewlery_type,quality,price_paid,discount,user_id,location_id) VALUES (?,?,?,?,?,?)")){
        $sql_statement->bind_param('ssiiii',$jewelry_name,$quality,$price, $discounts,$user_id,$location_id);
        $sql_statement->execute();
        $sql_statement->close();
      }else{
         echo "Error on connection point 2 ERROR: ".$sql_statement->error;
      }
  }
  //this function inserts the record into the database and then
  //sends back a formatted row using json
  function get_new_record_for_ajax_request(){
    global $conn_obj,$table;
    $sql_statement = $conn_obj->stmt_init();
    if($sql_statement = $conn_obj->prepare("SELECT id, jewlery_type,quality, price_paid,discount,user_id,location_id,date_purchased FROM $table WHERE id = LAST_INSERT_ID()")){
      $sql_statement->execute();
      $sql_statement->bind_result($id,$jewlery_type,$quality, $price_paid,$discount,$user_id,$location_id,$date_purchased);
      while($sql_statement->fetch()){
      //formats the date
      $date_purchased = date("m/d/Y", strtotime($date_purchased));
      //creates the row to be sent back based on the data placed into the database
      $table_row_boiler_plate = "<tr>
	  <input type='hidden' id='id' value='$id'> 
          <td class='jewlery_type'>$jewlery_type</td>
          <td class='quality'>$quality</td>
          <td class='price_paid'>$price_paid</td>
          <td class='discount'>$discount</td>
          <td class='user_id'>$user_id</td>
          <td class='location_id'>$location_id</td>
          <td class='date_purchased'value='$date_purchased'> <a class='delete_item btn btn-primary disable' href='#'role='button'>Remove Item  </a> <a class='update_item btn btn-primary disable' href='#'role='button'>Update Item  </a></td>
        </tr>";
      }
      echo json_encode($table_row_boiler_plate);
    }
  }
?>
