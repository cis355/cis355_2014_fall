<?php

// lesson01.php
// George Corser, cis355, 2014-08-23
// Prints all items in column:name in table:table01 of database:lesson01
// Adds random entry to table:table01

// Step 1: ----- Connect to database -----

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table01";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection<br>";

// Step 2: ----- Check if any records in table -----

$query = "SELECT * FROM $usertable";

$result = mysql_query($query);

if($result) // if $result is empty there is no output and no message
{
  while($row = mysql_fetch_array($result))
  {
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>"; // generates html code
  }
  $val1 = "name".rand();
  $val2 = rand();
  # INSERT INTO `lesson01`.`table01` (`id`, `name`, `desc`) VALUES (NULL, 'delta', 'fourth');
  $query = "INSERT INTO `lesson01`.`table01` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
  $result2 = mysql_query($query);
  echo "<br>".$result;
  echo "<br>Done<br>";
  # printf("Last inserted record has id %d\n", mysql_insert_id());
}
?>

