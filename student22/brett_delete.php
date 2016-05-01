<?php

/** Test php form
* Author: Brett Yeager
* cis355
* 9/25/2014
* deleting a temp form and table
**/

/* Garbing form data (if any) */
foreach($_POST as $key=>$value)
{
    $$key=$value;
}

/* if there is no data, display the form */
if(!$type_b)
{
    echo showForm();
}
else
{
    echo "<p>Trying to delete a record";
    
    /** These vars will be part of the global collection.
     *  These will need to be changed to your respective DB and table.
     */
    $hostname="localhost";
    $username="user01";
    $password="cis355lwip";
    $dbname="lwip";
    $usertable="table22";

    /* main entry point for processing.*/
    main();
}

function main() {
	global $hostname, $username, $password, $dbname;
	
    /* Create a new mysqli object, this opens the connection. */
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
	
	checkConnection($mysqli);
	
	delete_record($mysqli);
}

function checkConnect($mysqli)
{
    
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
    echo "Connected!<br>";
}

function delete_record($mysqli) {
	global $usertable, $rec_name;
	
	$sql = "DELETE FROM $usertable ";
	$sql .="WHERE id= ?";
	
	$stmt = $mysqli->stmt_init();
	
	if($stmt = $mysqli->prepare($sql))
	{
		$stmt->bind_param('i' $rec_name);
		
		/* execute prepared statement */
        $stmt->execute();
        /* close statment */
        $stmt->close();
	}	
}