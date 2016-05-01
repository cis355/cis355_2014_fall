<?php 
  // Connects to database
  mysql_connect("localhost","student","learn"); 
  // Selects the lesson01 database, returns boolean
  mysql_select_db("lesson01"); 
  
  $id = 1;  // Sets the id to 1
  
  // Build query with $id
  $query = "SELECT name, size, content, type FROM crm_upload where id=$id";
  // Get the result of the query
  $result = MYSQL_QUERY($query); 
  
  $name = MYSQL_RESULT($result,0,"name");         // Get name column value from first row of result
  $size = MYSQL_RESULT($result,0,"size");         // Get size column value from first row of result
  $content = MYSQL_RESULT($result,0,"content");   // Get content column value from first row of result
  $type = MYSQL_RESULT($result,0,"type");         // Get type column value from first row of result
  
  Header( "Content-type: $type");   // HTTP header type
  print $content;                   // Print content to screen
?>