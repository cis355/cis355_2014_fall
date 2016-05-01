<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["name"]."</td></tr>
<tr><td width=\"35%\">desc</td><td>".$_REQUEST["desc"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table01";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('SELECT * FROM `tbl06`');
if($val == FALSE){  
  $sql = "CREATE TABLE tbl06( ".
       "id INT NOT NULL AUTO_INCREMENT, ".
       "descript VARCHAR(30), ".
       "PRIMARY KEY ( id )); ";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}

if( $_POST['submit'] ) {

	$val1 = $_POST["name"];
	$val2 = $_POST["desc"];
	$sql = "INSERT INTO 'lesson01'.'tbl06' VALUES (null, $val1, $val2)";

	$result = mysql_query ("$sql");
	if(!($result)) {
	   echo "<BR><font color=red>error: record not inserted</font>";
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

<form name="basic" method="Post" action="lesson02.php">
<table border="0" cellpadding="5" cellspacing="0">
<tr><td>name</td><td><input type="text" name="name" value="" size="20"></td></tr>
<tr><td>desc</td><td><input type="text" name="desc" value="" size="30"></td></tr>
<tr><td align="center"><input type="reset" name="Reset" value="Reset"></td><td align="center"><input type="submit" name="Submit" value="Submit"></td></tr>
</table>
</form>

</body>
</html>