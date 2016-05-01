<?php 
//Connect DB
mysql_connect("localhost","student","learn"); 
//Choose DB
mysql_select_db("lesson01"); 
$id = 1; //ID of file in table to download
$query = "SELECT name, size, content, type FROM cab_upload where id=$id"; //Create query for file table 
$result = MYSQL_QUERY($query);                                            //Store query in result variable
$name = MYSQL_RESULT($result,0,"name"); //store name of file in name variable
$size = MYSQL_RESULT($result,0,"size");  //store size in bytes (INT) into size variable
$content = MYSQL_RESULT($result,0,"content"); //store actual file contents into content variable
$type = MYSQL_RESULT($result,0,"type");  //store mime type (application/... , image/... , text/...) into type variable 
Header( "Content-type: $type");  // Redirect page to view picture
print $content;  // view picture
?>