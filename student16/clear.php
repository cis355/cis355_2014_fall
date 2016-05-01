
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
$yourfield = "name";
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);

  $query = "delete from table01 where name like '%Kevi%'";
  $result2 = mysql_query($query);
  
  echo "Cleared";

  $query = "CREATE TABLE `lesson01`.`kjstacho_table` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(20) NOT NULL , `desc` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`) ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
  $result2 = mysql_query($query);
  
  echo "Created";
?>

