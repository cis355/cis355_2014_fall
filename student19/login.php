<?php



// lesson01.php, George Corser, cis355, 2014-08-23

// Prints all items in column:name in table:table01 of database:lesson01

// And adds random entry to table:table01



// Step 1: ----- Connect to database -----



$hostname = "localhost";

$username = "student";

$password = "learn";

$dbname = "lesson01";

$usertable = "table19";

$yourfield = "name";

$dField = "descr";


//con variable will hold connection return code.

$con = mysql_connect($hostname, $username, $password)

	or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");

mysql_select_db($dbname);



echo "Database: ".$dbname."<br>";

echo "Table: ".$usertable."<br><br>";



$query = "CREATE TABLE `lesson01`.`student19_table` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(25) NOT NULL , 

			 `desc` VARCHAR(40) NOT NULL , PRIMARY KEY (`id`) ) ENGINE = InnoDB CHARACTER SET utf8mb4 

			 COLLATE utf8mb4_general_ci";

$result = mysql_query($query);



// variables added into the database table "student19_table"

$name = "name".rand();

$description = rand();



//Inserts values into the table

$query2 = "INSERT INTO `lesson01`.`student19_table` (`id`, `name`, `desc`) VALUES (NULL, '$name', '$description')";

$result2 = mysql_query($query2);



//query to select all record from student19_table

$query3 = "SELECT * FROM $usertable";

$result3 = mysql_query($query3);



//if the table is not empty, print all of the records.

if($result3)

{

	while($row = mysql_fetch_array($result3))

	{

		$name = $row["$yourfield"];
		$descript = $row["$dField"];

		echo "Name: ".$name."<br> Descr: ".$dField."<br><br>"; //prints name then goes to next line

	}

}

?>