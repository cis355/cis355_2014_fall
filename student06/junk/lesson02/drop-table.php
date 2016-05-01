<?php

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table21";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

if( mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$usertable."'")) == 1 ){ 
  $sql = "DROP TABLE $usertable";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	else
	 echo "Table dropped";
}

?>