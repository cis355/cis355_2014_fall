<?php 
mysql_connect("localhost","student","learn"); 								// Connects to database
mysql_select_db("lesson01"); 												// Verifies tables available
$id = 1;																	// $id is the id of the file to download
$query = "SELECT name, size, content, type FROM LDC_upload where id=$id"; 	// Sets up query
$result = MYSQL_QUERY($query); 												// $result is the results from query
$name = MYSQL_RESULT($result,0,"name"); 									// $name gets name value of the results first row									
$size = MYSQL_RESULT($result,0,"size"); 									// $size gets size value of the results first row
$content = MYSQL_RESULT($result,0,"content"); 								// $content gets content value of the results first row
$type = MYSQL_RESULT($result,0,"type"); 									// $type gets type value of the results first row
Header( "Content-type: $type"); 											// helps browser format output based on the file type
print $content; 															// displays to screen
?>