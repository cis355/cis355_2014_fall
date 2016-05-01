<?php
# --------------------------------------------------------------------------- #
# program: table20.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          to update this template, change all instances of "table14d.php" to your php filename
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

    ini_set("session.cookie_domain", ".cis355.com");
// Begin VALIDATION of session start 
	session_start();

// a. ---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	<head>
	<title>table20.php</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
    </head><body>';


// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table20";

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
	$viewRecord         = 8;

	
    $item_name = $_POST['item_name'];
	$manufacturer = $_POST['manufacturer'];
	$year_of_rv = $_POST['year_of_rv'];
	$class_type = $_POST['class_type'];
	$condition_of_rv = $_POST['condition_of_rv'];
	$engine_type = $_POST['engine_type'];
	$color_of_rv = $_POST['color_of_rv'];
	$amt_paid = $_POST['amt_paid'];
	$descript = $_POST['descript'];
	$location_id        = $_POST['location_id'];
	$user_id        	= $_POST['user_id'];
	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	if( isset( $_POST['viewRecord'] ) ) $userSelection = $viewRecord;
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
			deleteRecord($mysqli);   // selecting delete immediately deletes the record
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted: 
			header("Location: " . $_SERVER['REQUEST_URI']);
		    insertRecord($mysqli);
			$msg = 'record inserted';
			showList($mysqli, $msg);
			break;
		case $updateCompleted:
			header("Location: " . $_SERVER['REQUEST_URI']);
		    updateRecord($mysqli);
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $viewRecord:
		    showViewForm($mysqli);
			//$msg = 'record updated';
			//showList($mysqli, $msg);
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
	    $sql = "CREATE TABLE table20 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
		$sql .= "item_name VARCHAR(30),";
	    $sql .= "manufacturer VARCHAR(30),";
	    $sql .= "year_of_rv VARCHAR(4),";
	    $sql .= "class_type VARCHAR(30),";
	    $sql .= "condition_of_rv VARCHAR(20),";
	    $sql .= "engine_type VARCHAR(30),";
	    $sql .= "color_of_rv VARCHAR(20),";
		$sql .= "amt_paid VARCHAR(20),";
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
	
	
	// display current user and location
	//echo "You are logged in as user: ".$_SESSION["user"].
	//     " at Location: ".$_SESSION["location"]."<br><br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12"><form action="table20.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="13" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: white;">Recreational Vehicles</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID</td><td>| Item</td><td>| Manufacturer</td><td>| Year</td><td>| RV Class</td><td>| Condition</td>
			<td>| Color</td><td>| Amount Paid</td></tr>';
			

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
	
	$get_user_id = $row[10];
	if ($_SESSION["id"] != "")
		{
		echo    '</table><input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">
			<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary">';
		}
		
		echo'<form method="POST" action="Bio20.html">
			<input type="submit" class="btn btn-success" value="View Author"/>
			</form>';
		
			
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
	
	loadBanner();
	
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			$get_user_id = $row[10];
			$get_loc_id = $row[11];
			
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[7] .
				 '</td><td>' . $row[8] .   
				 '</td><td><input type="submit" name="viewRecord" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');" /></td>';
				 
				if ($_SESSION["id"] == $get_user_id && $get_user_id != "")
				 {
				 echo '</td><td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /></td><td> 
				   <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" /></td><td></td>';
				 }
				else
				{
				  echo '</td><td></td><td></td>';
				}
				 
		}
	}
	
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
	loadBanner();
		 
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table20.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Recreational Vehicles</h2></td></tr>
			<tr><td>Item Name</td><td><input type="edit" name="item_name" value="" size="30"></td></tr>
			<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="" size="20"></td></tr>
			<tr><td>Year</td><td><input type="edit" name="year_of_rv" value="" size="5"></td></tr>
			<tr><td>Class Type </td>
			<td><input type="edit" name="class_type" value="" size="10"></td></tr>
			<tr><td>Condition </td>
			<td><input type="edit" name="condition_of_rv" value="" size="10"></td></tr>
			<tr><td>Engine Type</td>
			<td><input type="edit" name="engine_type" value="" size="15"></td></tr>
			<tr><td>Color</td><td><input type="edit" name="color_of_rv" value="" size="20"></td></tr>
			<tr><td>Amount Paid $</td><td><input type="edit" name="amt_paid" value="" size="10"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>';
			
		echo '<tr><td>Location ID: </td><td>';
        echo "<select class='form-control' name = 'location_id' id='location'>";
            //$sql_statement = $mysqli->stmt_init();
			//echo $mysqli->error;
                //if($sql_statement = $mysqli->prepare("SELECT * FROM locations")){
				if($sql_statement = $mysqli->query("SELECT * FROM locations")){
                // if ($sql_statement->execute()) {
                //    if ($sql_statement->bind_result($id,$location_name)) {
                  while($row = $sql_statement->fetch_object()){
                    if($row->location_id === $location_id){
                      echo"<option value='".$row->location_id. "' selected='selected'>".$row->name. "</option>";
                    }
                    else{
                      echo "<option value='".$row->location_id. "' >".$row->name. "</option>";
                    }
                  }
				  $sql_statement->close();
				  }
				 else
				    echo $mysqli->error;
				//}
				 // else
				//    echo $mysqli->error;
                //}
				//else
				//    echo $mysqli->error;
                echo '</select></td></tr>';																					
			echo '<tr><td>User ID: </td><td><textarea style="resize: none;" name="user_id" cols="40" rows="3"></textarea>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table>
		<a href="table20.php" class="btn btn-primary">Display Database</a>
	</form>
