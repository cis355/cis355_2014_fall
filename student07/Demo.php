<?php
$hostname="newlocalhost";
$username="admin";
$password="fix";
$dbname="fixstudents";
$usertable="studen01";
$yourfield = "name";

$query = "INSERT INTO `fixstudents`.`student01` (`id`, `name`, `desc`) VALUES (`07`,`Lance`,`Fith`)";
$result1 = mysql_query($query);   

$query =  "SELECT* FROM $usertable";
$result2 = mysql_query($query);

  while($row = mysql_fetch_array($result2))
    {
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>";
	}
?>