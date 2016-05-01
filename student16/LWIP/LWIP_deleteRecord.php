<?php
/**
 * Purpose: This file will;
 *          Delete a record from a table in the LWIP project
 *
 * Author:  Kevin Stachowski
 * Date:    9/26/2014
 * Update:  09/30/2014
 * Notes:   These tables will be used by the LWIP project
 * 
 * External resources: This application depends on access to a mysql DB.
 */

require_once("LWIP_function.php");

/* iterate over the $[_GET] just we do with $[_POST] to get vars from the url. */
foreach($_GET as $key=>$value)
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
	/* bring vars from $[_GET] into local scope */
    global $table, $id;
    
	/* add the headers to the top of the html page. */
    headers();
    
	/* if there is a table and id in the url process the request. */
    if($table and $id)
    {
		/* Give the user a chance to cancel. */
		echo "<script>okPopUp();</script>";
		
        /* Create a new mysqli object, this opens the connection. */
        $mysqli = getMySQLi();
        
        /* the sql to be executed */
        $sql = "DELETE FROM $table WHERE $table"."_id=$id";

        /* This will execute the sql on the mysqli object. */
        if(executeSQL($mysqli,$sql))
		{
			echo "<br>$id deleted from table $table !<br>";
			//call java script redirect (from LWIP_Scripts.js)
			echo "<script>goToShowTables();</script>";
		}

        /* Close connection, call close method of the mysqli object */
        $mysqli->close();
		
    }
    else
    {
        echo "<br>There was no Table or ID sent in.<br>";
    }
    
	/* add the footers to the bottom of the html page. */
    footer();
}