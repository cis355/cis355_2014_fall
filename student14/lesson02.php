<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["type"]."</td></tr>
<tr><td width=\"35%\">desc</td><td>".$_REQUEST["brand"]."</td></tr>
<tr><td width=\"35%\">name</td><td>".$_REQUEST["model"]."</td></tr>
<tr><td width=\"35%\">desc</td><td>".$_REQUEST["color"]."</td></tr>
<tr><td width=\"35%\">name</td><td>".$_REQUEST["strWind"]."</td></tr>
<tr><td width=\"35%\">desc</td><td>".$_REQUEST["price"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lwip";
$usertable="table14";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('select 1 from `table14`');
if($val == FALSE){
  $sql = "CREATE TABLE table14 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
  $sql .= "type VARCHAR(20),";
  $sql .= "brand VARCHAR(30),";
  $sql .= "model VARCHAR(20),";
  $sql .= "color VARCHAR(30),";
  $sql .= "strWind VARCHAR(20),";
  $sql .= "price VARCHAR(30)";
  $sql .= ")";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}
$val1 = $_POST["type"];
$val2 = $_POST["brand"];
$val3 = $_POST["model"];
$val4 = $_POST["color"];
$val5= $_POST["strWind"];
$val6 = $_POST["price"];
$sql = "INSERT INTO `lwip`.`table14` (`id`, `type`, `brand`,`model`,`color`,`strWind`, `price`) VALUES (NULL, '$val1', '$val2','$val3','$val4','$val5','$val6')";

$result = mysql_query ("$sql");
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}
?>