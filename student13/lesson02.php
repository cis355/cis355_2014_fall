<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">brand</td><td>".$_REQUEST["brand"]."</td></tr>
<tr><td width=\"35%\">model</td><td>".$_REQUEST["model"]."</td></tr>
<tr><td width=\"35%\">type</td><td>".$_REQUEST["type"]."</td></tr>
<tr><td width=\"35%\">price</td><td>".$_REQUEST["price"]."</td></tr>
<tr><td width=\"35%\">water depth</td><td>".$_REQUEST["water"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table01";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('select 1 from `table13`');
if($val == FALSE){
  $sql = "CREATE TABLE table13 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
  $sql .= "brand VARCHAR(20),";
  $sql .= "model VARCHAR(20),";
  $sql .= "type VARCHAR(30),";
  $sql .= "price DECIMAL(7,2),";
  $sql .= "water DECIMAL(7,2)";
  $sql .= ")";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}
$val1 = $_POST["brand"];
$val2 = $_POST["model"];
$val3 = $_POST["type"];
$val4 = $_POST["price"];
$val5 = $_POST["water"];
$sql = "INSERT INTO `lesson01`.`table13` (`id`, `brand`, `model`, `type`, `price`, `water`) VALUES (NULL, '$val1', '$val2', '$val3', '$val4', '$val5')";

$result = mysql_query ("$sql");
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}

?>