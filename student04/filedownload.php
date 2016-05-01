<?php 
//Olivia Archambo
mysql_connect("localhost","student","learn"); 	// connects to DB
mysql_select_db("lesson01"); 					// verify table available
$id = 1;										// is the id of the file to download
$query = "SELECT name, size, content, type FROM oma_upload where id=$id"; // sql query
$result = MYSQL_QUERY($query); 					// $result is a variable that contains output of the query
$name = MYSQL_RESULT($result,0,"name"); 		// $name is value in name field of first row of results
$size = MYSQL_RESULT($result,0,"size"); 		// $size is value in size field of first row of results
$content = MYSQL_RESULT($result,0,"content"); 	// $content is value in content field of first row of results
$type = MYSQL_RESULT($result,0,"type"); 		// $type doesn't work.
Header( "Content-type: $type"); 				// help the browser format the output based on the file type
print $content; 								// displays to the screen
?>