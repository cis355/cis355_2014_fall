<?php
# --------------------------------------------------------------------------- #
# program: table21.php
# author:  Billy Wright
# course:  cis355 fall 2014
# purpose: template for cis355
#          to update this template, change all instances of "table21.php" to your php filename
#          and of course change the database table fields to match your database table.
# --------------------------------------------------------------------------- #
# input:   $_POST, or nothing
#
# processing: this program is called by a browser and excecuted on a server.
#          a. display (echo) html head and link to bootstrap css
#          b. set connection variables and verify connection
#             *. if successful connection
#             c. create table, if necessary
#             d. initialize userSelection variables
#             e. determine what user clicked
#             f. call function based on what user clicked
#                1. if nothing selected (first time), display database table contents, 
#                2. if insert selected, show a form to insert a new record in database table,
#                3. if update selected, show a form to update an existing record in database table,
#                4. if delete selected, show a form to delete an existing record in database table,
#                5. if insert completed, insert a new record in database table, then display database table contents,
#                6. if update completed, update the right record in database table, then display database table contents,
#                7. if delete completed, delete the right record in database table, then display database table contents.
#
# output:  HTML code 
# --------------------------------------------------------------------------- #

// session start happens first!!
//Add session start.
session_start();

// a. ---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	<head>
	<title>table21.php</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
    </head><body>';


// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table21";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

if($mysqli)            // if successful connection...
{
    // c. ---------- create table, if necessary ----------
	createTable($mysqli); 
	
	// d. ---------- initialize userSelection variables ----------
	$userSelection 		= 0;
	$firstCall 		= 1; // first time program is called
	$insertSelected 	= 2; // after user clicked insertSelected button on list 
	$updateSelected 	= 3; // after user clicked updateSelected button on list 
	$deleteSelected 	= 4; // after user clicked deleteSelected button on list 
	$insertCompleted 	= 5; // after user clicked insertSubmit button on form
	$updateCompleted 	= 6; // after user clicked updateSubmit button on form
	$deleteCompleted 	= 7; // after user clicked deleteSubmit button on form
	
       	$Year				= $_POST['Year'];
	$Make 				= $_POST['Make'];
	$Model				= $_POST['Model'];
	$DateListed			= $_POST['DateListed'];
	$PriceListed			= $_POST['PriceListed'];
	$DateSold			= $_POST['DateSold'];
	$PriceSold			= $_POST['PriceSold'];
// Add location_id and user_id
        $location_id   =  $_SESSION['location'];        // = $_POST['location_id'];
	$user_id       =  $_SESSION['id'];        // = $_POST['user_id'];
        $email = $_SESSION['user'];
	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	// the code above assumes this is the first time program is called 
	// unless one of the buttons named above was clicked
	
	// f. ---------- call function based on what user clicked ----------
	switch( $userSelection ):
	    case $firstCall: 
//Add code for user session.

//Not using these -line 79-79 instead.
         //  $_SESSION["login_user"] = 2;        //User 2
          // $_SESSION["login_location"] = 1;    //Location 1
		    $msg = '';
//Add code for htmldisplayhead.
           displayHTMLHead();
		    showList($mysqli, $msg);
			break;
		case $insertSelected:
//Add code for htmldisplayhead.
           displayHTMLHead();
		    showInsertForm();
			break;
		case $updateSelected :
//Add code for htmldisplayhead.
           displayHTMLHead();
		    showUpdateForm($mysqli);
			break;
		case $deleteSelected:        // currently no confirmation form is displayed on delete
//Add code for htmldisplayhead.
           displayHTMLHead();
			// showDeleteForm($mysqli);
			deleteRecord($mysqli);   // selecting delete immediately deetes the record
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted:
		    insertRecord($mysqli);
//Add code for htmldisplayhead.
           header("Location: " . $_SERVER['REQUEST_URI']); // redirect
           displayHTMLHead();
			$msg = 'record inserted';
			showList($mysqli, $msg);
			break;
		case $updateCompleted:
//Add code for htmldisplayhead.
           header("Location: " . $_SERVER['REQUEST_URI']); // redirect
           displayHTMLHead();
		    updateRecord($mysqli);
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (possible upgrade later?)
//Add code for htmldisplayhead.
           header("Location: " . $_SERVER['REQUEST_URI']); // redirect
           displayHTMLHead();
			$msg = 'record deleted';  
			showList($mysqli, $msg);
			break;
	endswitch;

} // ---------- end if ---------- and end main processing ----------


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
	    $sql = "CREATE TABLE table21 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "Year VARCHAR(30),";
	    $sql .= "Make VARCHAR(20),";
	    $sql .= "Model VARCHAR(30),";
	    $sql .= "DateListed VARCHAR(20),";
	    $sql .= "PriceListed VARCHAR(30),";
	    $sql .= "DateSold VARCHAR(30),";
            $sql .= "PriceSold VARCHAR(30)";
