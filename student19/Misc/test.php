<?php
/**
 * Purpose: This file will;
 *          Test the "MySQLi" methods for working with a MySQL database.
 *              - connect via objects.
 *              - bind parameters.
 *              - get results via object properties.
 *          It will show examples of functions and variable scope.
 *              - global vs local vars
 *              - calling functions and parameters.
 *          It will show examples of PHPDoc compatible commenting.
 *              - multiline comments
 *              - auto complete. (if supported, works in Netbeans, not in Notepad++)
 *
 * Author:  Kevin Stachowski
 * Date:    9/14/2014
 * Notes:   This builds on items covered in lesson02.php for CIS355 Fall 14
 * 
 * External resources: This application depends on access to a mysql DB.
 *                     Although the DB connection and table names are defined as global vars, 
 *                     there is code that depends on the table having certian field names.
 */

/**
 * This will go through each $_POST item and create a variable of the same name.
 * For example if there is a $_POST["name"], it will create the var $name that contains the vaule in $_POST["name"].
 * In a foreach the collection var comes first, then vars used to iterate across each child. 
 */
foreach($_POST as $key=>$value)
{
    /** The $key is the named index in $_POST, $value is that indexes value. 
     *  Adding another $ to $key turns it into a variable variable name. 
     *  You can also manually create and assign each one:
     *      $name = $_POST["name"];
     *      $descript = $_POST["descript"];
     * **/
    $$key=$value;
}

/** Now that we have any post vars that were sent, we can check if we should load the html form or process post data.**/


/** If there is posted data (if $name has a value), 
 *  show the data and start the main function. 
 *  If not show the html form.
 *  Notice the ! means not. **/
if(!$model)
{
    echo showForm();
}
else
{
    echo "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
    <tr><td width=\"35%\">brand</td><td>$brand</td></tr>
    <tr><td width=\"35%\">model</td><td>$model</td></tr>
	<tr><td width=\"35%\">price</td><td>$price</td></tr>
	<tr><td width=\"35%\">condition</td><td>$condition</td></tr>
    </table></td></tr></table>";
    
    /** These vars will be part of the global collection.
     *  These will need to be changed to your respective DB and table.
     */
    $hostname="localhost";
    $username="student";
    $password="learn";
    $dbname="lesson01";
    $usertable="whitfiTble19_New01az";

    /* main entry point for processing.*/
    main();
}

/**
 * Purpose: This is the driver function for the application.
 * Pre:     This is called only if there is post data.
 * Post:    Form data has been processed.
 */
function main()
{
	echo "<br><br><br>Begin processing of form data...<br>";
	processFormData();
    echo "Done!<br>";
    showFormBtn();
}

/**
 * Purpose: This will use the MySQLi methods, inserting, updating and selecting a record with bound parameters.
 *          This function used the global collection to access global vars, 
 *          they could also be passed into the function via parameters.
 * Pre:     There should be some post data that needs to be processed.
 * Post:    Form data has been inserted and displayed to the user.
 */
function processFormData()
{
	/** Pass in global vars to local scope.
    * Without this it will create new instances in memory for the connection string vars.
	* If the vars are null, the mysqli object will attempt to use the default use set in the php.ini file.
	*/
	global $hostname, $username, $password, $dbname;
	
    /* Create a new mysqli object, this opens the connection. */
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
        
        /* Make sure the connection is good, pass the mysqli object to the function. */
        checkConnect($mysqli);
        
        /* Create the table if needed, pass the mysqli object to the function. */
        createTable($mysqli);
        
        /* Insert a record to the MySQL table, pass the mysqli object to the function. */
        insertRecord($mysqli);
        
        /* Update a record on the MySQL table, pass the mysqli object to the function. */
        updateRecord($mysqli);
        
        /* Select records from the Mysql table, pass the mysqli object to the function. */
        selectRecords($mysqli);
	
	/* Close connection, call close method of the mysqli object */
	$mysqli->close();
}

/**
 * Purpose: This function will check the MySQLi connection and display errors, if there are any.
 * Pre:     The MySQLi object must be initialized
 * Post:    the connection hase been confirmed.
 * 
 * @param   var   $mysqli  contains an initialized MySQLi object.
 */
function checkConnect($mysqli)
{
    
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
    echo "Connected!<br>";
}

/**
 * Purpose: This function will use the form post data to insert a new record into the MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The form data has been added to the table.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function insertRecord($mysqli)
{
    /* vars from the post data that we will use to bind */
    global $name, $descript, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (brand,model,price,condition) VALUES (?, ?, ?, ?)"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('ssis', $brand,$model,$price,$condition);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
    echo "Inserted!<br>";
}

