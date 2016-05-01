<?php 
/////--------------------------
// File: File_download.php
// author: George Corser (Cis355)
// updated by: Michael Coppolino
//
//-----------------------------------
mysql_connect("localhost","student","learn"); // Database Connecting
mysql_select_db("lesson01");  // Verifies Table Available
$id = 1; //$id is a variable that tells where in the table the file is
$query = "SELECT name, size, content, type FROM mbc_upload where id=$id"; //Tells mysql to upload the file
$result = MYSQL_QUERY($query);  // starts query
$name = MYSQL_RESULT($result,0,"name");  //name gets name value of file
$size = MYSQL_RESULT($result,0,"size");   //size gets size value of file
$content = MYSQL_RESULT($result,0,"content");  //content gets the image ready to post
$type = MYSQL_RESULT($result,0,"type");  // type gets type value of file
Header( "Content-type: $type");  // Helps browser format data
print $content;  //posts the picture in the browser
?>