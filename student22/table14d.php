<?php
# --------------------------------------------------------------------------- #
# program: table14d.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          instructions: change all "table14d.php" to your php filename, and 
#          change the database table fields to match your database table
# --------------------------------------------------------------------------- #
# input:   $_POST, or nothing
#
# processing: this program is called by a browser and excecuted on a server.
#          a. display (echo) html head and link to bootstrap css
#          b. set connection variables and verify connection
#             if successful connection ...
#             c. create table, if necessary
#             d. initialize userSelection and $_POST variables 
#             e. determine what user clicked
#             f. call function based on what user clicked
#                1. if nothing selected (first time), 
#                   display database table contents, 
#                2. if insert selected, show a form 
#                   to insert a new record in database table,
#                3. if update selected, show a form 
#                   to update an existing record in database table,
#                4. if delete selected, show a form 
#                   to delete an existing record in database table,
#                   // currently no confirmation form is displayed on delete
#                   // selecting delete immediately deletes the record
#                5. if insert completed, insert a new record in database table, 
#                   then display database table contents,
#                6. if update completed, update right record in database table, 
#                   then display database table contents,
#                7. if delete completed, delete right record in database table, 
#                   then display database table contents.
#                   // this case never occurs because delete is immediate
# 
# functions:
#                checkConnect
#                createTable
#                showList
#                populateTable
#                showInsertForm
#                showUpdateForm
#                deleteRecord
#                insertRecord
#                updateRecord
#
# output:  HTML code 
# --------------------------------------------------------------------------- #


// ---------- a. display (echo) html head and link to bootstrap css -----------
// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// ---------- b. set connection variables and verify connection ---------------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table22";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

// ---------- if successful connection...
if($mysqli)            
{
    // ---------- c. create table, if necessary -------------------------------
	createTable($mysqli); 
	
	// ---------- d. initialize userSelection and $_POST variables ------------
	$userSelection 		= 0;
	$firstCall 			= 1; // first time program is called
	$insertSelected 	= 2; // after user clicked insertSelected button on list 
	$updateSelected 	= 3; // after user clicked updateSelected button on list 
	$deleteSelected 	= 4; // after user clicked deleteSelected button on list 
	$insertCompleted 	= 5; // after user clicked insertSubmit button on form
	$updateCompleted 	= 6; // after user clicked updateSubmit button on form
	$deleteCompleted 	= 7; // after user clicked deleteSubmit button on form
	
    $boat_type 			= $_POST['boat_type']; // if does not exist then value is ""
	$brand 				= $_POST['brand'];
	$engine_type 		= $_POST['engine_type'];
	$color 				= $_POST['horse_power'];
	$strWind 			= $_POST['size'];
	$price 				= $_POST['paid'];
	$descript 			= $_POST['descript'];
	
    // ---------- e. determine what user clicked ------------------------------
	// the $_POST['buttonName'] is the name of the button clicked in browser
	$userSelection = $firstCall; // assumes first call unless button was clicked
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	
	// ---------- f. call function based on what user clicked -----------------
	switch( $userSelection ):
	    case $firstCall: 
		    $msg = '';
			displayHTMLHead();
		    showList($mysqli, $msg);
			break;
		case $insertSelected:
			displayHTMLHead();
		    showInsertForm();
			break;
		case $updateSelected :
			displayHTMLHead();
		    showUpdateForm($mysqli);
			break;
		case $deleteSelected:    
			// displayHTMLHead();		
			// showDeleteForm($mysqli); // currently no form is displayed
			deleteRecord($mysqli);   // delete is immediate (no confirmation)
			displayHTMLHead();
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted: // updated to do Post/Redirect/Get (PRG)
		    insertRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
			displayHTMLHead();
			$msg = 'record inserted';
			showList($mysqli, $msg);
			break;
		case $updateCompleted:
		    updateRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']);
			displayHTMLHead();
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (see above)
		    deleteRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']);
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
	    $sql = "CREATE TABLE table22 
		       (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "boat_type VARCHAR(20),";
	    $sql .= "brand VARCHAR(30),";
	    $sql .= "engine_type VARCHAR(20),";
	    $sql .= "horse_power VARCHAR(30),";
	    $sql .= "size VARCHAR(20),";
	    $sql .= "price VARCHAR(30),";
	    $sql .= "descript VARCHAR(100),";
		$sql .= "user_id INT,";
		$sql .= "location_id INT,";
		$sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id),";
		$sql .= "FOREIGN KEY (location_id) REFERENCES locations (location_id)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
    }
}