/**
 * Purpose: This function will update some data on a MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The table data has been updated
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function updateRecord($mysqli)
{
    /* var from the post data that we will use to bind */
    global $brand, $model;
    
    /* Notice the ?, this will be a bound parameter. */
    $sql = "UPDATE $usertable SET model='updated!' WHERE brand=?";
    if($stmt = $mysqli->prepare($sql))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('s', $brand);

            /* execute prepared statement */
            $stmt->execute();
            /* call the close method for statment object */
            $stmt->close();
    }
    echo "Updated!<br>";
}

/**
 * Purpose: This function will select data from the MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The MySQL data is displayed to the user.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function selectRecords($mysqli)
{
    global $usertable;
    /* test select via object */
    if($stmt = $mysqli->prepare("select * from $usertable"))
    {
        /* execute prepared statement */
        $stmt->execute();
        /* bind result vars */
        $stmt->bind_result($id, $brand, $model);
        /* read while fetch returns data*/
        echo "<br> Data selected from the MySQL table.<table><tr><th>ID</th><th>brand</th><th>model</th></tr>";
        while($stmt->fetch()){
                /* work with bound result vars */
                echo "<tr><td>$id</td><td>$brand</td><td>$model</td></tr>";
        }
        echo "</table>";
        $stmt->close();
    }
}

/**
 * Purpose: This will generate the html form for the user. 
 *          (I could just echo the data here but wanted to show a data returning function...)
 * Pre:     It should be called when there is no form post data.
 * Post:    The form string needs to be displayed to the user.
 * 
 * @return  This will return a string of the html form.
 */
function showForm()
{
    return "<!DOCTYPE html><html>
        <head><title>Lesson02 Part2 Form</title></head><body><style>
        .robotext {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
        .robolink:link {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
        .robolink:hover {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: underline}
        .robolink:visited {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
        </style>
        <script language='Javascript'>
            function validate(){
                var allok = true;
                document.basic.Submit.disabled='disabled';
                return true;}
        </script>

        <form name='basic' method='Post' action='test.php' onSubmit='return validate();'>
        <table border=0 cellpadding=5 cellspacing=0>
		<tr><td>Brand</td><td><input type='edit' name='brand' value='' size=20></td></tr>
        <tr><td>Model</td><td><input type='edit' name='model' value='' size=20></td></tr>
        <tr><td>Price</td><td><input type='edit' name='price' value='' size=20></td></tr>
		<tr><td>Condition</td><td><input type='edit' name='condition' value='' size=20></td></tr>
        <tr><td align=center><input type='reset' name='Reset' value='Reset'></td><td align=center><input type=submit name='Submit' value='Submit'></td></tr>
        <tr><td colspan=2 class=robotext><a href='http://www.phpform.info' class='robolink'>HTML/PHP Form Generator</a> from ROBO Design Solutions</td></tr>
        </table></form></body></html>";
}

/**
 * Purpose: This will show the html form button to the user. 
 *          Even though its calling the same page since there is no post vars it will load the html from for the user.
 * Pre:     It should be called at the end of data processing.
 * Post:    The button is displayed to the user.
 */
function showFormBtn()
{
    echo "<br><form name='basic' method='Post' action='test.php' onSubmit='return validate();'>
        <input type=submit name='Submit' value='Back to form'></form>";
}

/**
 * Purpose: This function will check if the table exists, and create it if not.
 * Pre:     The MySQLi object must be connected.
 * Post:    The table is verified there or created.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function createTable($mysqli)
{
    global $usertable;
    /* test select via object */
    if($result = $mysqli->query("select id from $usertable limit 1"))
    {
        /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
        $row = $result->fetch_object();
		/** The fields in the results come back as properties of the fetched object. 
		*   Here since I selected the "id", the row has a property called "id".
		*/
		$id = $row->id;
        $result->close();
    }
    
    /* if nothing in $id*/
    if(!$id)
    {
        $sql = "CREATE TABLE $usertable (";
        $sql .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "brand VARCHAR(20), ";
		$sql .= "model VARCHAR(20), ";
		$sql .= "price INT(10), ";
		$sql .= "condition VARCHAR(12)";
        $sql .= ");";

        if($stmt = $mysqli->prepare($sql))
        {
            /* execute prepared statement */
            $stmt->execute();
            echo "Created table.<br>";
        }
    }
    else {
        echo "Table already exists.<br>";
    }
    
}