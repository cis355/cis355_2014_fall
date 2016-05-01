<?php
#To be used for manipulating the table as needed.

$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table09";

$con = mysql_connect($hostname,$username,$password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$mysqli = new mysqli($hostname, $username, $password, $dbname);

/*
	    $sql = "CREATE TABLE table09 
		       (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "year VARCHAR(20),";
	    $sql .= "make VARCHAR(30),";
	    $sql .= "model VARCHAR(20),";
	    $sql .= "vcondition VARCHAR(30),";
	    $sql .= "price_paid VARCHAR(100),";
	    $sql .= "location_id INT,";
	    $sql .= "user_id INT,";
	    $sql .= "FOREIGN KEY (location_id) REFERENCES locations (location_id),";
        $sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
			echo "executed!";
        }
		else
        {
			$stmt = $mysqli->prepare($sql);
            $stmt->execute();
			echo "executed!";
        }

*/		
/*
	if($result = $mysqli->query("SELECT * FROM locations"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7];
		}
	}

	
	if($result = $mysqli->query("SELECT * FROM users"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7];
		}
	}

*/
	
/*
echo "Adding foreign key";
$sql = "ALTER TABLE $usertable ADD FOREIGN KEY (location_id) REFERENCES locations(location_id)";
$result = mysql_query("$sql");
echo $result;
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	
*/

/*
echo "Altering Table";
$sql = "ALTER TABLE $usertable MODIFY COLUMN location_id INT(10) NOT NULL";
$result = mysql_query("$sql");
echo $result;
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	
*/

/*
echo "Adding table";
$sql = "CREATE TABLE $usertable (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
$sql .= "user_id VARCHAR(20),";
$sql .= "location_id VARCHAR(30),";
$sql .= "year VARCHAR(20),";
$sql .= "make VARCHAR(30),";
$sql .= "model VARCHAR(30),";
$sql .= "vcondition VARCHAR(30),";
$sql .= "price_paid VARCHAR(20)";
$sql .= ")";
$result = mysql_query("$sql");
echo $result;
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	
*/		
/*
$sql = "SELECT * FROM information_schema.columns WHERE  table_name = 'table09' ORDER  BY ordinal_position ";
$result = mysql_query("$sql");
echo $result;

echo "BREAK HERE";
*/

/*
$sql = "drop TABLE table09";
$result = mysql_query("$sql");
echo $result;

  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	 
*/

/*
$sql = "delete from $usertable";
$result = mysql_query("$sql");
echo $result;


  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	 
$sql = "Select * from $usertable";
$result = mysql_query("$sql");
echo $result;

*/


$sql = "INSERT INTO table09 (year,make,model,vcondition,price_paid,user_id,location_id) VALUES('2014','Dodge','Charger','New','30000','7792','4')";
$result = mysql_query("$sql");




  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
	 


/*
$sql = "Select * from $usertable";
$result = mysql_query("$sql");
echo $result;
*/
?>