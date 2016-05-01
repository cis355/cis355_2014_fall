<?php
/**
 * Purpose: This file will;
 *          Give the users a way to navigate the sites pages.
 *
 * Author:  Kevin Stachowski
 * Date:    9/18/2014
 * Date:    10/11/2014
 * Notes:   This file is for simple site navigation.
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
	headers();
	showForm();
	footer();
}

/**
 * Purpose: This will display navigation links to the user
 * Pre:     none
 * Post:    Form links.
 */
Function showForm()
{
    $html = "<br><br><table class='table table-bordered'><tr><th>Link</th><th>Description</th></tr>
    <tr><td><a href='http://www.cis355.com/student16/LWIP/LWIP_Create.php'>Create</a></td><td>This will create the tables.</td></tr>
    <tr><td><a href='http://www.cis355.com/student16/LWIP/LWIP_ShowTables.php'>Show Tables</a></td><td>This will display the data in the tables.</td></tr>
    <tr><td><a href='http://www.cis355.com/student16/LWIP/sessionTest1.php'>Test Sessions </a></td><td>This is to test session vars.</td></tr>	
    <tr><td><a href='http://www.cis355.com/student16/LWIP/LWIP_NewLocation.php'>Insert a new location</a></td><td>This will allow a user to insert a new location.</td></tr> 
    <tr><td><a href='http://www.cis355.com/student06/etc/tables.php'>Table tool</a></td><td>This will allow a user to connect and perform a query.</td></tr> 	
    </table>"; 
        
    echo $html;
}