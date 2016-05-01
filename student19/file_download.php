<?php 
//-------------------------
// File Name: download.php
// author: Nathan Whitfield
//-------------------------

mysql_connect("localhost","student","learn"); //connects to the database
mysql_select_db("lesson01"); // sets the database that is being connected to 
$id = 1;  // assigns 1 to id
$query = "SELECT name, size, content, type FROM ntw_upload where id=$id"; //assigns select query from ntw_upload table
$result = MYSQL_QUERY($query);  //executes query and stores the results in $result
$name = MYSQL_RESULT($result,0,"name");  //$name is value in name field of 1st row of results
$size = MYSQL_RESULT($result,0,"size");  //$size is value in size field of 1st row of results
$content = MYSQL_RESULT($result,0,"content");  //$content is value in content field of 1st row of results
$type = MYSQL_RESULT($result,0,"type");  //$type is value in type field of 1st row of resutls
Header( "Content-type: $type");  //hels browser format output based on file type
//print $content; 
header('Content-Disposition: attachment; filename="'.$name.'"');
?>