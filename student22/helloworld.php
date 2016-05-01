<?php

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table01";
$yourfield = "name";

$con = mysql_connect($hostname, $username, $password)
	or die("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
	
mysql_select_db($dbname);

$query = "INSERT INTO `Student22`.`lesson01` (`id`, `name`, `desc`) VALUES (NULL, Brett, cool)";
$result = mysql_query($query);
$query2 = "SELECT * FROM Student22";
$result2 = mysql_query($query2);
echo $result2;
echo "HI";
/* if($result) { // if $result is empty there is no output and no message
  
  $val1 = "name".rand();
  $val2 = rand();
  # INSERT INTO `lesson01`.`table01` (`id`, `name`, `desc`) VALUES (NULL, 'delta', 'fourth');
  $query = "INSERT INTO `Student22`.`lesson01` (`id`, `name`, `desc`) VALUES (NULL, '$val1', '$val2')";
  $result2 = mysql_query($query);
  
  while($row = mysql_fetch_array($result2)){
    $name = $row["$yourfield"];
    echo "Name: ".$name."<br>"; // generates html code
  }
  # echo "<br>".$result;
  # echo "<br>Done<br>";
  # printf("Last inserted record has id %d\n", mysql_insert_id());
  echo "Brian loves gui's";
  
}
else */
	//echo $result . " blarg";
?>