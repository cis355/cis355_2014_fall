
  
  
<?php

// lesson01.php, George Corser, cis355, 2014-08-23
// Prints all items in column:name in table:table01 of database:lesson01
// And adds random entry to table:table01

// Step 1: ----- Connect to database -----

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="kjstacho_table";
$yourfield = "name";
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);

  $query = "INSERT INTO $usertable (`id`, `name`, `desc`) VALUES (NULL, 'Kevin', 'me')";
  $result2 = mysql_query($query);
  
  echo "inserted";
?>