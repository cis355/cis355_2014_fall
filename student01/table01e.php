<?php
// gpc: this file modifies Kevin's template, as labeled with "gpc"
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

if(!$user_id)
{
    echo showForm();
}
else
{   //gpc: form info edited below
    echo "<p>The following information was submitted from the form:</p>

	<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">

    <tr><td width=\"35%\">user_id</td><td>$user_id</td></tr>
    <tr><td width=\"35%\">location_id</td><td>$location_id</td></tr>
	<tr><td width=\"35%\">ins_co</td><td>$ins_co</td></tr>
	<tr><td width=\"35%\">ins_agency</td><td>$ins_agency</td></tr>
	<tr><td width=\"35%\">ins_agency_phone</td><td>$ins_agency_phone</td></tr>
	<tr><td width=\"35%\">ins_agency_www</td><td>$ins_agency_www</td></tr>
	<tr><td width=\"35%\">amount_paid</td><td>$amount_paid</td></tr>
    <tr><td width=\"35%\">amount_per</td><td>$amount_per</td></tr>
	<tr><td width=\"35%\">address</td><td>$address</td></tr>
	<tr><td width=\"35%\">city</td><td>$city</td></tr>
	<tr><td width=\"35%\">state</td><td>$state</td></tr>
	<tr><td width=\"35%\">zip_code</td><td>$zip_code</td></tr>
	<tr><td width=\"35%\">year_built</td><td>$year_built</td></tr>
	<tr><td width=\"35%\">exterior</td><td>$exterior</td></tr>
	<tr><td width=\"35%\">condition</td><td>$condition</td></tr>
	<tr><td width=\"35%\">structure_type</td><td>$structure_type</td></tr>
	<tr><td width=\"35%\">weather_risk</td><td>$weather_risk</td></tr>
	<tr><td width=\"35%\">fire_risk</td><td>$fire_risk</td></tr>
	<tr><td width=\"35%\">owner_smokes (1=yes, 2=no)</td><td>$owner_smokes</td></tr>
	<tr><td width=\"35%\">owner_credit (int)</td><td>$owner_credit</td></tr>
	<tr><td width=\"35%\">owner_claims (int)</td><td>$owner_claims</td></tr>

	</table>";
    
    /** These vars will be part of the global collection.
     *  These will need to be changed to your respective DB and table.
     */
    $hostname="localhost";
    $username="student";
    $password="learn";
    $dbname="lesson01";
    $usertable="table01a";

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
        //createTable($mysqli);
        
        /* Insert a record to the MySQL table, pass the mysqli object to the function. */
        //insertRecord($mysqli);
        
        /* Update a record on the MySQL table, pass the mysqli object to the function. */
        //updateRecord($mysqli);
        
        /* Select records from the Mysql table, pass the mysqli object to the function. */
        //selectRecords($mysqli);
	
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
        <head>
		<title>Table01</title>
		<style>
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
        </head>
		<body>
        <form name='basic' method='Post' action='table01e.php' onSubmit='return validate();'>
        <table border=0 cellpadding=5 cellspacing=0>
        <tr><td>user_id</td><td><input type='edit' name='user_id' value='' size=20></td></tr>
        <tr><td>location_id</td><td><input type='edit' name='location_id' value='' size=20></td></tr>
        <tr><td>ins_co</td><td><input type='edit' name='ins_co' value='' size=20></td></tr>
        <tr><td>ins_agency</td><td><input type='edit' name='ins_agency' value='' size=20></td></tr>
        <tr><td>ins_agency_phone</td><td><input type='edit' name='ins_agency_phone' value='' size=20></td></tr>
        <tr><td>ins_agency_www</td><td><input type='edit' name='ins_agency_www' value='' size=20></td></tr>
        <tr><td>amount_paid</td><td><input type='edit' name='amount_paid' value='' size=20></td></tr>
        <tr><td>amount_per</td><td><input type='edit' name='amount_per' value='' size=20></td></tr>
        <tr><td>address</td><td><input type='edit' name='address' value='' size=20></td></tr>
        <tr><td>city</td><td><input type='edit' name='city' value='' size=20></td></tr>
        <tr><td>state</td><td><input type='edit' name='state' value='' size=20></td></tr>
        <tr><td>zip_code</td><td><input type='edit' name='zip_code' value='' size=20></td></tr>
        <tr><td>year_built</td><td><input type='edit' name='year_built' value='' size=20></td></tr>
        <tr><td>exterior</td><td><input type='edit' name='exterior' value='' size=20></td></tr>
        <tr><td>condition</td><td><input type='edit' name='condition' value='' size=20></td></tr>
        <tr><td>structure_type</td><td><input type='edit' name='structure_type' value='' size=20></td></tr>
        <tr><td>weather_risk</td><td><input type='edit' name='weather_risk' value='' size=20></td></tr>
        <tr><td>fire_risk</td><td><input type='edit' name='fire_risk' value='' size=20></td></tr>
        <tr><td>owner_smokes</td><td><input type='edit' name='owner_smokes' value='' size=20></td></tr>
        <tr><td>owner_credit</td><td><input type='edit' name='owner_credit' value='' size=20></td></tr>
        <tr><td>owner_claims</td><td><input type='edit' name='owner_claims' value='' size=20></td></tr>
        <tr><td align=center><input type='reset' name='Reset' value='Reset'></td><td align=center><input type='submit' name='Submit' value='Submit'></td></tr>
        </table>
		</form>
		</body>
		</html>";
}

/**
 * Purpose: This will show the html form button to the user. 
 *          Even though its calling the same page since there is no post vars it will load the html from for the user.
 * Pre:     It should be called at the end of data processing.
 * Post:    The button is displayed to the user.
 */
function showFormBtn()
{
    echo "<br><form name='basic' method='Post' action='table01e.php' onSubmit='return validate();'>
        <input type=submit name='Submit' value='Back to form'></form>";
}

?>