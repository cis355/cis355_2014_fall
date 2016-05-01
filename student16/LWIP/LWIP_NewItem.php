<?php
/**
 * Purpose: This file will;
 *          Add a new item to the items table
 *
 * Author:  Kevin Stachowski
 * Date:    9/21/2014
 * Update:  10/10/2014
 * Notes:   
 * 
 * External resources: This application depends on access to a mysql DB.
 */

/** This will include all functions and global vars from the file.
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
    global $title;
	
	//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
	
	if(!isset($_GET["id"]) && !isset($_SESSION["userID"]) && !isset($_SESSION["id"]))
    {
		header('Location: ../../student14/login.php');
    }
    
    headers();
    
    $mysqli = getMySQLi();
    
    if(!$title)
    {
        showForm($mysqli);
        footer();
        exit();
    }
		
    /* Insert Data into table, pass the mysqli object to the function. */
    insertData($mysqli);

    /* Close connection, call close method of the mysqli object */
    $mysqli->close();
    
    footer();
}

/**
 * Purpose: This function will select data from the table
 * Pre:     The MySQLi object must be connected.
 * Post:    The data is displayed or feedback is given on why it didn't.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function insertData($mysqli)
{
    global $title, $description, $price, $kjstacho_LWIP_Locations_id;
	
    $sql = "insert into ".itemsTable." (kjstacho_LWIP_Users_id,kjstacho_LWIP_Locations_id,title,description,price) VALUES(?,?,?,?,?)";
    if($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param('iissd', $_SESSION["userID"], $kjstacho_LWIP_Locations_id, $title, $description, $price);
        if($stmt->execute())
        {
            echo "Data has been added to ".itemsTable.".<br>";
            $stmt->close();
			//call java script redirect (from LWIP_Scripts.js)
			echo "<script>goToShowTables();</script>";
        }
        elseif(errorsOn)
        {
            echo "Insert execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }
    elseif(errorsOn)
    {
        echo "Insert Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

/**
 * Purpose: This will generate the html form for the user. 
 * Pre:     It should be called when there is no form post data.
 * Post:    The form string needs to be displayed to the user.
 */
function showForm($mysqli)
{
     echo "<h2>New Item</h2><form name='item_insert' method='Post' action='LWIP_NewItem.php' '>
        <table class='table table-bordered'>
        <tr><td>Title</td><td><input name='title' value='' size=20></td></tr>
        <tr><td>Desription</td><td><input name='description' value='' size=30></td></tr>
        <tr><td>Price</td><td><input name='price' value='' size=30></td></tr>
        <tr><td>Location</td><td><select name='kjstacho_LWIP_Locations_id'>";
        
    if($result = $mysqli->query("select * from locations"))
    {
        /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
        while($row = $result->fetch_object())
        {
			/* display option for the select */
			if($row->location_id == $_SESSION['location'])
			{
				echo "<option value=".$row->location_id." selected>".$row->name."</option>";
			}
			else
			{
				echo "<option value=".$row->location_id.">".$row->name."</option>";
			}
        }
        $result->close();
    }
    else
    {
        echo "Create Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        exit();
    }
    
    echo "</select></td></tr>
        <tr><td align=center><input class='btn btn-lg-primary' type='reset' name='Reset' value='Reset'></td>
		<td align=center><input type=submit name='Submit' value='Submit' class='btn btn-lg-primary'></td></tr>
        </table></form>";
}