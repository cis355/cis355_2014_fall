<html>
<!---------------------------
// File Name: download.php
// author: Nathan Whitfield
----------------------------->

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
<a href="http://cis355.com/student19/file_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true); // changes php.ini setting to allow files uploads.
error_reporting(e_all); // displays errors that occur in your code.
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0) // checks to see if 'upload' is set and if 'userfile' is not null
{
$fileName = $_FILES['userfile']['name'];	 //filename on the user machine
$tmpName  = $_FILES['userfile']['tmp_name']; //computer-generated name
$fileSize = $_FILES['userfile']['size'];	 //size of the file in bytes
$fileType = $_FILES['userfile']['type'];	 //sets the MIME type of the file(not working)

$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));

$fp      = fopen($tmpName, 'r');			//opens file that was passed as read only.
$content = fread($fp, filesize($tmpName));  //reads file into $content variable.
$content = addslashes($content);			//adds slashes in front of special characters to add into mysql statement
echo "<br>filename: ".$fileName."<br>";
echo "filesize: ".$fileSize."<br>";
echo "filetype: ".$fileType."<br>";

fclose($fp);	// closes file

if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}

$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error());  //connects to database
$db = mysql_select_db('lesson01', $con);  //verifies database is avaliable

//If $db is true then you add the file into the database
if($db){
$query = "INSERT INTO ntw_upload (name, size, type, content ) ".
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";
mysql_query($query) or die('Error, query failed');  // executes insert query. Displays error message if it fails.
mysql_close();
echo "<br>File $fileName uploaded<br>";
}else { echo "file upload failed: ".mysql_error(); }
} 
?>