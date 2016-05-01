<?php
  $server="localhost";
  $loginName="user01";
  $password="cis355lwip";
  $db_handle="lwip";
  $table="table11";
  $conn_obj = new mysqli($server,$loginName,$password,$db_handle);
  
  function connect(){
    if($conn_obj->connect_error){
      echo "Could not make connection at point 1 ERROR: ".$conn_obj->connect_error ;
      die;
    }
  }

  function construct_data_table_from_database_main_pane($current_user_described_by_emai,$current_user_id,$current_users_location){
    global $conn_obj,$table;
    $sql_statement = $conn_obj->stmt_init();
    if($sql_statement = $conn_obj->prepare("SELECT id,jewlery_type,quality, price_paid,discount,date_purchased,user_id,location_id FROM $table")){
      $sql_statement->execute();
      $sql_statement->bind_result($id,$jewlery_type,$quality, $price_paid,$discount,$date_purchased,$user_id,$location_id);
      //storing the results to count the rows
      $sql_statement->store_result();
      //get number of rows
      $row_count = $sql_statement-> num_rows;
      //see if the table is empty
      if($row_count > 0){
        while($sql_statement->fetch()){
          $date_purchased = date("m/d/Y", strtotime($date_purchased));
          echo "<tr>
            <input type='hidden' id='id' name = 'id' value='$id'>
            <td class='jewlery_type'> $jewlery_type</td>
            <td class='quality'>$quality</td>
            <td class='price_paid'>$price_paid</td>
            <td class='discount'>$discount</td>
            <td class='user_id'>$user_id</td>
            <td class='location_id'>$location_id</td>";
            if($current_user_id === $user_id){
              echo"<td class='date_purchased'>   <a class='delete_item btn btn-primary disable'href = '#' role='button'>Remove Item</a></td>";
            }
            echo"</tr>";
          }
      }
      else{//the table is empty
        echo "<h1 class='no-data'>No data in the tables.</h1>";
      }
      $sql_statement->close();
    }
  }
   function construct_data_table_from_database_update_pane($current_user_described_by_emai,$current_user_id,$current_users_location){
    global $conn_obj,$table;
    $sql_statement = $conn_obj->stmt_init();
    if($sql_statement = $conn_obj->prepare("SELECT id,jewlery_type,quality, price_paid,discount,date_purchased,user_id,location_id  FROM $table")){
      $sql_statement->execute();
      $sql_statement->bind_result($id,$jewlery_type,$quality, $price_paid,$discount,$date_purchased,$user_id,$location_id);
      //storing the results to count the rows
      $sql_statement->store_result();
      //get number of rows
      $row_count = $sql_statement-> num_rows;
      if($row_count > 0){
        while($sql_statement->fetch()){
          $date_purchased = date("m/d/Y", strtotime($date_purchased));
          echo "<tr>
            <input type='hidden' id='id' name = 'id' value='$id'>
            <td class='jewlery_type'> $jewlery_type</td>
            <td class='quality'>$quality</td>
            <td class='price_paid'>$price_paid</td>
            <td class='discount'>$discount</td>
            <td class='user_id'>$user_id</td>
            <td class='location_id'>$location_id</td>";
            if($current_user_id === $user_id){
               echo"<td class='date_purchased' value='$date_purchased'>   <a class='update_item btn btn-primary disable'href = '#' role='button'>Update Item</a></td>";
            }
            echo"</tr>";
        }
      }
      else{
        echo "<h1>No data in the tables.</h1>";
      }
      $sql_statement->close();
    }
  }

?>
