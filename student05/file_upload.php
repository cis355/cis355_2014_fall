<html>
	<body>
		<form method="post" enctype="multipart/form-data">
			<table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
			<tr>
			<td>please select a file</td></tr>
			<tr>
			<td>
				<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
				<input name="userfile" type="file" id="userfile"> 
			</td>
			<td width="80"><input name="upload" type="submit" class="box" id="upload" value=" Upload "></td>
			</tr>
			</table>
		</form>
		<br>
		<a href="http://cis355.com/student01/file_download.php">Download</a><br>
		<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
	</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true);
error_reporting(e_all);


if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)// if upload has been selected and file exists and is not null
{
	$fileName = $_FILES['userfile']['name']; //store file name
	$tmpName  = $_FILES['userfile']['tmp_name']; //store temporary name for file replacement
	$fileSize = $_FILES['userfile']['size']; //store size in bytes (integer)
	$fileType = $_FILES['userfile']['type']; // store mime type of file
	
	//get file path without slashes
	$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string( 
		$_FILES['userfile']['type']) : mysql_real_escape_string( stripslashes ($_FILES['userfile'])));
	$fp = fopen($tmpName, 'r'); // open file
	$content = fread($fp, filesize($tmpName)); // read file
	$content = addslashes($content); // add slashes to navigate directories
	
	// display file details
	echo "filename: ".$fileName."<br>";
	echo "filesize: ".$fileSize."<br>";
	echo "filetype: ".$fileType."<br>";

	// close file
	fclose($fp);

	// if get_magic_quotes_gpc() fails then add the slashed back on to path in $filename
	if(!get_magic_quotes_gpc())
	{
		$fileName = addslashes($fileName);
	}

	//Connect and choose database
	$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error());
	$db = mysql_select_db('lesson01', $con);

	// If successful connection to database
	if($db)
	{
		// Create query for inserting into file table
		$query = "INSERT INTO cab_upload (name, size, type, content ) ".
		"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";
		// If query fails let user know and cancel further processes
		mysql_query($query) or die('Error, query failed'); 
		// close database connection
		mysql_close();
		echo "<br>File $fileName uploaded<br>";
	}
	else 
	{ 
		echo "file upload failed: ".mysql_error(); 
	}

} 
?>