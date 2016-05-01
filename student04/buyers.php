<?php
//**************************************************************************
//Author: Olivia Archambo
//Assignment: Individual Project, "Petsaver" Website with 3 DB tables
//School Yaer: Fall, 2014
//Professor: George Corser
//File: buyers.php
//**************************************************************************

ini_set("session.cookie_domain", ".cis355.com");
		session_start();

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="customer04";

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
	$updateSelected 	= 3; // after user clicked updateSelected button on list 
	$deleteSelected 	= 4; // after user clicked deleteSelected button on list 
	$insertCompleted 	= 5; // after user clicked insertSubmit button on form
	$updateCompleted 	= 6; // after user clicked updateSubmit button on form
	$deleteCompleted 	= 7; // after user clicked deleteSubmit button on form
	$viewSelected       = 8;
	
    $cust_id			= $_POST['cust_id']; // if does not exist then value is ""
	$cust_name			= $_POST['cust_name'];
	$cust_loc			= $_POST['cust_loc'];
	$cust_email			= $_POST['cust_email'];
	$pet_type			= $_POST['pet_type'];
	$pet_breed			= $_POST['pet_breed'];
	$descript 			= $_POST['descript'];
	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	if( isset( $_POST['viewSelected'] ) ) $userSelection = $viewSelected;
	// the code above assumes this is the first time program is called 
	// unless one of the buttons named above was clicked
	
// f. ---------- call function based on what user clicked ----------
	switch( $userSelection ):
	    case $firstCall: 
		    $msg = '';
			displayHTMLHead();
		    showList($mysqli, $msg);
			break;
		case $insertSelected:
			displayHTMLHead();
		    showInsertForm($mysqli);
			break;
		case $updateSelected :
			displayHTMLHead();
		    showUpdateForm($mysqli);
			break;
		case $deleteSelected:        // currently no confirmation form is displayed on delete
			deleteRecord($mysqli);   // selecting delete immediately deletes the record
			displayHTMLHead();
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted: 
		    insertRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
			displayHTMLHead();
			$msg = 'record inserted';
			showList($mysqli, $msg);
			break;
		case $updateCompleted:
		    updateRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
			displayHTMLHead();
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (possible upgrade later?)
			$msg = 'record deleted';  
			showList($mysqli, $msg);
			break;
		case $viewSelected:
			displayHTMLHead();
			viewRecords($mysqli);
	endswitch;

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
	    $sql = "CREATE TABLE customer04 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "cust_id INT,";
	    $sql .= "cust_name VARCHAR(25),";
	    $sql .= "cust_loc VARCHAR(25),";
	    $sql .= "cust_email VARCHAR(25),";
	    $sql .= "pet_type VARCHAR(25),";
	    $sql .= "pet_breed VARCHAR(25),";
	    $sql .= "descript VARCHAR(200),";
		$sql .= "FOREIGN KEY(cust_id) REFERENCES userinfo04(user_id)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
    }
}

# ---------- showList --------------------------------------------------------
function showList($mysqli, $msg)
{
    // this function gathers records from a "mysql table" and builds an "html table"
	
	global $usertable;
	
	//display current user and location id
	echo "You are logged in as user:" . $_SESSION["user"]. "<br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="buyers.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="11" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Potential Buyers/Adopters (table04)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Name</td>
			<td>Location</td><td>Email</td><td>Pet Type</td><td>Breed</td><td></td></tr>';
			

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable"); // get count of records in mysql table
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, else display "no records" message
	if( $countvalue > 0 )
	{
			populateTable($mysqli); // populate html table, from data in mysql table
	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}
	
	// display html buttons 
	if($_SESSION['user'] != "")
	{
	echo    '</table><input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">
			<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary" onclick="setAdd();">
			</form></div>';
	}
	//echo '<center><a href="http://www.cis355.com/student04/bio.php">Bio for Olivia Archambo/Table04 LWIP</a></center>';
	// add JavaScript functions to end of html body section
	// note: "hid" is id of item to be deleted; "uid" is id of item to be updated.
	// see also: populateTable function
	echo "<script>
			function setHid(num)
			{
				document.getElementById('hid').value = num;
		    }
		    function setUid(num)
			{
				document.getElementById('uid').value = num;
		    }

		 </script>";
}

