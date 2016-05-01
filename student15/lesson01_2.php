<?php
// lesson01_2.php, Caleb Miller, CIS 355, 2014-09-09// Creates table:table15 in database:lesson01
// Prints all items in column:name in table:table15 of database:lesson01
// And adds random entry to table:table15
// Step 1: Connect to database
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table15";
$yourfield = "name";
$con = mysql_connect($hostname, $username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect to database $dbname.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "Connected to database $dbname successfully!<br>";
echo "<br>";
// Step 2: Create table
$query = "CREATE TABLE IF NOT EXISTS `lesson01`.`table15` ( `id` INT NOT NULL AUTO_INCREMENT ,
                                                            `name` VARCHAR(20) NOT NULL ,
                                                            `desc` VARCHAR(30) NOT NULL ,
                                                             PRIMARY KEY (`id`) )
          ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
$result = mysql_query($query);
// Step 3: Check if any records in table
$query = "SELECT * FROM $usertable";
$result = mysql_query($query);
// Step 4: Print name field and add another random record
// If $result is empty there is no output and no message
if (mysql_num_rows($result) <> 0) {
  while($row = mysql_fetch_array($result)){
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>"; // generates html code
  }  echo "<br>";  
  $val1 = "name".rand();
  $val2 = rand();
  $query = "INSERT INTO `lesson01`.`table15` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
  $result = mysql_query($query);
}
else {
  // Insert first record into table
  $val1 = "First Record";
  $val2 = rand();
  $query = "INSERT INTO `lesson01`.`table15` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
  $result = mysql_query($query);
}
echo $result;
echo "<br>Processing Done. Record Added.<br>";
printf("Last inserted record has ID %d\n", mysql_insert_id());
?>

