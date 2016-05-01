<?php
# --------------------------------------------------------------------------- #
# program: table17.php
# author:  Alexys Suisse
# course:  cis355 fall 2014
# purpose: used template from cis355 class posted by George Corser
#------------------------------------------------------------------------------
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

#-------------------------------------------------------------------------------------------------

// ---------- a. display (echo) html head and link to bootstrap css -----------
// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// ---------- b. set connection variables and verify connection ---------------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table17";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

// ---------- if successful connection...
if($mysqli)            
{
    // ---------- c. create table, if necessary -------------------------------
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
	
	$itemName 		= $_POST['itemName']; // if does not exist then value is ""
	$manufacturer	= $_POST['manufacturer'];
	$year			= $_POST['year'];
	$doorStyle		= $_POST['doorStyle'];
	$itemCondition		= $_POST['itemCondition'];
	$defrostType	= $_POST['defrostType'];
	$color			= $_POST['color'];
	$amountPaid		= $_POST['amountPaid'];
	$descript		= $_POST['descript'];
		
    // ---------- e. determine what user clicked ------------------------------
	// the $_POST['buttonName'] is the name of the button clicked in browser
	$userSelection = $firstCall; // assumes first call unless button was clicked
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	
	// f. ---------- call function based on what user clicked ----------
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
    /* check connection */
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
	    $sql = "CREATE TABLE table17 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "itemName VARCHAR(30),";
	    $sql .= "manufacturer VARCHAR(20),";
	    $sql .= "year VARCHAR(5),";
	    $sql .= "doorStyle VARCHAR(30),";
	    $sql .= "itemCondition VARCHAR(20),";
		$sql .= "defrostType VARCHAR(30),";
	    $sql .= "color VARCHAR(20),";
	    $sql .= "amountPaid VARCHAR(10),";
	    $sql .= "descript VARCHAR(100)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
    }
}

# ---------- showList --------------------------------------------------------
// this function gathers records from a "mysql table" and builds an "html table"
function showList($mysqli, $msg)
{
    global $usertable;
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="Table17.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Deep Freezer Database (Table 17)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;">
			<td>ID</td>
			<td>Item Name</td>
			<td>Manufacturer</td>
			<td>Year</td>
			<td>Style Of Door</td>
			<td>Condition</td>
			<td>Defrost Type</td>
			<td>Color</td>
			<td>Amount Paid</td>
			<td>Description</td>
			<td></td></tr>';
		
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
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . 
				 '</td><td>' . $row[8] . '</td><td>' . $row[9] . 
				 '</td><td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
				 <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ()
{
    echo   '<div class="col-md-4">
			<form name="basic" method="POST" action="Table17.php" onSubmit="return validate();">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
					<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
					<h2>Deep Freezer Form</h2></td></tr>
					<tr><td>Item Name</td><td><input type="edit" name="itemName" value="" size="30"></td></tr>
					<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="" size="20"></td></tr>
					<tr><td>Year</td><td><input type="edit" name="year" value="" size="5"></td></tr>
					<tr><td>Style Of Door</td>
					<td><select name="doorStyle">
						<option>Single Door</option>
						<option>Double Door</option>
						<option>Lift Lid</option>
						<option>Single Door and Drawer Combo</option>
						<option>Double Door and Drawer Combo</option>
					</select></td></tr>
					<tr><td>itemCondition </td>
					<td><select name="itemCondition">
						<option>Excellent</option>
						<option>Good</option>
						<option>Average</option>
						<option>Poor</option>
						<option>Very Poor</option>
					</select></td></tr>
					<tr><td>Defrost Type </td>
					<td><select name="defrostType">
						<option>Manual</option>
						<option>Automatic</option>
					</select></td></tr>
					<tr><td>Color</td><td><input type="edit" name="color" value="" size="20"></td></tr>
					<tr><td>Amount Paid $</td><td><input type="edit" name="amountPaid" value="" size="10"></td></tr>
					<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>
					<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
					<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
				</table>
				<a href="Table17.php" class="btn btn-primary">Display Database</a>
			</form>
			</div>';
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
					<form name="basic" method="POST" action="Table17.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
							<h2>Deep Freezer Form</h2></td></tr>
							<tr><td>Item Name</td><td><input type="edit" name="itemName" value="'. $row[1] .'" size="30"></td></tr>
							<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="'. $row[2] .'" size="20"></td></tr>
							<tr><td>Year</td><td><input type="edit" name="year" value="'. $row[3] .'" size="5"></td></tr>
							<tr><td>Style Of Door</td>
							<td><select name="doorStyle" value="'. $row[4] .'">
								<option>Single Door</option>
								<option>Double Door</option>
								<option>Lift Lid</option>
								<option>Single Door and Drawer Combo</option>
								<option>Double Door and Drawer Combo</option>
							</select></td></tr>
							<tr><td>itemCondition </td>
							<td><select name="itemCondition" value="'. $row[5] .'">
								<option>Excellent</option>
								<option>Good</option>
								<option>Average</option>
								<option>Poor</option>
								<option>Very Poor</option>
							</select></td></tr>
							<tr><td>Defrost Type </td>
							<td><select name="defrostType" value="'. $row[6] .'">
								<option>Manual</option>
								<option>Automatic</option>
							</select></td></tr>
							<tr><td>Color</td><td><input type="edit" name="color" value="'. $row[7] .'" size="20"></td></tr>
							<tr><td>Amount Paid $</td><td><input type="edit" name="amountPaid" value="'. $row[8] .'" size="10"></td></tr>
							<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">'. $row[9] .'</textarea></td></tr>
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
    global $itemName, $manufacturer, $year, $doorStyle, $itemCondition, $defrostType, $color, $amountPaid, $descript, $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (itemName, manufacturer, year, doorStyle, itemCondition, defrostType, color, amountPaid, descript) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssssss', $itemName, $manufacturer, $year, $doorStyle, $itemCondition, $defrostType, $color, $amountPaid, $descript);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $itemName, $manufacturer, $year, $doorStyle, $itemCondition, $defrostType, $color, $amountPaid, $descript, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET itemName = ?, manufacturer = ?, year = ?, doorStyle = ?, itemCondition = ?, defrostType = ?, color = ?, amountPaid = ?, descript = ? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('sssssssssi', $itemName, $manufacturer, $year, $doorStyle, $itemCondition, $defrostType, $color, $amountPaid, $descript, $index);
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
		<title>Table17.php</title>
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		</head>
	<body>';
}

?>
