<!--
This page is used to test server side functionality using PHP

Author: Kevin Stachowski
Date: 08/26/2014
-->

<html>
<title>Kevins Page</title>

<?php

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="kjstacho_table";


//import class
include_once('toolBox.php');
//connection string to the DB
$db=mysqli_connect($hostname,$username,$password,$dbname);
//main function
main($db);

/**
 * This is the driver function for the application
 *
 * @param   var   $db  contains the connection object
 */
function main($db)
{
    printHeader();
    connect($db);
    getData($db);
	playWithObjects();
    echo "<br>Done!<br>";
}

/**
 * This will print the header info
 */
function printHeader()
{
	echo '<p><b>Hello ' . htmlspecialchars($_POST["name"]) . '!</b></p>';
    echo 'CIS355 Project<br>';
}

/**
 * This will connect to the MySQL DB
 */
function connect($db)
{
    echo 'Connecting to DB...<br>';
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }
    echo "Connected!<br>";
}

/**
 * This query for data
 */
function getData($db)
{
    echo "Getting data...<br>";
    $sql = <<<SQL
    SELECT *
    FROM kjstacho_table
SQL;
    //fetch the data from the database
    if(!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    }
    while($row = $result->fetch_assoc()){
        echo "ID: ".$row['id']." Name: ".$row['name']." ".$row['desc']."<br>";
    }
}

/**
 * This will close the MySQL Connection
 */
function closeConn($db)
{
    echo "Closing connection...<br>";
    mysqli_close($db);
}

/**
 * This will instantiate objects and call methods and access properties.
 */
function playWithObjects()
{
	echo "<br>call method via instantiated object<br>";
	$MyCIS355 = new CIS355();
	$test = $MyCIS355->sayHello();
	
	echo $test;
	
	echo "<br>call method directly<br>";
	echo CIS355::sayHello();
	
	echo "<br>assign and call property<br>";
	$MyInventory = new Inventory();
	$MyInventory->A = "test";
	echo "<br>";
	echo "obj = ".$MyInventory->A;
	
	$json = json_encode($MyInventory);
	echo "<br>php obj to JSON = ".$json;
	
	echo "<br><br>json back to php object = ";
	$phpObj = json_decode($json);
	print_r($phpObj);
}
?>
</html>