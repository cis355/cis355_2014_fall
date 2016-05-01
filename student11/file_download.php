<?php 
mysql_connect("localhost","student","learn"); //lets make a connection
mysql_select_db("lesson01"); //lets grab a db
$id = 1; //i have an id of one
$query = "SELECT name, size, content, type FROM bsl_upload where id=$id"; //grap a file where the id equals id 
$result = MYSQL_QUERY($query);  //execute the query 
$name = MYSQL_RESULT($result,0,"name"); //the name variable has the name of the file
$size = MYSQL_RESULT($result,0,"size"); //the file size has the size of the file
$content = MYSQL_RESULT($result,0,"content"); // grab the content from the content column and put it in the variable
$type = MYSQL_RESULT($result,0,"type");  //grab the type from the type column and put it in type
Header( "Content-type: $type");  //set the header content-type
print $content; //lets show the content
?>