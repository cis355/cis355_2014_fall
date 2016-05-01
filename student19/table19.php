<?php
# --------------------------------------------------------------------------- #
# program: table19.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          to update this template, change all instances of "table19.php" to your php filename
#          and of course change the database table fields to match your database table
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

//Keeps session variables across multiple domains.
ini_set("session.cookie_domain", ".cis355.com");

// Start session and set logged in user variables.
session_start();

		ini_set('display_errors', 1);
		error_reporting(e_all);

// a. ---------- display (echo) html head and link to bootstrap css ----------
displayHTMLHead();

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table19";

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
	$viewSelected		= 8; // after user clicked viewSubmit button on list
	$viewCompleted		= 9; // after user clicked viewCompleted button on form
	$searchSelected		= 10; // after user clicked filter on the search panel
	$showAll			= 11; // after user clicked show all button on search panel
	
	$brand 				= $_POST['brand']; // if does not exist then value is ""
	$model 				= $_POST['model'];
	$color 				= $_POST['color'];
	$cond 				= $_POST['cond'];
	$price 				= $_POST['price'];
	$descript 			= $_POST['descript'];
	$location_id		= $_POST['location_id'];
	$user_email			= $_SESSION['user'];
	$user_id			= $_SESSION['id'];
	$user_location		= $_SESSION['location'];
	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	if( isset( $_POST['viewSelected'] ) ) $userSelection = $viewSelected;
	if( isset( $_POST['viewCompleted'] ) ) $userSelection = $viewCompleted;
	if( isset( $_POST['searchSelected'] ) ) $userSelection = $searchSelected;
	
	// the code above assumes this is the first time program is called 
	// unless one of the buttons named above was clicked
	
	// f. ---------- call function based on what user clicked ----------
	switch( $userSelection ):
	    case $firstCall: 
		    $msg = '';
		    showList($mysqli, $msg);		
			break;
		case $insertSelected:
		    showInsertForm($mysqli);
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
			//the echo below will reload the page so that it clears the post data so you can't refresh and duplicate entries.
			echo '<script>window.location="http://cis355.com/student19/table19.php";</script>';
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
		case $viewSelected:
			showViewForm($mysqli);
			break;
		case $viewCompleted:
			showList($mysqli, $msg);
			break;
		case $searchSelected:
			$msg = 'filter';
			showList($mysqli, $msg);
			break;
		case $showAll:
			$msg = "";
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
	    $sql = "CREATE TABLE table19 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "brand VARCHAR(30),";
	    $sql .= "model VARCHAR(20),";
	    $sql .= "color VARCHAR(30),";
	    $sql .= "cond VARCHAR(12),";
	    $sql .= "price VARCHAR(30),";
	    $sql .= "descript VARCHAR(100),";
		$sql .= "location_id INT,";
		$sql .= "user_id INT,";
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
	// Displays search function
	searchForm($mysqli);

    // this function gathers records from a "mysql table" and builds an "html table"
	
	global $usertable;
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table19.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="12" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Cellphone Database (table19)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Brand</td><td>Model</td>
			<td>Color</td><td>Condition</td><td>Price</td><td>Description</td><td>LocationID</td></tr>';
			

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable"); // get count of records in mysql table
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, else display "no records" message
	if( $countvalue > 0 )
	{
			populateTable($mysqli, $msg); // populate html table, from data in mysql table
	}
	else
	{
			echo '<br><h3 align="CENTER">No records in database table</h3><br>';
	}
	
	// display html buttons 
	echo    '</table>
			<input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">';
		
		if( ISSET( $_SESSION['id'] ) )
		{
			echo '
			<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary">
			<a href="bio.php" class="btn btn-success">View Bio</a></div></form>';
		}
		
	// add JavaScript functions to end of html body section
	// note: "hid" is id of item to be deleted; "uid" is id of item to be updated.
	// see also: populateTable function
	
	echo '<script>
			function setHid(num)
			{
				document.getElementById("hid").value = num;
			}
		    function setUid(num)
			{
				document.getElementById("uid").value = num;
		    }
			function confirmDelete(num)
			{
				var confirmation = confirm("Are you sure you want to delete the record?");
				if(confirmation)
				{
					setHid(num);
				}
			}
		 </script>';
}

