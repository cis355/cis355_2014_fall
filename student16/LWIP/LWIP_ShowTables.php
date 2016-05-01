<?php
/**
 * Purpose: This file will;
 *          Show the data in the tables used in the LWIP project
 *
 * Author:  Kevin Stachowski
 * Date:    09/21/2014
 * Update:  10/11/2014
 * Notes:   These tables will be used by the LWIP project
 * 
 * External resources: This application depends on access to a mysql DB.
 */
require_once("LWIP_function.php");

main();

 /**
 * Purpose: This is the driver function for the application.
 * Pre:     none
 * Post:    page has been processed.
 */
function main()
{
	/* add the headers to the top of the html page. */
    headers();
    
	//display all vars
	//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

    // Make sure that we received some user data or they are already logged in.
    if(!isset($_GET["id"]) && !isset($_SESSION["userID"]) && !isset($_SESSION["id"]))
    {
		//uncomment to require a login.
        //echo "<script>alert('Please log in first!'); goToNewLogin();</script>";
        //exit;
    }
    
    // since this is the first page really loaded since login, 
    // this is the first chance we have to set our session vars.
    // But only if they arent already set...
    if(!isset($_SESSION["userID"]))
    {
		if(isset($_SESSION["id"]))
		{
			$_SESSION["userID"] = $_SESSION["id"];
			$_SESSION["email"] = $_SESSION["user"];
		}
		else if(isset($_GET["id"]))
		{
			$_SESSION["userID"] = $_GET["id"];
			$_SESSION["email"] = $_GET["user"];
		}
    }
    
	if(isset($_SESSION["userID"]))
	{
		//echo "<br>Logged in as...";
		//echo "<br>userid: ".$_SESSION["userID"];
		//echo "<br>email: ".$_SESSION["email"];
	}
	
    /* Create a new mysqli object, this opens the connection. */
    $mysqli = getMySQLi();
		
    /* Select and display Data from the tables, pass the mysqli object to the function. */
    selectTable($mysqli,itemsTable);
    selectTable($mysqli,commentsTable);
    //selectTable($mysqli,locationsTable);
    //selectTable($mysqli,usersTable);
	
	/* Example of a join query, display joined data. */
    //showJoin($mysqli);

    /* Close connection, call close method of the mysqli object */
    $mysqli->close();
    
	/* add the footers to the bottom of the html page. */
    footer();
}

/**
 * Purpose: This will fectch and display data from a join query.
 * Pre:     none
 * Post:    data is displayed on the html page.
  * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function showJoin($mysqli)
{
    $sql = "select * from ".itemsTable." left join ".entryTable." on ".itemsTable.".".itemsTable."_id = ".entryTable.".".itemsTable."_id";
    $stmt = executeSQL($mysqli,$sql);
    
    $stmt->bind_result($idItem, $title, $userI, $description, $insertDateI, $idEntry, $user, $comment, $price, $kjstacho_LWIP_Items_id, $insertDateE);
    /* read while fetch returns data*/
	echo "<h2>Data joined from table ".itemsTable." and ".entryTable.".</h2><br>";
    echo "<table class='table table-bordered'><tr><th>Item ID</th><th>Title</th><th>User</th><th>Description</th><th>Item InsertDate</th><th>Entry ID</th><th>User</th><th>Comment</th><th>Price</th><th>kjstacho_LWIP_Items_id</th><th>Entry InsertDate</th></tr>";
    while($stmt->fetch()){
        /* work with bound result vars */
        echo "<tr><td>$idItem</td><td>$title</td><td>$userI</td><td>$description</td><td>$insertDateI</td><td>$idEntry</td><td>$user</td><td>$comment</td><td>$price</td><td>$kjstacho_LWIP_Items_id</td><td>$insertDateE</td></tr>";
    }
    echo "</table><br>";
    $stmt->close();
}


