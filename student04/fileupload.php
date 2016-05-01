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
<a href="http://cis355.com/student04/filedownload.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
//Olivia Archambo
ini_set('file-uploads',true);	// set php.ini to allow file uploads
error_reporting(e_all);		// displays errors (so you dont just get a blank page)
// checks that file was uploaded by checking that uploaded was clicked and the file exists in $_FILES
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)
{
$fileName = $_FILES['userfile']['name'];	//filename on user machine
$tmpName  = $_FILES['userfile']['tmp_name'];	//computer-generated name
$fileSize = $_FILES['userfile']['size'];	// size of file in bytes
$fileType = $_FILES['userfile']['type'];	// mime type (not working...)
//~~~~~~~~~~~IGNORE BELOW~~~~~~~~~~~~~~~~~
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));
//~~~~~~~~~~~~~~IGNORE~~~~~~~~~~~~~~~~~~
$fp      = fopen($tmpName, 'r');	// opens the file that was passed (read-only)
$content = fread($fp, filesize($tmpName));	// reading file into variable called $content
$content = addslashes($content);	// makes content usable in sql query

echo "filename: ".$fileName."<br>"; // prints file name in browser
echo "filesize: ".$fileSize."<br>"; // prints file size in browser
echo "filetype: ".$fileType."<br>"; // prints file type in browser (blank)

fclose($fp); // closes file
//~~~~~~~~~~ignore \/~~~~~~~~~~~~~~~~~
if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}
//~~~~~~~~~~~~ignore /\~~~~~~~~~~~~~~~~
$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error()); // connects to DB
$db = mysql_select_db('lesson01', $con); // verifies database is available
if($db){								// db is boolean value
$query = "INSERT INTO oma_upload (name, size, type, content ) ". //insert into table
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";
mysql_query($query) or die('Error, query failed'); 	//
mysql_close();										//
echo "<br>File $fileName uploaded<br>";
}else { echo "file upload failed: ".mysql_error(); }
} 
?>