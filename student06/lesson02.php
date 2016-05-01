<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">Title:</td><td>".$_REQUEST["title"]."</td></tr>
<tr><td width=\"35%\">Author:</td><td>".$_REQUEST["author"]."</td></tr>
<tr><td width=\"35%\">Publisher:</td><td>".$_REQUEST["publisher"]."</td></tr>
<tr><td width=\"35%\">Published:</td><td>".$_REQUEST["published"]."</td></tr>
<tr><td width=\"35%\">Genre:</td><td>".$_REQUEST["genre"]."</td></tr>
<tr><td width=\"35%\">ISBN:</td><td>".$_REQUEST["isbn"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table06";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

if( mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$usertable."'")) != 1 ){ 
  $sql = "CREATE TABLE $usertable( ".
       "id INT NOT NULL AUTO_INCREMENT, ".
       "title VARCHAR(30), ".
	   "author VARCHAR(30), ".
	   "publisher VARCHAR(30), ".
	   "published VARCHAR(30), ".
	   "genre VARCHAR(30), ".
	   "isbn VARCHAR(30), ".
       "PRIMARY KEY ( id )); ";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	 else
	 echo "Table created!";
}

if( $_POST['Submit'] ) {

	$title = $_POST["title"];
	$author = $_POST["author"];
	$publisher = $_POST["publisher"];
	$published = $_POST["published"];
	$genre = $_POST["genre"];
	$isbn = $_POST["isbn"];

	$sql = "INSERT INTO $usertable VALUES (null, '$title', '$author', '$publisher', '$published', '$genre', '$isbn')";

	$result = mysql_query ("$sql");
	if(!($result)) {
	   echo "<BR><font color=red>error: record not inserted</font><p>".mysql_error()."</p>";
	} else {
	   echo "<br><font color=green>record successfully inserted</font>";
	}

}

?>

<!DOCTYPE html>

<html>

<head>
  <title>Lesson02 Form</title>
</head>

<body>

<style>
.robotext {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
.robolink:link {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
.robolink:hover {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: underline}
.robolink:visited {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
</style>

<form name="basic" method="Post" action="insert-book.php">
<table border="0" cellpadding="5" cellspacing="0">
<tr><td>Title:</td><td><input type="text" name="title" value="" size="20"></td></tr>
<tr><td>Author:</td><td><input type="text" name="author" value="" size="20"></td></tr>
<tr><td>Publisher</td><td><input type="text" name="publisher" value="" size="20"></td></tr>
<tr><td>Date Published:</td><td><input type="text" name="published" value="" size="20"></td></tr>
<tr><td>Genre</td><td><input type="text" name="genre" value="" size="20"></td></tr>
<tr><td>ISBN</td><td><input type="text" name="isbn" value="" size="20"></td></tr>
<tr><td align="center"><input type="submit" name="Submit" value="Submit"></td></tr>
</table>
</form>

</body>
</html>