/**
 * Purpose: This function will select data from the table
 * Pre:     The MySQLi object must be connected.
 * Post:    The data is displayed or feedback is given on why it didn't.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function selectTable($mysqli,$usertable)
{
	/* get data from the table passed in. */
    if(!($stmt = executeSQL($mysqli,getTableSQL($usertable))))
	echo "<script>window.location = 'http://www.cis355.com/student16/LWIP/LWIP_Create.php';</script>";
	
    echo "<hr><h2>Data selected from table $usertable.</h2><br>";
    if($usertable == itemsTable)
    {   
		/* bind results for the items table. */    
        $stmt->bind_result($tableId, $userId, $locID, $title, $description, $price, $insertDate);
        /* read while fetch returns data*/
        echo "<table class='table table-bordered'><tr><th>ID</th><th>User ID</th><th>Location ID</th><th>Title</th><th>Description</th><th>Price</th><th>InsertDate</th><th></th><th></th><th></th></tr>";
        while($stmt->fetch()){
                /* work with bound result vars */
                echo "<tr><td>$tableId</td><td>$userId</td><td>$locID</td><td>$title</td><td>$description</td><td>$price</td><td>$insertDate</td>";
				showBtns($userId,$usertable,$tableId);
        }
    }
    elseif($usertable == commentsTable)
    {
		/* bind results for the entry table. */  
        $stmt->bind_result($tableId, $itemID, $userID, $comment, $insertDate);
        /* read while fetch returns data*/
        echo "<table class='table table-bordered'><tr><th>ID</th><th>Item ID</th><th>User ID</th><th>Comment</th><th>InsertDate</th><th></th><th></th><th></th></tr>";
        while($stmt->fetch()){
                /* work with bound result vars */
                echo "<tr><td>$tableId</td><td>$itemID</td><td>$userID</td><td>$comment</td><td>$insertDate</td>";
				showBtns($userID,$usertable,$tableId);
        }
    }
    elseif($usertable == locationsTable)
    {
		/* bind results for the entry table. */  
        $stmt->bind_result($tableId, $zip,$zipDescription, $insertDate);
        /* read while fetch returns data*/
        echo "<table class='table table-bordered'><tr><th>ID</th><th>Zip</th><th>Zip Description</th><th>InsertDate</th><th></th><th></th></tr>";
        while($stmt->fetch()){
                /* work with bound result vars */
                echo "<tr><td>$tableId</td><td>$zip</td><td>$zipDescription</td><td>$insertDate</td>";
				showBtns($_SESSION["userID"],$usertable,$tableId);
        }
    }
    elseif($usertable == usersTable)
    {
	/* bind results for the entry table. */  
        $stmt->bind_result($tableId, $kjstacho_LWIP_Locations_id,$email, $password, $insertDate);
        /* read while fetch returns data*/
        echo "<table class='table table-bordered'><tr><th>ID</th><th>email</th><th>password</th><th>location ID</th><th>InsertDate</th><th></th><th></th></tr>";
        while($stmt->fetch()){
                /* work with bound result vars */
                echo "<tr><td>$tableId</td><td>$email</td><td>$password</td><td>$kjstacho_LWIP_Locations_id</td><td>$insertDate</td>";
				showBtns($tableId,$usertable,$tableId);
        }
    }
	
	/* show the links to the items insert form. */  
    if (strpos($usertable, 'Items') !== FALSE)
    {
        $link = "http://www.cis355.com/student16/LWIP/LWIP_NewItem.php";
    }
	/* show the links to the entry insert form. */
    elseif (strpos($usertable, 'Comments') !== FALSE)
    {
        $link = "http://www.cis355.com/student16/LWIP/LWIP_NewComment.php";
    }
	elseif (strpos($usertable, 'Locations') !== FALSE)
    {
        $link = "http://www.cis355.com/student16/LWIP/LWIP_NewLocation.php";
    }
	
    echo "</table>";
	
	//Dont show the add button on users.
	if($usertable != usersTable)
	{
		echo "<h2><a class='label label-success' href=$link>New Record</a></h2><br><br>";
	}
    /* close the statement object. */
	$stmt->close();
        
}

/** Purpose: This table will create the sql statements for creating the tables.
*   Pre:     This is called before the create table function is called.
*   Post:    The sql for the correct table has been generated. It still needs to be executed.
* 
*   @return  This will return a string of sql used to create a table.
*/
function getTableSQL($usertable)
{
    return "SELECT * FROM $usertable";
}

/** Purpose: This will show the update and delete buttons
*   Pre:     the row data has been retreived from mysql
*   Post:    the update and delete buttons are displayed for the logged in user.
* 
*   @param var  $usertable hold the table to update or delete.
*   @param var  $tableId hold the id on the table to update or delete.
*/
function showBtns($ID,$usertable,$tableId)
{
	echo "<td><a class='label label-success' href='http://www.cis355.com/student16/LWIP/LWIP_updateRecord.php?table=$usertable&id=$tableId&enabled=false'>View</a></td>"; 
	if($ID == $_SESSION["userID"])
		{
			echo "<td><a class='label label-info' href='http://www.cis355.com/student16/LWIP/LWIP_updateRecord.php?table=$usertable&id=$tableId'>Update</a></td>
			<td><a class='label label-danger' href='http://www.cis355.com/student16/LWIP/LWIP_deleteRecord.php?table=$usertable&id=$tableId'>Delete</a></td></tr>";
		}
		else
		{
			echo "<td></td><td></td></tr>";
		}
}


