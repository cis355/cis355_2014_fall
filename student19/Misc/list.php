<?php

$hostname = "localhost";

$username = "student";

$password = "learn";

$dbname = "lesson01";

$usertable = "WhitfiNewTable_019";

$con = mysql_connect($hostname, $username, $password)

	or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
	
mysql_select_db($dbname);

$query3 = "SELECT * FROM $usertable";

$result3 = mysql_query($query3);



//if the table is not empty, print all of the records.

if($result3)

{

	while($row = mysql_fetch_array($result3))

	{

		$id = $row['id'];
		$brand = $row['brand'];
		$model = $row['model'];
		$price = $row['price'];
		$condition = $row['condition'];

		echo "ID: ".$id."<br> Brand: ".$brand."<br> model: ".$model."
		<br> price: ".$price."<br> condition: ".$condition."<br><br>"; //prints name then goes to next line

	}

}
?>