<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["name"]."</td></tr>
<tr><td width=\"35%\">descr</td><td>".$_REQUEST["descr"]."</td></tr>
<tr><td width=\"35%\">price</td><td>".$_REQUEST["price"]."</td></tr>
<tr><td width=\"35%\">city</td><td>".$_REQUEST["city"]."</td></tr>
<tr><td width=\"35%\">state</td><td>".$_REQUEST["state"]."</td></tr>
<tr><td width=\"35%\">zip</td><td>".$_REQUEST["zip"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table19";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('select 1 from `table19`');
if($val == FALSE){
  $sql = "CREATE TABLE table19 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
  $sql .= "name VARCHAR(20),";
  $sql .= "descr VARCHAR(30),";
  $sql .= "price VARCHAR(10),";
  $sql .= "city VARCHAR(20),";
  $sql .= "state VARCHAR(15),";
  $sql .= "zip VARCHAR(12)";
  $sql .= ")";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}
$val1 = $_POST["name"];
$val2 = $_POST["descr"];
$val3 = $_POST["price"];
$val4 = $_POST["city"];
$val5 = $_POST["state"];
$val6 = $_POST["zip"];

$sql = "INSERT INTO `lesson01`.`table19` (`id`, `name`, `descr`, `price`, `city`, `state`, `zip`) 
		VALUES (NULL, '$val1', '$val2', '$val3', '$val4', '$val5', '$val6')";

$result = mysql_query ("$sql");
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}
?>