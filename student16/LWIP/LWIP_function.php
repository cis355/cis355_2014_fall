<?php
/**
 * Purpose: This file will;
 *          Show the data in the tables used in the LWIP project
 *
 * Author:  Kevin Stachowski
 * Date:    9/21/2014
 * Update:  10/11/2014
 * Notes:   These tables will be used by the LWIP project
 * 
 * External resources: This application depends on access to a mysql DB.
 */

/** These vars will be part of the global collection.
*  These will need to be changed to your respective DB and table.
*/
foreach($_POST as $key=>$value)
{
    /** The $key is the named index in $_POST, $value is that indexes value. 
     *  Adding another $ to $key turns it into a variable variable name. 
    **/
    $$key=$value;
}

foreach($_GET as $key=>$value)
{
    /** The $key is the named index in $_GET, $value is that indexes value. 
     *  Adding another $ to $key turns it into a variable variable name. 
    **/
    $$key=$value;
}

//start session
ini_set("session.cookie_domain", ".cis355.com");
session_start();

//turn on errors
//ini_set('display_errors',1);
//error_reporting(E_ALL);

define("hostname","localhost");
define("username","user01");
define("password","cis355lwip");
define("dbname","lwip");
define("errorsOn",true);
define("itemsTable","kjstacho_LWIP_Items");
define("usersTable","kjstacho_LWIP_Users");
define("locationsTable","kjstacho_LWIP_Locations");
define("commentsTable","kjstacho_LWIP_Comments");
define("key","Aa1!@#$%^&*()");

 /**
 * Purpose: This is the driver function for the application.
 * Pre:     none
 * Post:    page has been processed.
 */
function getMySQLi()
{
    /* Create a new mysqli object, this opens the connection. */
    $mysqli = new mysqli(hostname, username, password, dbname);
    /* Make sure the connection is good, pass the mysqli object to the function. */
    checkConnect($mysqli);
    
    return $mysqli;
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
 * Purpose: This will show the html form button to the user. 
 *          Even though its calling the same page since there is no post vars it will load the html from for the user.
 * Pre:     It should be called at the end of data processing.
 * Post:    The button is displayed to the user.
 */
function showFormBtn()
{
    echo "<br><br><br><br><br><h4>
	<a class='label label-success' href=index.php>Dashboard</a>
	<a class='label label-success' href=LWIP_ShowTables.php>Show Items</a>
	<a class='label label-success' href=../../student14/logout.php>Logout</a>
	<a class='label label-success' href=LWIP_Bio.php>Bio - Notes</a>
	</h4> test credentials - email:test@test.com, pwd:12345<br>";
}

/**
 * Purpose: This will execute sql on the mysqli object.
 * Pre:     mysqli must be connected
 * Post:    the sql has been processed.
 */
function executeSQL($mysqli,$sql)
{
    if($stmt = $mysqli->prepare($sql))
    {
        if($stmt->execute())
        {
            return $stmt;
        }
        elseif(errorsOn)
        {
            echo "Select execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }
    elseif(errorsOn)
    {
        echo "Select Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

 /**
 * Purpose: This will apply headers for bootstrap
 * Pre:     none
 * Post:    headers have been added.
 */
function headers()
{
    echo "<!DOCTYPE html>
        <html>
        <head>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
		<link rel='stylesheet' href='LWIP_Style.css'>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
		<script type='text/javascript' src='LWIP_Scripts.js'></script>
        </head><body>
		<div class='col-md-12' style='background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;'>
		<a href='http://www.cis355.com/student14/landing.php'><img src='http://www.cis355.com/student14/LWIP_logo.png' style='margin-top: 5px;'></a>";
		if(isset($_SESSION["user"]))
		{
			echo "<div class='navbar-form navbar-right'>Logged in as ".$_SESSION["user"]."</div>";
		}
		else
		{
		echo "<form class='navbar-form navbar-right' style='margin-top: 35px;' method='POST' action='http://www.cis355.com/student14/login.php'>
			<input type='text' size='9' name='username' class='form-control' placeholder='Username'>
			<input type='password' size='9' name='password' class='form-control' placeholder='Password'>
			<input type='submit' name='loginSubmit' value='Login' class='btn btn-success'></input>
		</form>";	
	}
	echo "<br><br></div>";
	showFormBtn();
}

 /**
 * Purpose: This will apply the footer to close the html.
 * Pre:     the rest of the page has been generated.
 * Post:    footers have been added.
 */
function footer()
{
    echo "</div><div class='footer'>
            <p>Copyright &copy; 2014, Kevin Stachowski - license: code free to use in any way you see fit.</p>
        </div></body></html>";
}

 /**
 * Purpose: This will return an encrypted string
 * Pre:     na
 * Post:    $string has been encrypted
 *
 * @param   var   $string  contains a plain text string.
 */
function encrypt($string)
{
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(key), $string, MCRYPT_MODE_CBC, md5(md5(key))));
}

 /**
 * Purpose: This will return a decrypted string
 * Pre:     na
 * Post:    $string has been decrypted
 *
 * @param   var   $string  contains an encrypted string.
 */
function decrypt($string)
{
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(key))), "\0");
}