# ---------- showList ---------------------------------------------------------
// this function gets records from a "mysql table" and builds an "html table"
function showList($mysqli, $msg) 
{
	global $usertable;
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table14d.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px; 
			box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; 
			color: white; background-color:#333333;">
			<h2 style="color: white;">Boat database (table22)</h2>
			</td></tr><tr style="font-weight:800; font-size:20px;">
			<td>id</td><td>Boat Type</td><td>Brand</td>
			<td>Engine Type</td><td>Horse Power</td><td>Size</td>
			<td>Price</td><td>Description</td><td></td></tr>';

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable");
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, 
	// else display "no records" message
	if( $countvalue > 0 )
	{
			populateTable($mysqli); // populate html table, from mysql table
	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}
	
	// display html buttons 
	echo    '</table>
			<input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">
			<input type="submit" name="insertSelected" value="Add an Entry" 
			class="btn btn-primary"">
			</form></div>';

	// add JavaScript functions to end of html body section
	// "hid" is id of item to be deleted
	// "uid" is id of item to be updated.
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

# ---------- populateTable ----------------------------------------------------
// populate html table, from data in mysql table
function populateTable($mysqli)
{
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . 
			    $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				'</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . 
				$row[7] . '</td><td><input name="deleteSelected" type="submit" 
				class="btn btn-danger" value="Delete" onclick="setHid(' . 
				$row[0] .')" /> <input style="margin-left: 10px;" type="submit" 
				name="updateSelected" class="btn btn-primary" value="Update" 
				onclick="setUid(' . $row[0] . ');" />';
		}
	}
	$result->close();
}

# ---------- showInsertForm ---------------------------------------------------
function showInsertForm ()
{
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table14d.php" 
	    onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; 
		    border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; 
			    color: white; background-color:#333333;">
			<h2>Boat Form</h2></td></tr>
			<tr><td>Boat Type: </td><td><input type="edit" name="boat_type" value="" 
			size="20"></td></tr>
			<tr><td>Brand: </td><td><input type="edit" name="brand" value="" 
			size="20"></td></tr>
			<tr><td>Engine Type: </td><td><input type="edit" name="engine_type" value="" 
			size="30"></td></tr>
			<tr><td>Horse Power: </td><td><input type="edit" name="horse_power" value="" 
			size="20"></td></tr>
			<tr><td>Size: </td><td><input type="edit" name="size" 
			value="" size="30"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" 
			size="20"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" 
			name="descript" cols="40" rows="3"></textarea></td></tr>
			<tr><td><input type="submit" name="insertCompleted" 
			class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" 
			class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table14d.php" class="btn btn-primary">
		Display Database</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table14d.php">
						<table class="table table-condensed" 
						    style="border: 1px solid #dddddd; 
							border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; 
							border-radius: 5px; color: white; 
							background-color:#333333;">
							<h2>Boat Form</h2></td></tr>
							<tr><td>Boat Type: </td><td><input type="edit" 
							name="boat_type" value="'. $row[1] .'" size="20">
							</td></tr>
							<tr><td>Brand: </td><td><input type="edit" 
							name="brand" value="' . $row[2] . '" size="20">
							</td></tr>
							<tr><td>Engine Type: </td><td><input type="edit" 
							name="engine_type" value="' . $row[3] . '" size="30">
							</td></tr>
							<tr><td>Horse Power: </td><td><input type="edit" 
							name="horse_power" value="' . $row[4] . '" size="20">
							</td></tr>
							<tr><td>Size: </td><td><input type="edit" 
							name="size" value="' . $row[5] . '" size="30">
							</td></tr>
							<tr><td>Price: </td><td><input type="edit" 
							name="price" value="' . $row[6] . '" size="20">
							</td></tr>
							<tr><td>Description: </td><td><textarea 
							style="resize: none;" name="descript" cols="40" 
							rows="3">' . $row[7] . '</textarea></td></tr>
							<tr><td><input type="submit" name="updateCompleted" 
							class="btn btn-primary" value="Update Entry"></td>
							<td style="text-align: right;"><input type="reset" 
							class="btn btn-danger" value="Reset Form"></td></tr>
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
					</form>
				</div>';
		}
		$result->close();
	}
}

# ---------- deleteRecord -----------------------------------------------------
function deleteRecord($mysqli)
{
	$index = $_POST['hid'];  // "hid" is id of db record to be deleted
	global $usertable;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("DELETE FROM $usertable WHERE id=?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('i', $index);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- insertRecord -----------------------------------------------------
function insertRecord($mysqli)
{
    global $boat_type, $brand, $engine_type, $horse_power, $size, $price, $descript;
	global $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (boat_type,brand,engine_type,horse_power,
	    size,price,descript) VALUES (?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('sssssss', $boat_type, $brand, $engine_type, $horse_power, $size, 
		    $price, $descript);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord -----------------------------------------------------
function updateRecord($mysqli)
{
	global $boat_type, $brand, $engine_type, $horse_power, $size, $price, $descript; 
	global $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET boat_type = ?, brand = ?, 
	    engine_type=?, horse_power=?, size=?, price=?, descript=? WHERE id = ?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('sssssssi', $boat_type, $brand, $engine_type, $horse_power, $size, 
		    $price, $descript, $index);
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
	<title>table14d.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
}

?>