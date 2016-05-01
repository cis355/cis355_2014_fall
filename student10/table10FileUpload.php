<?php
// Sets the value of the given configuration option
// ini option 'file-uploads' : Whether or not to allow HTTP file uploads
ini_set('file-uploads',true);

// if error report
error_reporting(e_all);

// if submitted form value is upload and is not null
//    uploaded file has file size > 0
if(isset($_POST['upload'])&&$_FILES['userfile']['size']>0)
{
$fileName = $_FILES['userfile']['name'];       // file extension
$tmpName  = $_FILES['userfile']['tmp_name'];   // file size
$fileSize = $_FILES['userfile']['size'];       // file size in bytes
$fileType = $_FILES['userfile']['type'];       // mime file

// Gets the current configuration setting of magic_quotes_gpc
// magic_quotes_gpc : process of escaping special characters with a '\'
// mysql_real_escape_string : Escapes special characters in a string for use in an SQL statement 
$fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
$_FILES['userfile']['type']) : mysql_real_escape_string(
stripslashes ($_FILES['userfile'])));

/*  */
$fp      = fopen($tmpName, 'r');            // opens read only file
$content = fread($fp, filesize($tmpName));  // gets file size
$content = addslashes($content);            // Returns a string with backslashes before characters that 
                                            // need to be escaped
echo "filename: ".$fileName."<br>";
echo "filesize: ".$fileSize."<br>";
echo "filetype: ".$fileType."<br>";

// close file
fclose($fp);

// if get_magic_quotes_gpc is not true add slashes to filename
if(!get_magic_quotes_gpc())
{
    $fileName = addslashes($fileName);
}

// assign table name
$usertable = "table10_upload";

// connect to database
$con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error());

// returns true if connected to database
$db = mysql_select_db('lesson01', $con);

// if connected to database inset image
if($db){	
$query = "INSERT INTO table10gpc_upload (name, size, type, content ) ".
"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";

// if query fails display erros
mysql_query($query) or die('Error, query failed');

//exit connection 
mysql_close();

echo "<br>File $fileName uploaded<br>";
}else { echo "file upload failed: ".mysql_error(); }
} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="table10FileUpload.php" method="post" enctype="multipart/form-data">
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
<a href="table10File_download.php">Download</a><br>
<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>
