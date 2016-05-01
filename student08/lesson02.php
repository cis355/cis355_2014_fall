<?php
foreach($_POST as $key=>$value)
{
    $$key=$value;
}

if(!$name) /*This checks to make sure the Name variable has been filled, if not, it shows the form again*/
{
    echo showForm();
}
else
{
    echo "<p>Thank you for entering your data! We will add your data to the table!<p>">

	<tr><td width=\"35%\">Owner Name</td><td>".$_REQUEST["name"]."</td></tr>
	<tr><td width=\"35%\">Zip Code</td><td>".$_REQUEST["zip"]."</td></tr>
	<tr><td width=\"35%\">Model</td><td>".$_REQUEST["model"]."</td></tr>
	<tr><td width=\"35%\">Wheel Size</td><td>".$_REQUEST["wheel"]."</td></tr>
	<tr><td width=\"35%\">Bike Color</td><td>".$_REQUEST["color"]."</td></tr>
	<tr><td width=\"35%\">You Paid</td><td>".$_REQUEST["price"]."</td></tr>
	</table></td></tr></table>

";
    
    /** These vars will be part of the global collection.
     *  These will need to be changed to your respective DB and table.
     */
    $hostname="localhost";
	$username="user01";
	$password="cis355lwip";
	$dbname="lwip";
	$usertable="table08";

    main();
}

function main()
{

processFormData();
    echo "If you see no errors, your entry was added to the bike database!<br> If not, click back to form and try again!<br>";
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
        
        /* The next 5 functions will connect, create a table to the database if there isn't one
			Insert a record, update the table, and the display the records to the browser*/
        checkConnect($mysqli);
	createTable($mysqli);
        insertRecord($mysqli);
        updateRecord($mysqli);
        selectRecords($mysqli);
	
	/* This will close the connection */
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
    global $name, $zip, $model, $wheel, $color, $price, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();

    if($stmt = $mysqli->prepare("INSERT INTO table08 (name,zip,model,wheel,color,price) VALUES (?,?, ?, ?, ?, ?)"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('ss', $name, $zip, $model, $wheel ,$color, $price);

           /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
}
   else
{
  echo "Not Inserted! <br>";/*print if insert fails*/
}
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
    global $id, $name, $zip, $model, $wheel, $color, $price, $usertable;
    
    $sql = "UPDATE $usertable 
	SET name=?, zip=?, model=?, wheel=?, color=?, price=?
	WHERE id=?";
    if($stmt = $mysqli->prepare($sql))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('s', $name);

            /* execute prepared statement */
            $stmt->execute();
            /* call the close method for statement object */
            $stmt->close();
    }
   else
{
    echo "Update Failed!<br>";
}
}

/**
 * Purpose: This function will select data from the MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The MySQL data is displayed to the user.
 * 

 @param   var   $mysqli  contains a connected MySQLi object.

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
        $stmt->bind_result($id, $name, $zip, $model, $wheel, $color, $price);
        /* read while fetch returns data*/
        echo "<br>Here is a list of our current records<br><table><tr><th>Name</th><th>Zip Code</th><th>Model</th><th>Wheel Size</th><th>Bike Color</th><th>I paid</th></tr>";
        while($stmt->fetch()){
                /* work with bound result vars */
                echo "<tr><td>$id</td><td>$name</td><td>$zip</td><td>$model</td><td>$wheel</td><td>$color</td><td>$price</td></tr>";
        }
        echo "</table>";
        $stmt->close();
    }
}

/**
 * Purpose: This will generate the html form for the user. 
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
		
        <form name='basic' method='Post' action='lesson02.php' onSubmit='return validate();'>
        <table border=0 cellpadding=5 cellspacing=0>
        <tr><td>Your Name</td><td><input type='edit' name='name' value='' size=20></td></tr>
        <tr><td>Zip Code</td><td><input type='edit' name='zip' value='' size=30></td></tr>
        <tr><td>Bike Model</td><td><input type='edit' name='model' value='' size=30></td></tr>
        <tr><td>Wheel Size</td><td><input type='edit' name='wheel' value='' size=30></td></tr>
        <tr><td>Bike Color</td><td><input type='edit' name='color' value='' size=30></td></tr>
        <tr><td>You Paid:</td><td><input type='edit' name='price' value='' size=30></td></tr>
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
    echo "<br><form name='basic' method='Post' action='table08a.html' onSubmit='return validate();'>
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
        $sql = "CREATE TABLE table08 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
		$sql .= "name VARCHAR(20),";
		$sql .= "zip INT,";
		$sql .= "model VARCHAR(20),";
		$sql .= "wheel INT,";
		$sql .= "color VARCHAR(10),";
		$sql .= "price INT";
		$sql .= ")";


       if($stmt = $mysqli->prepare($sql))
        {
            /* execute prepared statement */
            $stmt->execute();
        }
    }
    
    
}
?>