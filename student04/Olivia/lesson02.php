<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["name"]."</td></tr>
<tr><td width=\"35%\">descrip</td><td>".$_REQUEST["descrip"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table04";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('select 1 from `table04`');
if($val == FALSE){
  $sql = "CREATE TABLE table04 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
  $sql .= "name VARCHAR(20),";
  $sql .= "descrip VARCHAR(30)";
  $sql .= ")";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error descripription: </font>".mysql_error();
}
$val1 = $_POST["name"];
$val2 = $_POST["descrip"];
$sql = "INSERT INTO `lesson01`.`table04` (`id`, `name`, `descrip`) VALUES (NULL, '$val1', '$val2')";

$result = mysql_query ("$sql");
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}
$sql = "SELECT * FROM `lesson01`.`table04`";

$result = mysql_query ("$sql");
while($row = mysql_fetch_array($result)) {
  echo $row['name'] . " " . $row['descrip'];
  echo "<br>";
}
?>