// Add location_id and user_id
            $sql .= "location_id INT NOT NULL,";
	    $sql .= "user_id INT NOT NULL,";
	    $sql .= "FOREIGN KEY (location_id) REFERENCES locations (location_id),";
            $sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id)";


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

        // display current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"].
	    " location: ".$_SESSION["location"]."<br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table21.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">MotorCycle Database (table21)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td>
			<td>Year</td><td>Make:</td><td>Model</td><td>DateListed</td><td>PriceListed</td><td>DateSold</td><td>PriceSold</td><td></td>
 
                        <td>Location</td><td>User</td><td></td>

                        </tr>'; //This was here before adding the Location and User html above.
			

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
	echo    '</table><input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">
			<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary">
			</form></div>';

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
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . '</td>

                                 <td>' . $row[8] . '</td><td>' . $row[9] .'</td>';
			if($_SESSION['id']== $row[9]){
                            echo'
                            <td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
			    <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';

                                                     }
               	}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ()
{

        // display current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"].
	    " location: ".$_SESSION["location"]."<br>";

    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table21.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>MotorCycle Form</h2></td></tr>
			<tr><td>Year: </td><td><input type="edit" name="Year" value="" size="20"></td></tr>
			<tr><td>Make: </td><td><input type="edit" name="Make" value="" size="30"></td></tr>
			<tr><td>Model: </td><td><input type="edit" name="Model" value="" size="20"></td></tr>
			<tr><td>DateListed: </td><td><input type="edit" name="DateListed" value="" size="30"></td></tr>
			<tr><td>PriceListed: </td><td><input type="edit" name="PriceListed" value="" size="20"></td></tr>
                	<tr><td>DateSold: </td><td><input type="edit" name="DateSold" value="" size="20"></td></tr> 
                	<tr><td>PriceSold: </td><td><input type="edit" name="PriceSold" value="" size="20"></td></tr>

                      <tr><td>Location ID: </td><td><textarea style="resize: none;" name="location_id" cols="40" rows="3"></textarea></td></tr>
		      <tr><td>User ID: </td><td><textarea style="resize: none;" name="user_id" cols="40" rows="3"></textarea></td></tr>

			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table21.php" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
                 // display current user and location_id
	         echo "You are logged in as user: ".$_SESSION["user"]. " location: ".$_SESSION["location"]."<br>";

			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table21.php">
					<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
					<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>MotorCycle Form</h2></td></tr>
						        <tr><td>Year: </td><td><input type="edit" name="Year" value="' . $row[1] . '" size="20"></td></tr>
							<tr><td>Make: </td><td><input type="edit" name="Make" value="' . $row[2] . '" size="30"></td></tr>
							<tr><td>Model: </td><td><input type="edit" name="Model" value="' . $row[3] . '" size="20"></td></tr>
							<tr><td>DateListed: </td><td><input type="edit" name="DateListed" value="' . $row[4] . '" size="30"></td></tr>
							<tr><td>PriceListed: </td><td><input type="edit" name="PriceListed" value="' . $row[5] . '" size="20"></td></tr>
                                                       	<tr><td>DateSold: </td><td><input type="edit" name="DateSold" value="' . $row[6] . '" size="20"></td></tr>
                                                 	<tr><td>PriceSold: </td><td><input type="edit" name="PriceSold" value="' . $row[7] . '" size="20"></td></tr>

                                                       <tr><td>Location ID: </td><td>
						       <textarea style="resize: none;" name="location_id" cols="40" rows="3">' . $row[8] . '</textarea></td></tr>
						       <tr><td>User ID: </td><td><textarea style="resize: none;" name="user_id" cols="40" rows="3">' . $row[9] . '</textarea></td></tr>


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
// Add location_id and user_id
    global $Year, $Make, $Model, $DateListed, $PriceListed, $DateSold, $PriceSold, $location_id, $user_id; //, $usertable;
//Add global usertable
global $usertable;
    
    $stmt = $mysqli->stmt_init();

// Add location_id and user_id and ??
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (Year, Make, Model, DateListed, PriceListed, DateSold, PriceSold, location_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))

    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
//Add ii and $location_id, $user_id
        $stmt->bind_param('sssssssii', $Year, $Make, $Model, $DateListed, $PriceListed, $DateSold, $PriceSold, $location_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $Year, $Make, $Model, $DateListed, $PriceListed, $DateSold, $PriceSold, $usertable,
// Add location_id and user_id
   $location_id, $user_id
;
// Add global usertable.
   global $usertable;

	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET Year = ?, Make = ?, Model = ?, DateListed = ?, PriceListed = ?, DateSold = ?, PriceSold = ?,

location_id=?, user_id=?  WHERE id = ?")) // Add location_id and user_id

    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
//Add ii and location_id, user_id
            $stmt->bind_param('sssssssiii', $Year, $Make, $Model, $DateListed, $PriceListed, $DateSold, $PriceSold, $location_id, $user_id, $index);
            $stmt->execute();
            $stmt->close();
    }
}

# ---------- updateRecord -----------------------------------------------------
function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table01.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
}

?>
