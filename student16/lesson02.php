<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

/**
 * Purpose: This file will test different methods for connecting to a database.
 *
 * Author:  Kevin Stachowski
 * Date:    9/13/2014
 * Notes:   for CIS355 Fall 14
 */

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["name"]."</td></tr>
<tr><td width=\"35%\">descript</td><td>".$_REQUEST["descript"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="kjstacho_table";
$yourfield = "name";

main();

/**
 * This is the driver function for the application,
 */
function main()
{
	echo "<br><br><br>MySQL test:<br>";
	MySQLTest();
	
	echo "<br><br><br>PDO test:<br>";
	PDO_Test();
	
	echo "<br><br><br>MySQLi test:<br>";
	MySQLiTest();
}

/**
 * This will test the MySQLi methods by inserting and selecting a record with bond parameters.
 */
function MySQLiTest()
{
	/* pass in global vars to local scope */
	global $hostname, $username, $password, $dbname;
	
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
	/* check connection */
	if ($mysqli->connect_errno) {
		die('Unable to connect to database [' . $mysqli->connect_error. ']');
		exit();
	}
	echo "connected!<br>";
	
	
	/* test insert via object*/
	$stmt = $mysqli->stmt_init();
	if($stmt = $mysqli->prepare("INSERT INTO kjstacho_table (name,descript) VALUES (?, ?)"))
	{
		/* vars used to bind */
		$name = 'User';
		$descript = 'Test02';
		
		/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param('ss', $name,$descript);
		
		/* execute prepared statement */
		$stmt->execute();
		$stmt->close();
	}
	echo "inserted!<br>";
	
	/* test select via object */
	if($stmt = $mysqli->prepare("select * from kjstacho_table"))
	{
		/* execute prepared statement */
		$stmt->execute();
		/* bind result vars */
		$stmt->bind_result($id, $name, $descript);
		/* read while fetch returns data*/
		while($stmt->fetch()){
			/* work with bound result vars */
			echo $id." - ".$name." - ".$descript."<br>";
		}
		$stmt->close();
	}
	/* close connection */
	$mysqli->close();
}

/**
 * This will test the PDO methods by inserting a record with bond parameters.
 * It will then retrieve the data via an object
 */
function PDO_Test()
{
	/* pass in global vars to local scope */
	global $hostname, $username, $password, $dbname, $usertable;
	
	try {
		$pdo = new PDO("mysql:host=" . $hostname . ";dbname=" . $dbname, $username, $password);
		echo "connected!<br>";
	} 
	catch(PDOException $e) 
	{
		echo $e->getMessage();
	}
	
	$sql = "INSERT INTO ".$usertable." (name,
            descript) VALUES (
            :name,
            :descript)";
    /* prepared statement */                                     
	$stmt = $pdo->prepare($sql);
    /* Bind parameters with data types */                                          
	$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);      
	$stmt->bindParam(':descript', $_POST['descript'], PDO::PARAM_STR);  
    /* execute prepared statement */                                  
	$stmt->execute(); 
	echo "inserted!<br>";
	
	$sql= "SELECT * FROM ".$usertable." ";
	$stmt = $pdo->query($sql);
	/* read while fetchObject returns data */
	while($row =$stmt->fetchObject()){
		/* the row object return the whatever field names were selected as the property names! */
		echo $row->id." - ".$row->name." - ".$row->descript."<br>";
	}
}

/**
 * This will test the old MySQL methods.
 * It will create a table if needed.
 * Then insert a record with string concatenation of the parameters.
 */
function MySQLTest()
{
global $hostname, $username, $password, $dbname, $usertable;
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";
//$val = mysql_query('drop table '.$usertable);
$val = mysql_query('select 1 from '.$usertable);
if($val == FALSE){
  $sql = "CREATE TABLE ".$usertable." (";
  $sql .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
  $sql .= "name VARCHAR(20), ";
  $sql .= "descript VARCHAR(30)";
  $sql .= ");";

  
  echo "created table.";
  if(!($result))
  {
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
  }
  $result = mysql_query ("$sql");
}
$val1 = $_POST["name"];
$val2 = $_POST["descript"];
$sql = "INSERT INTO ".$usertable." (name, descript) VALUES ('$val1', '$val2')";
echo "<br>".$sql."<br>";
$result = mysql_query ($sql);
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}
}
?>