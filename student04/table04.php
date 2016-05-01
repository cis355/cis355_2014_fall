<?php
# --------------------------------------------------------------------------- #
# program: table04.php
# author:  george corser
# editor:  Olivia Archambo
# course:  cis355 fall 2014
# purpose: template for cis355
#          to update this template, change all instances of "table04.php" to your php filename
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
		session_start();

// a. ---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	<head>
	<title>table04.php</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
    </head><body>';


// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table04";

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
	
    $type 				= $_POST['type']; // if does not exist then value is ""
	$brand 				= $_POST['brand'];
	$cond 				= $_POST['cond'];
	$drweight			= $_POST['drweight'];
	$drlength 			= $_POST['drlength'];
	$price 				= $_POST['price'];
	$descript 			= $_POST['descript'];
	$loc_id				= $_POST['loc_id'];
	$user_id			= $_POST['user_id'];
	
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
		//$_SESSION["user"] = 1;
		//$_SESSION["location"] = 3;
		//print_r($_SESSION);
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
			// showDeleteForm($mysqli);
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
	    $sql = "CREATE TABLE table04 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "type VARCHAR(20),";
	    $sql .= "brand VARCHAR(30),";
	    $sql .= "cond VARCHAR(20),";
	    $sql .= "drweight VARCHAR(30),";
	    $sql .= "drlength VARCHAR(20),";
	    $sql .= "price VARCHAR(30),";
	    $sql .= "descript VARCHAR(100)";
		$sql .= "loc_id INT,";
		$sql .= "user_id INT,";
		$sql .= "FOREIGN KEY(user_id) REFERENCES users(user_id),";
		$sql .= "FOREIGN KEY(loc_id) REFERENCES locations(location_id)";
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
	echo "You are logged in as user:" . $_SESSION["user"].
		 " location: " . $_SESSION["location"] . "<br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table04.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="11" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Compound Bow Database (table04)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Type</td><td>Brand</td>
			<td>Condition</td><td>Draw Weight</td><td>Draw Length</td><td>Price</td><td>Location</td><td></td></tr>';
			

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
	echo '<center><a href="http://www.cis355.com/student04/bio.php">Bio for Olivia Archambo/Table04 LWIP</a></center>';
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
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[8];
				 
			echo
				'</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');"/></td>';
				 
		if ($_SESSION["id"]==$row[9]) {				 
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
	echo "You are logged in as user:" . $_SESSION["user"].
		 " location: " . $_SESSION["location"] . "<br>";
		 
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table04.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Compound Bow Form</h2></td></tr>
			<tr><td>Type: </td><td><input type="edit" name="type" value="" size="20"></td></tr>
			<tr><td>Brand: </td><td><input type="edit" name="brand" value="" size="20"></td></tr>
			<tr><td>Condition: </td><td><input type="edit" name="cond" value="" size="15"></td></tr>
			<tr><td>Draw Weight: </td><td><input type="edit" name="drweight" value="" size="15"></td></tr>
			<tr><td>Draw Length: </td><td><input type="edit" name="drlength" value="" size="15"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" size="10"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>';
// delete if you can't get working			
	 echo '<tr><td>Location ID: </td><td>';
            echo "<select class='form-control' name = 'loc_id' id='location'>";
				if($sql_statement = $mysqli->query("SELECT * FROM locations")){
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
                echo "</select>";	
//...through here...
	echo
			'</td></tr>
			<tr><td>User ID: </td><td><input type="text" name="user_id" value="'. $_SESSION["id"].'" size="10" readonly></td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table04.php" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
		echo "You are logged in as user:" . $_SESSION["user"].
		 " location: " . $_SESSION["location"] . "<br>";
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table04.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Compound Bow Form</h2></td></tr>
							<tr><td>Type: </td><td><input type="edit" name="type" value="'. $row[1] .'" size="20"></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="20"></td></tr>
							<tr><td>Condition: </td><td><input type="edit" name="cond" value="' . $row[3] . '" size="30"></td></tr>
							<tr><td>Draw Weight: </td><td><input type="edit" name="drweight" value="' . $row[4] . '" size="20"></td></tr>
							<tr><td>Draw Length: </td><td><input type="edit" name="drlength" value="' . $row[5] . '" size="30"></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[6] . '" size="20"></td></tr>
							<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[7] . '</textarea></td></tr>';
			echo '<tr><td>Location ID: </td><td>';
            
			echo   "<select class='form-control' name = 'loc_id' id='location'>";
	
				// Init statement
				$stmt = $mysqli->stmt_init();
				
				// Set Select query
				$sql = "SELECT * FROM locations";
				
				// Init location variable
				$dbId = "";
				$location = "";
				
				// If the statement was prepared
				if($stmt = $mysqli->prepare($sql))
				{
					// Execute statement
					if($stmt->execute())
					{
						// Bind query result
						$stmt->bind_result($dbId, $location);
						
						// Fetch the statement
						while ($stmt->fetch())
						{
							// Output the locations
							echo "<option value='" . $dbId ."'>" . $location . "</option>";
						}
					}
				}				

				echo "</select>";
			
					echo
							'</td></tr>
							<tr><td>User ID: </td><td><input type="edit" name="user_id" value="' . $row[9] . '" size="20" readonly></td></tr>
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
    global $type, $brand, $cond, $drweight, $drlength, $price, $descript, $loc_id, $user_id, $usertable;

    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (type,brand,cond,drweight,drlength,price,descript,loc_id,user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssssii', $type, $brand, $cond, $drweight, $drlength, $price, $descript, $loc_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
		//echo'<script>window.location.reload();</script>';
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $type, $brand, $cond, $drweight, $drlength, $price, $descript, $loc_id, $user_id, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET type=?, brand=?, cond=?, drweight=?, drlength=?, price=?, descript=?, loc_id=?, user_id=? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('sssssssiii', $type, $brand, $cond, $drweight, $drlength, $price, $descript, $loc_id, $user_id, $index);
            $stmt->execute();
            $stmt->close();
    }
}

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
		</script></head><body>
		<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="//www.cis355.com/student14/landing.php"><img src="http://cis355.com/student14/LWIP_logo.png" style="margin-top: 5px;"></a>';

	if ($_SESSION["user"] != '')
	{
		$user = $_SESSION['user'];
		echo '<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p>';
	}
	else
	{
		echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="http://cis355.com/student14/login.php">
			  <input type="text" size="9" name="username" class="form-control" placeholder="Username">
			  <input type="password" size="9" name="password" class="form-control" placeholder="Password">
			  <button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
			  </form>';
	}

	echo '<br>
		  <br>
	      </div>';
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
				<form name="basic" method="POST" action="table04.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Compound Bow Form</h2></td></tr>
				<tr><td>Type: </td><td>'. $row[1] .'</td></tr>
				<tr><td>Brand: </td><td>' . $row[2] . '</td></tr>
				<tr><td>Condition: </td><td>' . $row[3] . '</td></tr>
				<tr><td>Draw Weight: </td><td>' . $row[4] . '</td></tr>
				<tr><td>Draw Length: </td><td>' . $row[5] . '</td></tr>
				<tr><td>Price: </td><td>' . $row[6] . '</td></tr>
				<tr><td>Description: </td><td>' . $row[7] . '</td></tr>
				<tr><td>Location: </td><td>' . $row[8] . '</td></tr>
				<tr><td>User ID: </td><td>' . $row[9] . '</td></tr>
				</table>
                <a href="table04.php" class="btn btn-primary">Display Database</a>
				</form></div>';
		$result->close();
	}
}
?>