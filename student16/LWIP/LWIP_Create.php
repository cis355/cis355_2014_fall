<?php
/**
 * Purpose: This file will;
 *          Create the tables used in the LWIP project
 *
 * Author:  Kevin Stachowski
 * Date:    09/18/2014
 * Update:  10/11/2014
 *
 * Notes:   These tables will be used by the LWIP project.
 *          This file can be used with url parameters.
 *          With no params it will create all tables.
 * 
 *          [drop]   - set to the table you want to drop.
 *          [create] - set to the table you want to create.
 * 
 *          example: http://www.cis355.com/student16/LWIP/LWIP_Create.php?drop=kjstacho_LWIP_Comments 
 *                   http://www.cis355.com/student16/LWIP/LWIP_Create.php?create=kjstacho_LWIP_Comments 
 *          
 * 
 * External resources: This application depends on access to a mysql DB (vars set in LWIP_function.php.
 */

/** This will include vars and functions from the file.
 *  the headers, footer and getMySQLi functions come from here.
 *  This file also processes $_GET and $POST vars 
*/
require_once("LWIP_function.php");

main();

 /**
 * Purpose: This is the driver function for the application.
 * Pre:     functions and vars from the LWIP_function.php file have been included
 * Post:    page has been processed.
 */
function main()
{
    global $drop, $create;
    // this function is defined in the functions file
    headers();
    
    // this function is defined in the functions file, it returns an open mysqli object
    $mysqli = getMySQLi();
    
    // if the url has a drop parameter, process the drop.
    // this would get set in the $_GET processing of the functions file
    if(isset($drop))
    {
        // this function is in the functions file
        // it takes an open mysqli object and a string sql statment.
	executeSQL($mysqli,"Drop Table $drop");
        
        echo "$drop dropped.<br>";
    }
    
    // if the url has a create parameter, process the create.
    // this would get set in the $_GET processing of the functions file
    elseif(isset($create))
	createTable($mysqli,$create);
    else
    {
        /* pass the mysqli object to the function. 
        *  the tables must be created in the right order,
        *  tables with parent foreign keys must be created first.    */
        //createTable($mysqli,locationsTable);
        //createTable($mysqli,usersTable);
        createTable($mysqli,itemsTable);
        createTable($mysqli,commentsTable);
    }

    /* Close connection, call close method of the mysqli object */
    $mysqli->close();
    
    /* call java script sleep and redirect (from LWIP_Scripts.js) */
    echo "<script>sleep(3000, goToShowTables());</script>";
    
    // this function is defined in the funtions file.
    footer();
}

/**
 * Purpose: This function will check if the table exists, and create it if not.
 * Pre:     The MySQLi object must be connected.
 * Post:    The table is verified there or created.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 * @param   var   $usertable  string contains the table to create.
 */
function createTable($mysqli,$usertable)
{
    //check if the the query can be executed.
    if($result = $mysqli->query("select 1 from $usertable"))
    {
        //this var will be used to check the result after $result has been closed
        $tableExists = true;
        /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
        $result->close();
    }
    elseif(false) //turned this off since the error is expected and handled later.
        echo "Check Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    
    /* if nothing in $id*/
    if(!$tableExists)
    {
        $sql = getTableSQL($usertable);
        executeSQL($mysqli,$sql);
        echo "$usertable created.<br>";
    }
    else 
        echo "$usertable already exists.<br>";
}

