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
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);

// Step 2: ----- Check if any records in table -----

$query = "INSERT INTO $usertable (first_name, last_name, middle_name, gender) VALUES ('John', 'Smith', 'Appleseed', 0)";

if( mysql_query( $query ) )
	echo 'Sucessfully added row to table.' . "\r\n";
else
	die( 'Failed to add row to table.' . "\r\n" );

$query = "SELECT * FROM $usertable";
$result = mysql_query($query);

if( $result ) {
	
	echo "<ul>";
	
	while( $rows = mysql_fetch_array( $result ) ) {
		foreach ( $rows as $row ) {
			echo "<li>" . print_r( $row ) . "</li>";
		}
	}

	echo "</ul>";

} else
	die("ERROR: Could not retrieve any records from table \"$usertable\".");

?>