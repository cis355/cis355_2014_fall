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
<a href="http://cis355.com/student01/file_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true); // set php.ini setting to allow file uploads
error_reporting(e_all); // display errors, shows blank page otherwise
// checks that file was uploaded (by checking "upload was clicked" and file exists in $_FILES)
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)
{
$fileName = $_FILES['userfile']['name'];     // filename on user machine
$tmpName  = $_FILES['userfile']['tmp_name']; // computer-generated name
$fileSize = $_FILES['userfile']['size'];     // size of file in bytes
$fileType = $_FILES['userfile']['type'];     // mime type (not working in this code)
// -- ignore --
$fileType=(get_magic_quotes_ena()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));
//-------------

$fp      = fopen($tmpName, 'r');            // opens the file that was passed (read-only)
$content = fread($fp, filesize($tmpName));  // reading file into variable, @contents
$content = addslashes($content);            // makes content usable in sql query (database safe)
echo "filename: ".$fileName."<br>";         // prints file name in browser
echo "filesize: ".$fileSize."<br>";         // prints file size in browser
echo "filetype: ".$fileType."<br>";         // prints file type in browser

fclose($fp); // closes file
// -------- ignore below ------
if(!get_magic_quotes_ena())
{
    $fileName = addslashes($fileName);
}
// --------- ignore above -----

$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error()); // connects to db
$db = mysql_select_db('lesson01', $con); // verifies database is available
if($db){                                 // $db is boolean value
$query = "INSERT INTO ena_upload (name, size, type, content ) ".
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";
mysql_query($query) or die('Error, query failed'); // 
mysql_close();
echo "<br>File $fileName uploaded<br>";
}else { echo "file upload failed: ".mysql_error(); }
} 
?>