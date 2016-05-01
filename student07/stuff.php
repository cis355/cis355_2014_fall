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
<a href="http://cis355.com/student07/morestuff.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true);										// Sets php.ini setting to allow uploads
error_reporting(e_all);												// Display errors (so you don't get a blank page)

if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)			// Checks that file was uploaded(by checking "upload was clicked" and file exists in $_FILES
{
$fileName = $_FILES['userfile']['name'];							// File name on the user machine
$tmpName  = $_FILES['userfile']['tmp_name'];						// Computer generated name
$fileSize = $_FILES['userfile']['size'];							// Size of file in byte
$fileType = $_FILES['userfile']['type'];							// Mime type (not working in this code)
//------ Ignore Below -------------------------------------------
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));
//------ Ignore Above ------------------------------------------- 
$fp      = fopen($tmpName, 'r');									// Opens file that was passed (read only)
$content = fread($fp, filesize($tmpName));							// Reading the file into the variable, $content
$content = addslashes($content);									// Makes content usable in sql query (database safe)
echo "<br>filename: ".$fileName."<br>";								// Prints file name in browser
echo "filesize: ".$fileSize."<br>";									// Prints file size in browser
echo "filetype: ".$fileType."<br>";									// Prints file type in browser (blank)

fclose($fp);														// Closes file
// ----- Ignore Below -----------------
if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}
// ----- Ignore Above -----------------

$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error()); // Connects to database
$db = mysql_select_db('lesson01', $con);							// Verify database is available
if($db){															// $db is a boolean value
$query = "INSERT INTO LDC_upload (name, size, type, content ) ".
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";		// Set up query for the database
mysql_query($query) or die('Error, query failed'); 					// Submits query or dies and displays message
mysql_close();														// mysql closes
echo "<br>File $fileName uploaded<br>";								// Echo message 
}else { echo "file upload failed: ".mysql_error(); }
} 
?>