# ---------- populateTable ---------------------------------------------------
// populate html table, from data in mysql table
function populateTable($mysqli)
{
	global $usertable;
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>'. $row[2] . '</td><td>' . $row[3] . 
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6];
				 
			echo
				'</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');"/></td>';
				 
		if ($_SESSION["id"]==$row[1]) {				 
			echo '</td><td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
				 <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
				 }
		else
			echo'<td></td></tr>';
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
	echo "You are logged in as user:" . $_SESSION["user"]. "<br>";
		 
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="buyers.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Describe what kind of pet you are looking for:</h2></td></tr>
			<tr><td>Name: </td><td><input type="edit" name="cust_name" value="" size="25"></td></tr>
			<tr><td>Location: </td><td><input type="edit" name="cust_loc" value="" size="25"></td></tr>
			<tr><td>Email: </td><td><input type="edit" name="cust_email" value="" size="25"></td></tr>
			<tr><td>Pet Type: </td><td><input type="edit" name="pet_type" value="" size="15"></td></tr>
			<tr><td>Breed (optional): </td><td><input type="edit" name="pet_breed" value="" size="15"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>';
	echo
			'</td></tr>
			<tr><td>User ID: </td><td><input type="text" name="cust_id" value="'. $_SESSION["id"].'" size="10" readonly></td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="buyers.php" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
		echo "You are logged in as user:" . $_SESSION["user"]. "<br>";
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="buyers.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Update Pet Description</h2></td></tr>
							<tr><td>Name: </td><td><input type="edit" name="cust_name" value="'. $row[2] .'" size="20"></td></tr>
							<tr><td>Location: </td><td><input type="edit" name="cust_loc" value="' . $row[3] . '" size="20"></td></tr>
							<tr><td>Email: </td><td><input type="edit" name="cust_email" value="' . $row[4] . '" size="30"></td></tr>
							<tr><td>Pet Type: </td><td><input type="edit" name="pet_type" value="' . $row[5] . '" size="20"></td></tr>
							<tr><td>Breed (optional): </td><td><input type="edit" name="pet_breed" value="' . $row[6] . '" size="30"></td></tr>
							<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[7] . '</textarea></td></tr>';
					echo
							'</td></tr>
							<tr><td>User ID: </td><td><input type="edit" name="user_id" value="' . $row[1] . '" size="20" readonly></td></tr>
							<tr><td><input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry"></td>
								<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
					</form>
				</div>';
		}
		$result->close();
	}
}

# ---------- deleteRecord -------------------------------------------------------------
function deleteRecord($mysqli)
{
	$index = $_POST['hid'];  // "hid" is id of db record to be deleted
	global $usertable;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("DELETE FROM $usertable WHERE id=?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('i', $index);
            $stmt->execute();
            $stmt->close();
    }
}

# ---------- insertRecord --------------------------------------------------------------
function insertRecord($mysqli)
{
    global $cust_id, $cust_name, $cust_loc, $cust_email, $pet_type, $pet_breed, $descript, $usertable;

    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (cust_id,cust_name,cust_loc,cust_email,pet_type,pet_breed,descript) VALUES (?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('issssss', $cust_id, $cust_name, $cust_loc, $cust_email, $pet_type, $pet_breed, $descript);
        $stmt->execute();
        $stmt->close();
    }
		//echo'<script>window.location.reload();</script>';
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $cust_name, $cust_loc, $cust_email, $pet_type, $pet_breed, $descript, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET cust_name=?, cust_loc=?, cust_email=?, pet_type=?, pet_breed=?, descript=? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('ssssssi', $cust_name, $cust_loc, $cust_email, $pet_type, $pet_breed, $descript, $index);
            $stmt->execute();
            $stmt->close();
    }
}

# ---------- viewRecords -----------------------------------------------------
function viewRecords($mysqli)
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;

	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
	    $row = $result->fetch_row();
		 echo '	<br>
				<div class="col-md-4">
				<form name="basic" method="POST" action="buyers.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Looking for Pets:</h2></td></tr>
				<tr><td>Customer Id: </td><td>'. $row[1] .'</td></tr>
				<tr><td>Customer Name: </td><td>' . $row[2] . '</td></tr>
				<tr><td>Location: </td><td>' . $row[3] . '</td></tr>
				<tr><td>Email: </td><td>' . $row[4] . '</td></tr>
				<tr><td>Pet Type: </td><td>' . $row[5] . '</td></tr>
				<tr><td>Pet Breed: </td><td>' . $row[6] . '</td></tr>
				<tr><td>Description: </td><td>' . $row[7] . '</td></tr>
				</table>
                <a href="buyers.php" class="btn btn-primary">Display Database</a>
				</form></div>';
		$result->close();
	}
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