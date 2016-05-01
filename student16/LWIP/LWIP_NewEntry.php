<?php
/**
 * Purpose: This file will;
 *          Show the form to insert data into the entry form
 *
 * Author:  Kevin Stachowski
 * Date:    9/21/2014
 * Update:  09/30/2014
 * Notes:   This page will display both the html form and insert the data. 
 * 
 * External resources: This application depends on access to a mysql DB.
 */

 require_once("LWIP_function.php");

foreach($_POST as $key=>$value)
{
    /** The $key is the named index in $_POST, $value is that indexes value. 
     *  Adding another $ to $key turns it into a variable variable name. 
    **/
    $$key=$value;
}

main();

 /**
 * Purpose: This is the driver function for the application.
 * Pre:     none
 * Post:    page has been processed.
 */
function main()
{
	/* bring vars into local scope */
    global $user;
    
    headers();
    $mysqli = getMySQLi();
    
    /* If there is posted data (if $user has a value)*/
    if(!$user)
    {
        showForm($mysqli);
    }
    else
    {
        /* Insert Data into table, pass the mysqli object to the function. */
        insertData($mysqli);
    }

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
	/* bring vars into local scope */
    global $user, $comment, $price, $kjstacho_LWIP_Items_id;
	
    $sql = "insert into ".entryTable." (user,comment,price,kjstacho_LWIP_Items_id) VALUES(?,?,?,?)";
    /* Prepare the statement */
	if($stmt = $mysqli->prepare($sql))
    {
		/* bind params for the items table */
        $stmt->bind_param('ssdi', $user, $comment, $price, $kjstacho_LWIP_Items_id);
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
    echo "<h2>New Entry</h2><form name='entry_insert' method='Post' action='LWIP_NewEntry.php' '>
        <table class='table table-bordered'>
        <tr><td>User</td><td><input name='user' value='' size=20></td></tr>
        <tr><td>Comment</td><td><input name='comment' value='' size=30></td></tr>
        <tr><td>Price</td><td><input name='price' value='' size=30></td></tr>
        <tr><td>Item Key</td><td><select name='kjstacho_LWIP_Items_id'>";
        
    if($result = $mysqli->query("select * from ".itemsTable))
    {
        /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
        while($row = $result->fetch_object())
        {
			/* display option for the select */
            echo "<option value=".$row->kjstacho_LWIP_Items_id.">".$row->title." - ".$row->description."</option>";
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