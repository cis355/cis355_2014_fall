<?php
/**
 * Purpose: This file will;
 *          Add a new location to the locations table
 *
 * Author:  Kevin Stachowski
 * Date:    10/11/2014
 * Update:  10/11/2014
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
    global $zip;
    
    headers();
    
    if(!$zip)
    {
        showForm();
        footer();
        exit();
    }
    
    $mysqli = getMySQLi();
		
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
    global $zip, $zipDescription;
	
    $sql = "insert into ".locationsTable." (zip,zipDescription) VALUES(?,?)";
    if($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param('ss', $zip, $zipDescription);
        if($stmt->execute())
        {
            echo "Data has been added to ".locationsTable.".<br>";
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
function showForm()
{
     echo "<h2>New Location</h2><form name='location_insert' method='Post' action='LWIP_NewLocation.php' '>
        <table class='table table-bordered'>
        <tr><td>Zip</td><td><input name='zip' value='' size=20></td></tr>
        <tr><td>Zip Desription</td><td><input name='zipDescription' value='' size=30></td></tr>
        <tr><td align=center><input class='btn btn-lg-primary' type='reset' name='Reset' value='Reset'></td>
	<td align=center><input type=submit name='Submit' value='Submit' class='btn btn-lg-primary'></td></tr>
        </table></form>";
}