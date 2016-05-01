<!-------------------------------------------
// file: file_upload.php
// author: Alexys Suisse (CIS 355)
// date: 11/20/14
//-------------------------------------------->
<html>
<body>
<form method="post" enctype="multipart/form-data">
<table width="350" border="0" cellpadding="1"
cellspacing="1" class="box">
<tr>
<td>please select a file</td></tr>
<tr>
<td>
<input type="hidden" name="MAX_FILE_SIZE"
value="16000000">
<input name="userfile" type="file" id="userfile"> 
</td>
<td width="80"><input name="upload"
type="submit" class="box" id="upload" value=" Upload "></td>
</tr>
</table>
</form>
<br>
<a href="http://cis355.com/student17/file_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true); 													//sets php.ini setting to allow file up loads
error_reporting(e_all); 														// displays errors (so you don't just get a blank page)																		
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0) 						// checks that file was uploaded (by checking "upload was 
																				//clicked" and file exists in $_FILES)
{
	$fileName = $_FILES['userfile']['name'];     									// filename on the user machine
	$tmpName  = $_FILES['userfile']['tmp_name']; 									// computer-generated name 
	$fileSize = $_FILES['userfile']['size'];     									// size of file in byte
	$fileType = $_FILES['userfile']['type'];    									// mime types (not working in this code)
	// ---- ignore below ----
	$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string( 
	$_FILES['userfile']['type']) : mysql_real_escape_string(
	stripslashes ($_FILES['userfile'])));
	// ---- ignore above ----
	$fp      = fopen($tmpName, 'r'); 												//opens the file that was passed (read-only)
	$content = fread($fp, filesize($tmpName)); 										// reading file into variable, $content
	$content = addslashes($content); 												// makes content usable in sql query (database safe)
	echo "<br> filename: ".$fileName."<br>"; 										//prints filename in browser
	echo "filesize: ".$fileSize."<br>"; 											// prints filesize in browser
	echo "filetype: ".$fileType."<br>"; 											// prints filetype in browser (blank)

	fclose($fp); 																	//closes the file

	//---- ignore below -----
	if(!get_magic_quotes_gpc())
	{
		$fileName = addslashes($fileName);
	}
	//---- ignore above -----
	$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error());	//connects to DB
	$db = mysql_select_db('lesson01', $con); 										// verifies database is available
	if($db){  																		// $db is boolean value
	$query = "INSERT INTO ays_upload (name, size, type, content ) ".				
			 "VALUES ('$fileName', '$fileSize', '$fileType', '$content')";			// insert the info through query
	mysql_query($query) or die('Error, query failed'); 								// either upload file or dies
	mysql_close();																	// close DB
	echo "<br>File $fileName uploaded<br>";											// print filename on browser that was loaded or
	}else { echo "file upload failed: ".mysql_error(); }							// print "file upload filed: with error message" to browser
} 
?>
