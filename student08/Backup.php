<?php
	ini_set("session.cookie_domain", ".cis355.com");
//VALIDATION BEGIN
			session_start();
			
// a. ---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	<head>
	<title>Look at the Bikes!</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
    </head><body>
	<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="http://cis355.com/student14/landing.php"><img src="LWIP_logo.png" style="margin-top: 5px;"></a>
		<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>mbcoppo@svsu.edu</b>!</p>		<br>
		<br>
	</div>
	<div class="col-md-12">
	<br/>';


// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table08";

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
	
    $name 				= $_POST['name']; // if does not exist then value is ""
	$zip 				= $_POST['zip'];
	$model 				= $_POST['model'];
	$wheel 				= $_POST['wheel'];
	$color  			= $_POST['color'];
	$price 				= $_POST['price'];
	$descript 			= $_POST['descript'];

 	
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
		    $msg = '';
		    showList($mysqli, $msg);
			break;
		case $insertSelected:
		    showInsertForm();
			break;
		case $updateSelected :
		    showUpdateForm($mysqli);
			break;
		case $deleteSelected:        // currently no confirmation form is displayed on delete
			// showDeleteForm($mysqli);
			deleteRecord($mysqli);   // selecting delete immediately deetes the record
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted: 
		    insertRecord($mysqli);
			$msg = 'record inserted';
			showList($mysqli, $msg);
			break;
		case $updateCompleted:
		    updateRecord($mysqli);
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (possible upgrade later?)
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
	    $sql .= "CREATE TABLE table08 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "name VARCHAR(20),";
	    $sql .= "zip VARCHAR(30),";
	    $sql .= "model VARCHAR(20),";
	    $sql .= "wheel VARCHAR(30),";
	    $sql .= "color VARCHAR(20),";
	    $sql .= "price VARCHAR(30),";
	    $sql .= "descript VARCHAR(100),";
		$sql .= "user_id INT,";
		$sql .= "location_id INT,";
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
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table08.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="11" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Bikes We Paid For </h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Name</td><td>Zip Code</td>
			<td>Model</td><td>Wheel Size</td><td>Bike Color</td><td>Price</td><td>Description</td><td>User ID</td><td>Location ID</td><td></td></tr>';
			

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
			 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . '</td><td>' . $row[8] . '</td><td>' . $row[9] . 
				 '</td><td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
				 <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ()
{
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table08.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Bike Form</h2></td></tr>
			<tr><td>Name: </td><td><input type="edit" name="name" value="" size="20"></td></tr>
			<tr><td>Zip: </td><td><input type="edit" name="zip" value="" size="20"></td></tr>
			<tr><td>Bike Model: </td><td><input type="edit" name="model" value="" size="30"></td></tr>
			<tr><td>Wheel Size: </td><td><input type="edit" name="wheel" value="" size="20"></td></tr>
			<tr><td>Bike Color: </td><td><input type="edit" name="color" value="" size="30"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" size="20"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table08.php" class="btn btn-primary">Display Database</a></form></div>';
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
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table08.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Bike Form</h2></td></tr>
							<tr><td>Name: </td><td><input type="edit" name="name" value="'. $row[1] .'" size="20"></td></tr>
							<tr><td>Zip Code: </td><td><input type="edit" name="zip" value="' . $row[2] . '" size="20"></td></tr>
							<tr><td>Bike Model: </td><td><input type="edit" name="model" value="' . $row[3] . '" size="30"></td></tr>
							<tr><td>Wheel Size: </td><td><input type="edit" name="wheel" value="' . $row[4] . '" size="20"></td></tr>
							<tr><td>Bike Color: </td><td><input type="edit" name="color" value="' . $row[5] . '" size="30"></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[6] . '" size="20"></td></tr>
							<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[7] . '</textarea></td></tr>
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
    global $name, $zip, $model, $wheel, $color, $price, $descript, $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (name,zip,model,wheel,color,price,descript) VALUES (?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssss', $name, $zip, $model, $wheel, $color, $price, $descript);
        $stmt->execute();
	echo "Record Inserted!";
        $stmt->close();
    }
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $name, $zip, $model, $wheel, $color, $price, $descript, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET name = ?, zip = ?, model = ?, wheel = ?, color = ?, price = ?, descript = ? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('sssssssi', $name, $zip, $model, $wheel, $color, $price, $descript, $index);
            $stmt->execute();
            $stmt->close();
    }
}

?>