
<?php

// lesson01.php, George Corser, cis355, 2014-08-23
// Prints all items in column:name in table:table01 of database:lesson01
// And adds random entry to table:table01

// Step 1: ----- Connect to database -----

$hostname = "localhost";
$username = "student";
$password = "learn";
$dbname = "lesson01";
$usertable = "chapin_table";
$yourfield = "Nicholas Chapin";
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);

// Step 2: ----- Check if any records in table -----

$query = "CREATE TABLE $usertable( ".
       "id INT NOT NULL AUTO_INCREMENT, ".
       "first_name VARCHAR(100) NOT NULL, ".
       "middle_name VARCHAR(100) NOT NULL, ".
       "last_name VARCHAR(40) NOT NULL, ".
       "gender INT NOT NULL, ".
       "birthday DATE, ".
       "PRIMARY KEY ( id )); ";
mysql_query($query);

if( mysql_query($query) )
	echo 'Sucessfully created table "' . $usertable . '".';
else
	echo 'Failed to create table "' . $usertable . '".';
  

?>