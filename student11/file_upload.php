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
<a href="http://cis355.com/student11/file_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true); //allows user to upload files 
error_reporting(e_all);//let me see the errors
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0) //are we uploading a file?
{
$fileName = $_FILES['userfile']['name'];//setting the file 
$tmpName  = $_FILES['userfile']['tmp_name'];//setting the temp file name
$fileSize = $_FILES['userfile']['size'];//setting the file size
$fileType = $_FILES['userfile']['type'];//setting the file type
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile']))); // Gets the current configuration setting of magic_quotes_gpc(is it on the server?) and makes sure it is not 0 if it is the we escape the special chararacters else it strips the strips the slashes and then escapes the special characters 
$fp      = fopen($tmpName, 'r');// binding the tempName to the variable fp...used for file streams the r stands for read only
$content = fread($fp, filesize($tmpName));//binding the file contents of the file into a content variable
$content = addslashes($content); //adding some slashes to this so i can make a query
echo "filename: ".$fileName."<br>"; //what is the filename?? now we know it is in the echo
echo "filesize: ".$fileSize."<br>";//what is the fileSize?? now we know it is in the echo
echo "filetype: ".$fileType."<br>";//what is the fileType?? now we know it is in the echo

fclose($fp);  //time to close this file because freeing up resources is performance's close friend
if(!get_magic_quotes_gpc()) //if we aren't using magic
{
    $fileName = addslashes($fileName); //then this is our file name
}
$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error()); //lets connect
$db = mysql_select_db('lesson01', $con); //lets get our db with our connection
if($db){ //if we have a db
$query = "INSERT INTO bsl_upload (name, size, type, content ) ".
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')"; //then i am going to upload this file to it
mysql_query($query) or die('Error, query failed'); //either perform this query or die
mysql_close(); //close my connection
echo "<br>File $fileName uploaded<br>"; //tell the user the file uploaded
}else { echo "file upload failed: ".mysql_error(); } //tell the user there was no upload of the file 
} 
?>