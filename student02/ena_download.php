<?php 
mysql_connect("localhost","student","learn"); // connects to db
mysql_select_db("lesson01");      // verifies table available
$id = 1;                       
$query = "SELECT name, size, content, type FROM gpc_upload where id=$id"; 
$result = MYSQL_QUERY($query); 
$name = MYSQL_RESULT($result,0,"name"); 
$size = MYSQL_RESULT($result,0,"size"); 
$content = MYSQL_RESULT($result,0,"content"); 
$type = MYSQL_RESULT($result,0,"type"); 
Header( "Content-type: $type"); 
print $content; 
?>