/** Purpose: This table will create the sql statements for creating the tables.
*   Pre:     This is called in the create table function is called.
*   Post:    The sql for the correct table has been generated. It still needs to be executed.
* 
*   @param   var   $usertable  string that contains the table to get the SQL for.
* 
*   @return  This will return a string of sql used to create a table.
*/
function getTableSQL($usertable)
{
    // set the sql var to some starting value
    $sql = "Blank!";
    
    //check the usertable and see what sql needs to be returned.
    // the table names are contants defined in the functions file
    if($usertable == usersTable)
    {
        $sql = "CREATE TABLE ".usersTable." (";
        //this will ad "_id" to the end of the usersTable name, and..
        //set it as an integer datatype,
        //set it as not null,
        //set it as a primary key field.
        $sql .= usersTable."_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        //this will ad "_id" to the end of the locationsTable name, integer datatype
	$sql .= locationsTable."_id INT, ";
        $sql .= "email VARCHAR(50), ";
        $sql .= "password VARCHAR(50), ";
        // add an insertdate that has a timestamp datatype
        // set a default of the current date time
        $sql .= "insertDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
        // add a foreign key that will only allow vaules that exist in the locationstable id field to be put in this field
        $sql .= "FOREIGN KEY (".locationsTable."_id) REFERENCES ".locationsTable."(".locationsTable."_id),";
        // set a unique flag on the email field
        // this will only allow an email to exist in the email field once
	$sql .= "UNIQUE (email)";
        $sql .= ");";
    }
    elseif($usertable == locationsTable)
    {
        $sql = "CREATE TABLE ".locationsTable." (";
        //this will ad "_id" to the end of the locationsTable name, and..
        //set it as an integer datatype,
        //set it as not null,
        //set it as a primary key field.
        $sql .= locationsTable."_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "zip CHAR(5), ";
        $sql .= "zipDescription VARCHAR(50),";
        // add an insertdate that has a timestamp datatype
        // set a default of the current date time
        $sql .= "insertDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $sql .= ");";
    }
    elseif($usertable == itemsTable)
    {
        $sql = "CREATE TABLE ".itemsTable." (";
        //this will ad "_id" to the end of the itemsTable name, and..
        //set it as an integer datatype,
        //set it as not null,
        //set it as a primary key field.
        $sql .= itemsTable."_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        //this will ad "_id" to the end of the userTable name, integer datatype
        $sql .= usersTable."_id INT, ";
        //this will ad "_id" to the end of the locationsTable name, integer datatype
	$sql .= locationsTable."_id INT, ";
        $sql .= "title VARCHAR(50),";
	$sql .= "description VARCHAR(500),";
        $sql .= "price DECIMAL (10,2),";
        // add an insertdate that has a timestamp datatype
        // set a default of the current date time
        $sql .= "insertDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
        // add a foreign key that will only allow values that exist in the usertables id field to be put in this field
        $sql .= "FOREIGN KEY (".usersTable."_id) REFERENCES users(user_id),";
        // add a foreign key that will only allow vaules that exist in the locationstable id field to be put in this field
        $sql .= "FOREIGN KEY (".locationsTable."_id) REFERENCES locations(location_id)";
	$sql .= ");";
    }
    elseif($usertable == commentsTable)
    {
        $sql = "CREATE TABLE ".commentsTable." (";
        //this will ad "_id" to the end of the commentsTable name, and..
        //set it as an integer datatype,
        //set it as not null,
        //set it as a primary key field.
        $sql .= commentsTable."_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        //this will ad "_id" to the end of the itemsTable name, integer datatype
        $sql .= itemsTable."_id INT, ";
        //this will ad "_id" to the end of the usersTable name, integer datatype
	$sql .= usersTable."_id INT, ";
        $sql .= "comment VARCHAR(500),";
        // add an insertdate that has a timestamp datatype
        // set a default of the current date time
        $sql .= "insertDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
        // add a foreign key that will only allow values that exist in the usertables id field to be put in this field
        //$sql .= "FOREIGN KEY (".usersTable."_id) REFERENCES ".usersTable."(".usersTable."_id),";
        // add a foreign key that will only allow values that exist in the itemsTable id field to be put in this field
        $sql .= "FOREIGN KEY (".itemsTable."_id) REFERENCES ".itemsTable."(".itemsTable."_id)";
        $sql .= ");";
    }
    
    //return whatever sql is appropriate
    return $sql;
}