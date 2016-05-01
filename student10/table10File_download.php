<?php 
$usertable = "gpc_upload";

// make connection to database
mysql_connect("localhost","student","learn"); 

// database is selected
mysql_select_db("lesson01"); 

// first image in table
$id = 1;

// first image in query is selected
$query = "SELECT name, size, content, type FROM $usertable where id=$id"; 

// results of query
$result = MYSQL_QUERY($query);
 
$name = MYSQL_RESULT($result,0,"name");        // file extension
$size = MYSQL_RESULT($result,0,"size");        // file size
$content = MYSQL_RESULT($result,0,"content");  // file size in bytes
$type = MYSQL_RESULT($result,0,"type");        // mime file 
 
// sends php content to browser 
Header( "Content-type: $type");
print $content; 

echo"<br><br>Not sure how to display image. i missed that part";
?>