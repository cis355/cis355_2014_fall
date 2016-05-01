
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

// Step 2: ----- Check if any records in table -----

$query = "SELECT * FROM $usertable";
$result = mysql_query($query);
echo "Herro".<br>;

$query002cb = CREATE TABLE `lesson01`.`Picnic` ( `id` INT NOT NULL AUTO_INCREMENT , 
                        `name` VARCHAR(20) NOT NULL , `desc` VARCHAR(30) NOT NULL , 
						PRIMARY KEY (`id`) ) ENGINE = InnoDB CHARACTER SET utf8mb4 
						COLLATE utf8mb4_general_ci;
$result002cb = mysql_query($query);

$val1 = "CodyBrownTable";
$val2 = "Cody B Table";

$query0022cb = "INSERT INTO `lesson01`.`Picnic` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
$mysqp_query($query0022cb);

$query00222cb = "SELECT * FROM $Picnic";
$result00222cb = mysql_query($query00222cb);

  while($row = mysql_fetch_array($result00222cb))
  {
    $name = $row["$yourfield"];
    echo "Name: ".$name00222cb."<br>"; // generates html code
  }

// Step 3: ----- If records, print name field and add another random record

if($result) 
{ // if $result is empty there is no output and no message
  while($row = mysql_fetch_array($result)){
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>"; // generates html code
  }
  $val1 = "Cody";
  $val2 = "Cody B";
  # INSERT INTO `lesson01`.`table01` (`id`, `name`, `desc`) VALUES (NULL, 'delta', 'fourth');
  $query = "INSERT INTO `lesson01`.`table01` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
  $result2 = mysql_query($query);
  # echo "<br>".$result;
  # echo "<br>Done<br>";
  # printf("Last inserted record has id %d\n", mysql_insert_id());
  
}
?>

