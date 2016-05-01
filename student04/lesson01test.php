<?php

// lesson01.php, George Corser, cis355, 2014-08-23
// Prints all items in column:name in table:table1022 of database:lesson01
// And adds random entry to table:table1022
// Step 1: ----- Connect to database -----

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table1022";
$yourfield = "name";
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);

// Step 2: ----- Check if any records in table -----

$query1022 = "SELECT * FROM $usertable";
$result1022 = mysql_query($query1022);

// Step 3: ----- If records, print name field and add another random record

if($result1022) { // if $result1022 is empty there is no output and no message
  while($row = mysql_fetch_array($result1022)){
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>"; // generates html code
  }
  $val1 = "Liv".rand();
  $val2 = rand();
  # INSERT INTO `lesson01`.`table1022` (`id`, `name`, `desc`) VALUES (NULL, 'delta', 'fourth');
  $query1022 = "INSERT INTO `lesson01`.`table1022` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
  $result10222 = mysql_query($query1022);
  # echo "<br>".$result1022;
  # echo "<br>Done<br>";
  # printf("Last inserted record has id %d\n", mysql_insert_id());

}

?>