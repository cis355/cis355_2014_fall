<?php
//**************************************************************************
//Author: Olivia Archambo
//Assignment: Individual Project, "Petsaver" Website with 3 DB tables
//School Yaer: Fall, 2014
//Professor: George Corser
//File: project.php
//**************************************************************************

ini_set("session.cookie_domain", ".cis355.com");
		session_start();
		
$_SESSION["user"] = "defaultuser@svsu.edu";
$_SESSION["id"] = 2;
// for testing purposes, I used default login variables

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="userinfo04";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

if($mysqli)            // if successful connection...
{
    // c. ---------- create table, if necessary ----------
	createTable($mysqli); 	
	
	// d. ---------- initialize userSelection variables ----------
	$userSelection 		= 0;
	$firstCall 			= 1; // first time program is called
	$insertSelected 	= 2; // after user clicked insertSelected button on list 
	$loginTry			= 3; // after user clicked updateSelected button on list 
	$insertCompleted 	= 4; // after user clicked insertSubmit button on form
	$logoutCompleted	= 5; //log the user out
	
    $user_name			= $_POST['user_name']; // if does not exist then value is ""
	$user_loc			= $_POST['user_loc'];
	$user_email			= $_POST['user_email'];
	$user_password		= $_POST['user_password'];
	
	   // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['loginTry'] ) ) $userSelection = $loginTry;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['logoutCompleted'] ) ) $userSelection = $logoutCompleted;
	// the code above assumes this is the first time program is called 
	// unless one of the buttons named above was clicked

	// f. ---------- call function based on what user clicked ----------
	switch( $userSelection ):
	    case $firstCall: 
		    $msg = '';
			displayHTMLHead();
			break;
		case $insertSelected:
			displayHTMLHead();
		    showInsertForm($mysqli);
			break;
		case $loginTry:
			displayHTMLHead();
		    tryLogin();
			break;
		case $insertCompleted: 
		    insertRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
			displayHTMLHead();
			$msg = 'record inserted';
			break;
		case $logoutCompleted:
			tryLogout();
	endswitch;
	
	echo '
<p style="margin:10px;"><a href="http://www.cis355.com/student04/buyers.php">View Individuals Looking for Pets table</a></p>
<p style="margin:10px;"><a href="http://www.cis355.com/student04/sellers.php">View Pets for Sale/For Adoption table</a></p>';
	
	if($_SESSION['user'] == "")	// if the session variables have not been set, show a login form
	{
	echo    '<form name="login" method="POST" action="project.php" onSubmit="return validate();">
	<table style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px; margin:10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Member Login:</h2></td></tr>
	<tr><td>Email: </td><td><input type="edit" name="user_name" value="" size="25" style="margin:4px;"></td></tr>
	<tr><td>Password: </td><td><input type="password" name="user_password" value="" size="15" style="margin:4px;"></td></tr>
	<tr><td></td><td><center><input type="submit" name="loginTry" value="Log In" class="btn btn-primary" onclick="setAdd();"></center></td></tr>
	</table></form>';
	
	echo '<p style="margin:10px;">Not a member yet?
	<input type="submit" name="insertSelected" value="Sign Up!" class="btn btn-primary" onclick="setAdd();"></p>';
	}
	else
	{
	echo '<p style="margin:10px;">Welcome,' . $_SESSION["user"].
	'<input type="submit" name="logoutCompleted" value="Log Out" class="btn btn-primary" style="margin:5px;" onclick="setAdd();"></p>';
	}	
}







 // ---------- end if ---------- and end main processing ----------


# ========== FUNCTIONS ========================================================

# ---------- checkConnect -----------------------------------------------------
function checkConnect($mysqli)
{
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}

# ---------- createTable ------------------------------------------------------
function createTable($mysqli)
{
    global $usertable;
    if($result = $mysqli->query("select id from $usertable limit 1"))
    {
        $row = $result->fetch_object();
		$id = $row->id;
        $result->close();
    }
    
    if(!$id)
    {
	    $sql = "CREATE TABLE userinfo04(user_id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(user_id),";
	    $sql .= "user_name VARCHAR(25),";
	    $sql .= "user_loc VARCHAR(25),";
	    $sql .= "user_email VARCHAR(25),";
	    $sql .= "user_password VARCHAR(15)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
    }
}

# ----------- tryLogout ---------------------------------------------------------
function tryLogout()
{
	$_SESSION["user"] = '';		//try setting session variables to null
	$_SESSION["id"] = '';
	//doesn't work...
	
	unset($_SESSION["user"]);
	unset($_SESSION["id"]);
	session_destroy();
    session_write_close();
	
	if($_SESSION['user'] === '')
	{
	echo    '<p>You have been logged out.';
	}
}

#--------------tryLogin-----------------------------------------------------------
function tryLogin()
{

}

function displayHTMLHead()
{
	 // a. ---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	<head>
	<title>Potential Buyers/Adopters</title>
	<a href="project.php"><center><img src="petsaverlogo.jpeg"></center></a>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<style>
	body {
    background-color: #ffd8ca; }
	</style>
    </head><body>';
}

?>