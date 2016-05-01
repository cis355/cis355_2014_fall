<?php
/**
 * Purpose: This file will;
 *          Show the login page of the LWIP
 *
 * Author:  Kevin Stachowski
 * Date:    10/07/2014
 * Notes:   This will be both the new user and login page.
 * 
 * External resources: This application depends on access to a mysql DB.
 */
header("Location: ../student16/LWIP/LWIP_ShowTables.php");
require_once("LWIP_function.php");

main();

 /**
 * Purpose: This is the driver function for the application.
 * Pre:     none
 * Post:    page has been processed.
 */
function main()
{
    // on load unset and destory any session vars.
    session_unset();
    session_destroy();
    
    headers();
    showForm();
    footer();
}

/**
 * Purpose: This will generate the html form for the user. 
 * Pre:     It should be called when there is no form post data.
 * Post:    The form string needs to be displayed to the user.
 */
function showForm()
{
    $mysqli = getMySQLi();
    echo "<h2>LWIP Login Page</h2>
        <table class='table table-bordered'>
        <tr><td>Email</td><td><input type='edit' id='email' name='email' value='' size=30></td></tr>
        <tr><td>Password</td><td><input type='password' id='password' name='password' value='' size=30></td></tr>
        <tr><td></td><td><button class='btn btn-lg-primary' id=btnLogin onclick='checkLogin()'>Ajax Login</button></td></tr>
        </table>
		</form>
                <div id='errorMsg'></div>
		<br>
		<h2>To Create a new user fill out the form below.</h2>
        <table class='table table-bordered'>
        <tr><td>Email</td><td><input type='edit' id='emailC' name='emailC' value='' size=30></td></tr>
        <tr><td>Password</td><td><input type='password' id='passwordC' name='passwordC' value='' size=30></td></tr>
        <tr><td>Default Location</td><td><select id='locationC' name='locationC'>";
        
    if($result = $mysqli->query("select * from ".locationsTable))
    {
        /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
        while($row = $result->fetch_object())
        {
            /* display option for the select */
            echo "<option value=".$row->kjstacho_LWIP_Locations_id.">".$row->zip." - ".$row->zipDescription."</option>";
        }
        $result->close();
    }
    else
    {
        echo "Create Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        exit();
    }
    
    echo "</select></td></tr>
        <tr><td></td><td><button class='btn btn-lg-primary' id=btnCreate onclick='createLogin()'>Ajax Create</button></td></tr>
        </table>
	<input type='hidden' name='create' value='true'>";
}