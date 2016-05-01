<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["user_id"]."</td></tr>
<tr><td width=\"35%\">description</td><td>".$_REQUEST["description"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table22";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('select 1 from `table22`');
if($val == FALSE){
  $sql = "CREATE TABLE table22 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
  $sql .= "user_id INT(20),";
  $sql .= "zip_code INT(5),";
  $sql .= "boat_type VARCHAR(20),";
  $sql .= "paid INT(20),";
  $sql .= "description VARCHAR(30)";
  $sql .= ")";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}
$val1 = $_POST["user_id"];
$val2 = $_POST["zip_code"];
$val3 = $_POST["boat_type"];
$val4 = $_POST["paid"];
$val5 = $_POST["description"];
$sql = "INSERT INTO `lesson01`.`table22` (`id`, `user_id`, `zip_code`, `boat_type`, `paid`, `description`)
	VALUES (NULL, '$val1', '$val2', '$val3', '$val4', '$val5')";

$result = mysql_query ("$sql");
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}

$sql = "DROP TABLE `table22`";
$result = mysql_query ("$sql");
if(!($result)) {
		echo $result;
   echo "<BR><font color=red>error: record not dropped</font>";
} else {

   echo "<br><font color=green>record successfully dropped</font>";
}

?>