</div>';
}



# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	loadBanner();
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table20.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
						<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Recreational Vehicles</h2></td></tr>
						<tr><td>Item Name</td><td><input type="edit" name="item_name" value="'. $row[1] .'" size="30"></td></tr>
						<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="'. $row[2] .'" size="20"></td></tr>
						<tr><td>Year</td><td><input type="edit" name="year_of_rv" value="'. $row[3] .'" size="5"></td></tr>
						<tr><td>Class Type </td><td><input type="edit" name="class_type" value="'. $row[4] .'" size="10"></td></tr>
						<tr><td>Condition </td><td><input type="edit" name="condition_of_rv" value="'. $row[5] .'" size="10"></td></tr>
						<tr><td>Engine Type</td><td><input type="edit" name="engine_type" value="'. $row[6] .'" size="15"></td></tr>
						<tr><td>Color</td><td><input type="edit" name="color_of_rv" value="'. $row[7] .'" size="20"></td></tr>
						<tr><td>Amount Paid $</td><td><input type="edit" name="amt_paid" value="'. $row[8] .'" size="10"></td></tr>
						<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[9] . '</textarea></td></tr>
						<tr><td>User ID: </td><td><textarea style="resize: none;" name="user_id" cols="40" rows="3">' . $row[10] . '</textarea></td></tr>
						<tr><td>Location ID: </td><td>';
			echo "<select class='form-control' name = 'location_id' id='location'>";
            //$sql_statement = $mysqli->stmt_init();
			//echo $mysqli->error;
                //if($sql_statement = $mysqli->prepare("SELECT * FROM locations")){
				if($sql_statement = $mysqli->query("SELECT * FROM locations")){
                // if ($sql_statement->execute()) {
                //    if ($sql_statement->bind_result($id,$location_name)) {
                  while($row = $sql_statement->fetch_object()){
                    if($row->location_id === $location_id){
                      echo"<option value='".$row->location_id. "' selected='selected'>".$row->name. "</option>";
                    }
                    else{
                      echo "<option value='".$row->location_id. "' >".$row->name. "</option>";
                    }
                  }
				  $sql_statement->close();
				  }
				 else
				    echo $mysqli->error;
				
                echo '</select></td></tr>';																					
				echo '<tr><td><input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry"></td>
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
    global $item_name, $manufacturer, $year_of_rv, $class_type, $condition_of_rv, $engine_type, $color_of_rv, $amt_paid, $descript, $usertable, $location_id, $user_id;
	
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (item_name,manufacturer,year_of_rv,class_type,condition_of_rv,engine_type,color_of_rv,amt_paid,descript, location_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssssssii', $item_name, $manufacturer, $year_of_rv, $class_type, $condition_of_rv, $engine_type, $color_of_rv, $amt_paid, $descript, $location_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
	
	
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $item_name, $manufacturer, $year_of_rv, $class_type, $condition_of_rv, $engine_type, $color_of_rv, $amt_paid, $descript, $usertable, $location_id, $user_id;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET item_name = ?, manufacturer = ?, year_of_rv = ?, class_type = ?, condition_of_rv = ?, engine_type = ?, color_of_rv = ?, amt_paid = ?, descript = ?, location_id = ?, user_id = ? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('sssssssssiii', $item_name, $manufacturer, $year_of_rv, $class_type, $condition_of_rv, $engine_type, $color_of_rv, $amt_paid, $descript, $location_id, $user_id, $index);
            $stmt->execute();
            $stmt->close();
    }
}

