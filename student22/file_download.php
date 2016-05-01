<?php 
mysql_connect("localhost","student","learn"); //connect to the database
mysql_select_db("lesson01"); //connect to lesson01
$id = 1; //set to the first picture in record
$query = "SELECT name, size, content, type FROM bay_upload where id=$id"; //preparing sql statement
$result = MYSQL_QUERY($query); //executes 
//sets var. for file info
$name = MYSQL_RESULT($result,0,"name"); 
$size = MYSQL_RESULT($result,0,"size"); 
$content = MYSQL_RESULT($result,0,"content"); 
$type = MYSQL_RESULT($result,0,"type"); 
//sets the page to display the file type
Header( "Content-type: $type"); 

//prints the file to the page
print $content; 
?>