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
<a href="http://cis355.com/student02/file_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true);	// set php.ini setting to allow uploads
error_reporting(e_all);		// display errors (besides blank page)

if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)	
	// checks that file was uploaded, by checking if "upload was clicked" and file exists in $_FILES
{
$fileName = $_FILES['userfile']['name'];	// name of file on users machine (ex: cat.jpg)
$tmpName  = $_FILES['userfile']['tmp_name'];// computer-generated name
$fileSize = $_FILES['userfile']['size'];	// puts the size of the file into bytes
$fileType = $_FILES['userfile']['type'];	// MIME type (currently not working)
// -- ignore ? --
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));
// --------------

$fp      = fopen($tmpName, 'r');			// opens the file that was passed (readonly)
$content = fread($fp, filesize($tmpName));	// opens and reads file and stores filesize into $content
$content = addslashes($content);			// filters $content for SQL accessibility
echo "<br>filename: ".$fileName."<br>";		// prints file name
echo "filesize: ".$fileSize."<br>";			// prints file size
echo "filetype: ".$fileType."<br>";			// prints file mime type (currently not working)

fclose($fp);	// closes file
// -- ignore ? --
if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}
// --------------
// connect to database or DIE
$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error());
// verifies the database is available
$db = mysql_select_db('lesson01', $con);
// $db boolean value
if($db){
$query = "INSERT INTO ena_upload (name, size, type, content ) ".
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";
mysql_query($query) or die('Error, query failed'); 
mysql_close();
echo "<br>File $fileName uploaded<br>";
}else { echo "file upload failed: ".mysql_error(); }
} 
?>