<?php 
mysql_connect("localhost","student","learn"); 	// connect to db
mysql_select_db("lesson01"); 				  	// verifies table is available 
$id = 1;	// $id is the id of the file to download
$query = "SELECT name, size, content, type FROM nma_upload where id=$id";  // sql query
$result = MYSQL_QUERY($query); 	// $result variable contains the output of query
$name = MYSQL_RESULT($result,0,"name"); 	// $name is value in name field of first row of results (row 0)
$size = MYSQL_RESULT($result,0,"size"); 	// $size is value in size field of first row of results (row 0)
$content = MYSQL_RESULT($result,0,"content"); 	// $content is value in content field of first row of results (row 0)
$type = MYSQL_RESULT($result,0,"type"); 	// $type is value in type field of first row of results (row 0)
Header( "Content-type: $type"); 		// helps browser format output based on file type 
print $content; // displays to screen 
?>