# ---------- populateTable ---------------------------------------------------
// populate html table, from data in mysql table
function populateTable($mysqli, $msg)
{
	global $usertable, $msg, $searchQuery;
	
	if($msg == 'filter')
	{
		$localFilter = $_POST['localFilter'];
		$condFilter = $_POST['condFilter'];
		$brandFilter = $_POST['brandFilter'];
		
		//Create query options
		if($localFilter != "" && $condFilter == "" && $brandFilter == "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE location_id = $localFilter";
		}
		else if($localFilter == "" && $condFilter != "" && $brandFilter == "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE cond = '".$condFilter."'";
		}
		else if($localFilter == "" && $condFilter == "" && $brandFilter != "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE brand = '".$brandFilter."'";
		}
		else if($localFilter != "" && $condFilter != "" && $brandFilter == "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE location_id = $localFilter AND cond = '".$condFilter."'";
		}
		else if($localFilter == "" && $condFilter != "" && $brandFilter != "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE cond = '".$condFilter."' AND brand = '".$brandFilter."'";
		}
		else if($localFilter != "" && $condFilter == "" && $brandFilter != "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE location_id = $localFilter AND brand = '".$brandFilter."'";
		}
		else if($localFilter != "" && $condFilter != "" && $brandFilter != "")
		{
			$searchQuery = "SELECT * FROM $usertable WHERE location_id = $localFilter AND cond = '".$condFilter."' AND brand = '".$brandFilter."'";
		}
		
		if($result = $mysqli->query($searchQuery))
		{
			while($row = $result->fetch_row())
			{
				echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
				'</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . '</td>';
			
				echo '<td><input style="margin-left: 10px;" type="submit" name="viewSelected" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');" /></td>';
				if($_SESSION['id'] == $row[8])
				{
					echo '<td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="confirmDelete(' . $row[0] .')" /> 
					<input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
				}
				echo '<td></td>';
			}
		}
	}
	
	else
	{
		if($result = $mysqli->query("SELECT * FROM $usertable"))
		{
			while($row = $result->fetch_row())
			{
				echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
					'</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . '</td>';
			
				echo '<td><input style="margin-left: 10px;" type="submit" name="viewSelected" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');" /></td>';
				if($_SESSION['id'] == $row[8])
				{
					echo '<td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="confirmDelete(' . $row[0] .')" /> 
					<input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
				}
				echo '<td></td>';
			}
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
	global $user_location;
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table19.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Cellphone Form</h2></td></tr>
			<tr><td>Brand: </td><td>
				<select class="form-control" name="brand" id="brnd">
					<option value="">Select...</option>
					<option value="htc">HTC</option>
					<option value="samsung">Samsung</option>
					<option value="motorola">Motorola</option>
					<option value="lg">LG</option>
					<option value="apple">Apple Inc.</option>
					<option value="other">Other</option>
				</select>
			</td></tr>
			<tr><td>Model: </td><td><input class="form-control" type="edit" name="model" value="" size="30"></td></tr>
			<tr><td>Color: </td><td><input class="form-control" type="edit" name="color" value="" size="20"></td></tr>
			<tr><td>Condition: </td><td>
				<select class="form-control" name="cond" id="condit">
					<option value="">Select...</option>
					<option value="new">New</option>
					<option value="refurb">Refurbished</option>
					<option value="used">Used</option>
				</select>
			</td></tr>
			<tr><td>Price: </td><td><input class="form-control" type="edit" name="price" value="" size="20"></td></tr>
			<tr><td>Description: </td><td><textarea class="form-control" style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>
			
			
			<tr><td>Location: </td><td>';
				echo "<select class='form-control' name = 'location_id' id='location'>";
				if($sql_statement = $mysqli->query("SELECT * FROM locations")){
                  while($rowLoc = $sql_statement->fetch_object()){
                    if($rowLoc->location_id == $user_location){
                      echo"<option value='".$rowLoc->location_id. "' selected>".$rowLoc->name. "</option>";
                    }
                    else{
                      echo "<option value='".$rowLoc->location_id. "' >".$rowLoc->name. "</option>";
                    }
                  }
				  $sql_statement->close();
				  }
				 else
				    echo $mysqli->error;
                echo '</select>';
			
			echo '<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table19.php" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable, $location_id;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table19.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Cellphone Form</h2></td></tr>
							<tr><td>Brand: </td><td>
								<select class="form-control" name="brand" id="brnd">
									<option value="">Select...</option>
									<option value="htc" id="htc">HTC</option>
									<option value="samsung" id="samsung">Samsung</option>
									<option value="motorola" id="motorola">Motorola</option>
									<option value="lg" id="lg">LG</option>
									<option value="apple" id="apple">Apple Inc.</option>
									<option value="other" id="other">Other</option>
									
								<script language="JavaScript">
									document.getElementById("'.$row[1].'").defaultSelected = true;
								</script>
								</select>
							</td></tr>
							<tr><td>Model: </td><td><input class="form-control" type="edit" name="model" value="' . $row[2] . '" size="30"></td></tr>
							<tr><td>Color: </td><td><input class="form-control" type="edit" name="color" value="' . $row[3] . '" size="20"></td></tr>
							<tr><td>Condition: </td><td>
								<select class="form-control" name="cond" id="condit" selected="'. $row[4]. '">
									<option value="">Select...</option>
									<option value="new" id="new">New</option>
									<option value="refurb" id="refurb">Refurbished</option>
									<option value="used" id="used">Used</option>
									
								<script language="JavaScript">
									document.getElementById("'.$row[4].'").defaultSelected = true;
								</script>
								</select>
							</td></tr>
							<tr><td>Price: </td><td><input class="form-control" type="edit" name="price" value="' . $row[5] . '" size="20"></td></tr>
							<tr><td>Description: </td><td><textarea class="form-control" style="resize: none;" name="descript" cols="40" rows="3">' . $row[6] . '</textarea></td></tr>
							
							<tr><td>Location: </td><td>
								<select class="form-control" name = "location_id" id="location">';
								if($sql_statement = $mysqli->query("SELECT * FROM locations")){
									while($rowLoc = $sql_statement->fetch_object()){
										if($rowLoc->location_id == $row[7]){
											echo'<option value="'.$rowLoc->location_id. '" selected="selected">'.$rowLoc->name. '</option>';
										}
										else{
											echo '<option value="'.$rowLoc->location_id. '" >'.$rowLoc->name. '</option>';
										}
									}
									$sql_statement->close();
								}
								else
									echo $mysqli->error;
								echo '</select>
								
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

#---------------- showViewForm ---------------------------------
function showViewForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table19.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Cellphone Form</h2></td></tr>
							<tr><td>Brand: </td><td>
								<select class="form-control" name="brand" id="brnd" disabled>
									<option value="">Select...</option>
									<option value="htc" id="htc">HTC</option>
									<option value="samsung" id="samsung">Samsung</option>
									<option value="motorola" id="motorola">Motorola</option>
									<option value="lg" id="lg">LG</option>
									<option value="apple" id="apple">Apple Inc.</option>
									<option value="other" id="other">Other</option>
									
								<script language="JavaScript">
									document.getElementById("'.$row[1].'").defaultSelected = true;
								</script>
								</select>
							</td></tr>
							<tr><td>Model: </td><td><input class="form-control" type="edit" name="model" value="' . $row[2] . '" size="30" disabled></td></tr>
							<tr><td>Color: </td><td><input class="form-control" type="edit" name="color" value="' . $row[3] . '" size="20" disabled></td></tr>
							<tr><td>Condition: </td><td>
								<select class="form-control" name="cond" id="condit" selected="'. $row[4]. '" disabled>
									<option value="">Select...</option>
									<option value="new" id="new">New</option>
									<option value="refurb" id="refurb">Refurbished</option>
									<option value="used" id="used">Used</option>
									
								<script language="JavaScript">
									document.getElementById("'.$row[4].'").defaultSelected = true;
								</script>
								</select>
							</td></tr>
							<tr><td>Price: </td><td><input class="form-control" type="edit" name="price" value="' . $row[5] . '" size="20" disabled></td></tr>
							<tr><td>Description: </td><td><textarea class="form-control" style="resize: none;" name="descript" cols="40" rows="3" disabled>' . $row[6] . '</textarea></td></tr>
							
							<tr><td>Location: </td><td>
								<select class="form-control" name = "location_id" id="location" disabled>';
								if($sql_statement = $mysqli->query("SELECT * FROM locations")){
									while($rowLoc = $sql_statement->fetch_object()){
										if($rowLoc->location_id == $row[7]){
											echo'<option value="'.$rowLoc->location_id. '" selected="selected">'.$rowLoc->name. '</option>';
										}
										else{
											echo '<option value="'.$rowLoc->location_id. '" >'.$rowLoc->name. '</option>';
										}
									}
									$sql_statement->close();
								}
								else
									echo $mysqli->error;
								echo '</select>
							
							<tr><td><input type="submit" name="viewCompleted" class="btn btn-primary" value="Return"></td>
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
    global $brand, $model, $color, $cond, $price, $descript, $user_id, $location_id, $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (brand,model,color,cond,price,descript,location_id,user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('ssssssii', $brand, $model, $color, $cond, $price, $descript, $location_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
	
	
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $brand, $model, $color, $cond, $price, $descript, $location_id, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET brand = ?, model = ?, color = ?, cond = ?, price = ?, descript = ?, location_id = ? WHERE id = ?"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('ssssssii', $brand, $model, $color, $cond, $price, $descript, $location_id, $index);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- displayHTMLHead -----------------------------------------------------
function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table19.php</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
	
echo '<div class="col-md-12" style="background-color: tan; border-bottom: 
    2px solid black; box-shadow: 3px 3px 5px #888888;">
	<a href="../student14/landing.php"><img src="../student14/LWIP_logo.png" style="margin-top: 5px;"></a>';
if ($_SESSION["user"] != '')
{
	$user = $_SESSION['user'];
	echo '<p style="font-size:18px; float: right; margin-top: 40px; 
	    margin-right: 20px;">Welcome <b>' .	$user . '</b>!</p>';
}
else
{
	echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" 
	    action="../student14/login.php">
		<input type="text" size="9" name="username" class="form-control" placeholder="Username">
		<input type="password" size="9" name="password" class="form-control" placeholder="Password">
		<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
	    </form>';
}
echo '<br><br></div>';
}

//Function for search form
function searchForm($mysqli)
{
  echo'<br />
		<div class="col-md-7">
			<form name="basic" method="POST" action="table19.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
					<tr>
						<td><select class="form-control" name="brandFilter">
							<option value="">Search Brand</option>
							<option value="htc">HTC</option>
							<option value="samsung">Samsung</option>
							<option value="motorola">Motorola</option>
							<option value="lg">LG</option>
							<option value="apple">Apple Inc.</option>
							<option value="other">Other</option>
						</select></td>
						
						<td><select class="form-control" name="condFilter">
								<option value="">Search Condition</option>
								<option value="new" id="new">New</option>
								<option value="refurb" id="refurb">Refurbished</option>
								<option value="used" id="used">Used</option>
						</select></td>
						
						<td><select class="form-control" name = "localFilter" id="location">';
						if($sql_statement = $mysqli->query("SELECT * FROM locations")){
							echo '<option value="">Search Location</option>';
							while($rowLoc = $sql_statement->fetch_object()){
								echo "<option value='".$rowLoc->location_id. "' >".$rowLoc->name. "</option>";
							}
						$sql_statement->close();
						}
						else
							echo $mysqli->error;
						echo '</select></td>';
						echo '<td><input style="margin-left: 10px;" type="submit" name="searchSelected" class="btn btn-success" value="Filter" >
							  <td><input type="submit" name="showAll" class="btn btn-primary" value="Show All Records"></td>
					</tr>
				</table>
			</form>
		<br></div>';
						
}

?>