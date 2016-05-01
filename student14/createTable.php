<?php
	$hostname="localhost";
	$username="student";
	$dbname = "lesson01";
	$password="learn";
	$yourfield = "name";
	$con = mysql_connect($hostname,$username, $password) 
		or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
	
	mysql_select_db($dbname);
	
	/*$sql = "CREATE TABLE `lesson01`.`student14` ( `id` INT NOT NULL AUTO_INCREMENT , `fname` VARCHAR(20) NOT NULL , `lname` VARCHAR(20) NOT NULL , `desc` VARCHAR(30), PRIMARY KEY (`id`) ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
	
	$result = mysql_query($sql)
	
	if($result != false)
		echo "<p>It worked!</p><br>";*/
		
	$insertQ = "INSERT INTO `lesson01`.`student14` (`id`, `fname`, lname`, `desc`) VALUES (NULL, 'Zachary', 'Metiva', 'Cool Beans')";
	
	$result1 = mysql_query($insertQ) or die ("<p>Nope</p>");
	
	if($result1 != false)
		echo "<p>It worked!</p><br>";
	
	echo "<table border='1'><tr><td>First Name</td><td>Last Name</td><td>Description</td></tr>";
	$query = "SELECT * FROM `student14`";
	$result2 = mysql_query($query);
	if($result2) { // if $result is empty there is no output and no message
		while($row = mysql_fetch_array($result)){
			$fname = $row['fname'];
			$lname = $row['lname'];
			$desc = $row['desc'];
			
			echo "<tr><td>".$fname."</td><td>".$lname."</td><td>".$desc."</td></tr>"; // generates html code
		}
		echo "</table>";
	}
?>
	
	