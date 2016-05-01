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
<a href="http://cis355.com/student18/download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
//--------------------------
// upload.php
// Shawn Wagner
//--------------------------
ini_set('file-uploads',true);
error_reporting(e_all); //displays errors
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0) //checks that file was uploaded by checking that upload was clicked and file exists
{
$fileName = $_FILES['userfile']['name']; //passes the name of the image
$tmpName  = $_FILES['userfile']['tmp_name']; //
$fileSize = $_FILES['userfile']['size']; //image size is passed
$fileType = $_FILES['userfile']['type']; //mime type, not working
//deprecated
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));
//
$fp      = fopen($tmpName, 'r'); //opens the file that was passed (read only)
$content = fread($fp, filesize($tmpName)); //reading the file into the variable called $content
$content = addslashes($content); //makes the content usable in a sql query (database safe)
echo "filename: ".$fileName."<br>"; //displays the file name, size, type
echo "filesize: ".$fileSize."<br>"; 
echo "filetype: ".$fileType."<br>";

fclose($fp); //closes the file

//deprecated
if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}
//

$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error()); //connects to the database
$db = mysql_select_db('lesson01', $con); //verifies the database is available
if($db){ //boolean value to check if the query is possible
$query = "INSERT INTO smw_upload (name, size, type, content ) ". //setup first line sql query
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')"; //assign values to the query
mysql_query($query) or die('Error, query failed'); //execute the query or display error message
mysql_close(); //
echo "<br>File $fileName uploaded<br>";
}else { echo "file upload failed: ".mysql_error(); }
} 

/* CREATE TABLE smw_upload (
    id        Int Not Null Auto_Increment,
    name      VarChar(255) Not Null,
    size      BigInt Unsigned Not Null,
    type      VarChar(50),
    content   MediumBlob Not Null,
    PRIMARY KEY (id)
); */
?>