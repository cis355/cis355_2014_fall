<?php 
//--------------------------
// download.php
// Shawn Wagner
//--------------------------
mysql_connect("localhost","student","learn"); //connect to the database
mysql_select_db("lesson01"); //verifies the table is available
$id = 1; //only allows one file to be uploaded
$query = "SELECT name, size, content, type FROM smw_upload where id=$id"; //sql query to get image
$result = MYSQL_QUERY($query); //variable that contains the output of the query
$name = MYSQL_RESULT($result,0,"name"); //value in name field of first row of results
$size = MYSQL_RESULT($result,0,"size"); //value in size field of first row of results
$content = MYSQL_RESULT($result,0,"content"); //value in content field of first row of results
$type = MYSQL_RESULT($result,0,"type"); //type doesn't work 
Header( "Content-type: $type"); //helps browser format output based in file type
print $content; //displays the image
?>