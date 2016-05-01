<?php
// lesson01-25.php, George Corser, cis355, 2014-09-11
// Prints all items in column:name in table:table25 of database:lesson01

// Step 1: ----- Connect to database -----

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table25";
$yourfield = "name";
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "Success!<br><br>";

// Step 2: ----- Get records from table -----

$query = "SELECT * FROM $usertable;";

$result = mysql_query($query);

  while($row = mysql_fetch_array($result)){
    $id = $row["id"];
	$name = $row["name"];
	$descr = $row["descr"];
    echo $id." ".$name." ".$descr."<br>"; // generates html code
  }

?>

