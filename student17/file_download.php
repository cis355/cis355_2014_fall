<?php 
//-------------------------------------------
// file: file_download.php
// author: Alexys Suisse (CIS 355)
// date: 11/20/14
//--------------------------------------------
mysql_connect("localhost","student","learn");  								// connects DB
mysql_select_db("lesson01"); 												// verifies the tables available
$id = 1; 																	// $id is the id in the ays_upload table of the file to download
$query = "SELECT name, size, content, type FROM ays_upload where id=$id"; 	// sql query
$result = MYSQL_QUERY($query); 												// $result variable contains the output of the query
$name = MYSQL_RESULT($result,0,"name"); 									// $name is the value in name field of first row of results (row 0)
$size = MYSQL_RESULT($result,0,"size"); 									// $size is the value in the size field of first row 
$content = MYSQL_RESULT($result,0,"content"); 								// $content is the value in the content field of first row
$type = MYSQL_RESULT($result,0,"type"); 									// $type is the the value in the type field of first row
Header( "Content-type: $type"); 											// helps browser format output based on file type
print $content; 															// displays to screen
?>
