
<?php

// lesson01.php, George Corser, cis355, 2014-08-23
// Prints all items in column:name in table:table01 of database:lesson01
// And adds random entry to table:table01

// Step 1: ----- Connect to database -----

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table01";
$usertable2="kjstacho_table";
$yourfield = "name";
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);

$query = "SELECT * FROM $usertable2";
$result = mysql_query($query);

// Step 3: ----- If records, print name field and add another random record

if($result) { // if $result is empty there is no output and no message
  while($row = mysql_fetch_array($result)){
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>"; // generates html code
  }
  
}
?>

