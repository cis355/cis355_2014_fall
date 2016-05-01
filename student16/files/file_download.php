<?php
/**
 * Purpose: This file will;
 *          download an image that has been saved to the mysql table
 *
 * Author:  Kevin Stachowski
 * Date:    11/20/2014
 * Update:  11/20/2014
 * Notes:   image is saved in a blob data type
 * 
 */

//set file upload permissions
ini_set('file-uploads',true);
//turn on errors
error_reporting(e_all);

 /** These vars will be part of the global collection.
*  These will need to be changed to your respective DB and table.
*/
define("hostname","localhost");
define("username","student");
define("password","learn");
define("dbname","lesson01");
define("tableName","gpc_upload");

if(isset($_GET["id"]))
{
	downloadFile();
}
else
{
	listImages();
}


 /**
 * Purpose: This will download a file to the mysql database.
 * Pre:     an image to download has been selected.
 * Post:    file has been downloaded.
 */
function downloadFile()
{
	//connect to mysql
	mysql_connect(hostname,username,password); 
	//select the DB
	mysql_select_db(dbname); 
	//get the id passed to the download page in the url
	$id = $_GET["id"];
	//the query to execute
	$query = "SELECT name, size, content, type FROM ".tableName." where id=$id"; 
	//the results of the query
	$result = MYSQL_QUERY($query); 
	//the name of the result
	$name = MYSQL_RESULT($result,0,"name"); 
	//the size of the result
	$size = MYSQL_RESULT($result,0,"size"); 
	//the content of the result
	$content = MYSQL_RESULT($result,0,"content"); 
	//the content type of the result
	$type = MYSQL_RESULT($result,0,"type"); 
	//display the image
	Header( "Content-type: $type"); 
	// Uncomment to save the image to the computer
	//Header( "Content-Disposition: attachment; filename=$name");
	//print the file if displayed
	print $content; 
}

 /**
 * Purpose: This will list all of the images on the mysql database.
 * Pre:     none
 * Post:    images have been listed.
 */
function listImages()
{
	echo "Select an image to display below.<br>";
	//connect to the DB
	$conn = new mysqli(hostname, username, password, dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	//the query to execute
	$query = "SELECT id, name, size FROM ".tableName; 
	//execute the query
	$result = $conn->query($query); 
	//read each row of data returned
	while($row = $result->fetch_assoc())
	{
		$id = $row["id"];
		$name = $row["name"]; 
		$size = $row["size"]; 
		//display links to the images
		echo "<a href='http://cis355.com/student16/files/file_download.php?id=$id'>$name - $size</a><br>";
	}
}