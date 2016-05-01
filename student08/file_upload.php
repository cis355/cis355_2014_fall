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
<a href="http://cis355.com/student08/file_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a><br>
</body>
</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
ini_set('file-uploads',true); // set php.ini setting to allow file uploads
error_reporting(e_all); //displays errors to avoid blank pages

//checks that file was uploaded by checking "upload was clocked and file exists in $FILES
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)
{
$fileName = $_FILES['userfile']['name']; //filename on the user machine
$tmpName  = $_FILES['userfile']['tmp_name']; //computer-generated name
$fileSize = $_FILES['userfile']['size']; // size of file
$fileType = $_FILES['userfile']['type']; //Mime type of file
//Ignore below//
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));
//Ignore above//
$fp      = fopen($tmpName, 'r'); //opens file that was passed (read-only)
$content = fread($fp, filesize($tmpName)); //read file into variable, $contents
$content = addslashes($content); //makes content usable in sql query(database safe)
echo "File Name: ".$fileName."<br>"; //The next 3 lines prints the Name, Size and Type of the file.
echo "File Size: ".$fileSize."<br>";
echo "File Type: ".$fileType."<br>";

fclose($fp);//closes file
//ignore below//
if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}
//ignore above//

$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error()); //connects to database
$db = mysql_select_db('lesson01', $con);  // verifies that data base is available
if($db){ //db is boolean
$query = "INSERT INTO mbc_upload (name, size, type, content ) ". //insert into the table using the variables
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')"; //Values entered in earlier
mysql_query($query) or die('Error, query failed'); //preforms the query or throws an error
mysql_close(); //Closes mysql
echo "<br>File $fileName uploaded<br>"; //prints the file was successful
}else { echo "file upload failed: ".mysql_error(); } //prints an error if one
} 
?>