function loadBanner()
{
echo'<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="../student14/landing.php"><img src="LWIP_logo.png" style="margin-top: 5px;"></a>
		<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>'. $_SESSION["user"] .'</b>!</p>
	    </div><br><br>';

}

function showViewForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	loadBanner();
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table20.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
						<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Recreational Vehicles</h2></td></tr>
						<tr><td>Item Name</td><td><input type="edit" name="item_name" value="'. $row[1] .'" size="30" disabled></td></tr>
						<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="'. $row[2] .'" size="20" disabled></td></tr>
						<tr><td>Year</td><td><input type="edit" name="year_of_rv" value="'. $row[3] .'" size="5" disabled></td></tr>
						<tr><td>Class Type </td><td><input type="edit" name="class_type" value="'. $row[4] .'" size="10" disabled></td></tr>
						<tr><td>Condition </td><td><input type="edit" name="condition_of_rv" value="'. $row[5] .'" size="10" disabled></td></tr>
						<tr><td>Engine Type</td><td><input type="edit" name="engine_type" value="'. $row[6] .'" size="15" disabled></td></tr>
						<tr><td>Color</td><td><input type="edit" name="color_of_rv" value="'. $row[7] .'" size="20" disabled></td></tr>
						<tr><td>Amount Paid $</td><td><input type="edit" name="amt_paid" value="'. $row[8] .'" size="10" disabled></td></tr>
						<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3" disabled>' . $row[9] . '</textarea></td></tr>
						<tr><td>User ID: </td><td><textarea style="resize: none;" name="user_id" cols="40" rows="3" disabled>' . $row[10] . '</textarea></td></tr>
						<tr><td>Location ID: </td><td>';
			echo "<select class='form-control' name = 'location_id' id='location'>";
            //$sql_statement = $mysqli->stmt_init();
			//echo $mysqli->error;
                //if($sql_statement = $mysqli->prepare("SELECT * FROM locations")){
				if($sql_statement = $mysqli->query("SELECT * FROM locations")){
                // if ($sql_statement->execute()) {
                //    if ($sql_statement->bind_result($id,$location_name)) {
                  while($row = $sql_statement->fetch_object()){
                    if($row->location_id === $location_id){
                      echo"<option value='".$row->location_id. "' selected='selected'>".$row->name. "</option>";
                    }
                    else{
                      echo "<option value='".$row->location_id. "' >".$row->name. "</option>";
                    }
                  }
				  $sql_statement->close();
				  }
				 else
				    echo $mysqli->error;
				
                echo '</select></td></tr>';																					
				echo '<tr><td><a href="table20.php" class="btn btn-primary">Display Database</a></td>		
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
					</form>
				</div>';
		}
		$result->close();
	}

}
?>