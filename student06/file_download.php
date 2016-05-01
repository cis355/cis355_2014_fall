<?php

/**
*
* @copyright
* @author
*
*/

// Connect to the MySQL database
mysql_connect("localhost","student","learn");
// Since we are connected to MySQL, this piece of code selects the database to use.
mysql_select_db("lesson01");

// The ID number of the row in the database table
$id = 1;

// The SQL query to be executed. The ID number of the row is targeted.
$query = "SELECT name, size, content, type FROM gpc_upload where id = $id";

// Executes the query and returns the result of the query
$result = MYSQL_QUERY($query);

// Get the value of the "name" column in row zero of the results
$name = MYSQL_RESULT($result,0,"name");
// Get the value of the "size" column in row zero of the results.
// The size of the file (in bytes).
$size = MYSQL_RESULT($result,0,"size");
// Get the value of the "content" column in row zero of the results.
// The binary data in the file (represented as a bunch of random characters).
$content = MYSQL_RESULT($result,0,"content");
// Get the value of the "type" column in row zero of the results
$type = MYSQL_RESULT($result,0,"type");
// Tells the browser what type of file it is downloading. $type should contain "video/jpg", but upload_file.php doesn't work
// so this variable will always be blank.
//Header( "Content-type: $type");
Header( "Content-type: $type");
// The content of the picture in base64 encoding
echo '<img src="data:image/jpeg;base64,'.base64_encode( $content ).'"/>'
?>