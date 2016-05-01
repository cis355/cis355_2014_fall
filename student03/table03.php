<?php
# --------------------------------------------------------------------------- #
# program: table03.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          to update this template, change all instances of "table03.php" to your php filename
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

// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// session start happens first!
	session_start();

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table03";

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
	$viewSelected       = 8; // after user clicked viewSelected button on list
	$viewCompleted  	= 9; // after user clicked viewCompleted button on form
	
    $category 			= $_POST['category']; // if does not exist then value is ""
	$brand 				= $_POST['brand'];
	$quality 			= $_POST['quality'];
	$bodytype 			= $_POST['bodytype'];
	$fretboard 			= $_POST['fretboard'];
	$cutaway 			= $_POST['cutaway'];
	$price 				= $_POST['price'];
	$user_id			= $_POST['user_id'];
	$location_id		= $_POST['location_id'];
	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	if( isset( $_POST['viewSelected'] ) )	$userSelection = $viewSelected;
	if( isset( $_POST['viewCompleted'] ) )	$userSelection = $viewCompleted;
	
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
		case $viewSelected :
			displayHTMLHead();
			showViewForm($mysqli);
			break;
		case $deleteSelected:    
			deleteRecord($mysqli);   // delete is immediate (no confirmation)
			displayHTMLHead();
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted: // updated to do Post/Redirect/Get (PRG)
		    insertRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
			displayHTMLHead();
			showList($mysqli, $msg);
			break;
		case $updateCompleted:
		    updateRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']);
			displayHTMLHead();
			showList($mysqli, $msg);
			break;
		case $viewCompleted:
			header("Location: " . $_SERVER['REQUEST_URI']);
			displayHTMLHead();
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (see above)
		    deleteRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']);
			displayHTMLHead();
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
	    $sql = "CREATE TABLE table03 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "category VARCHAR(50),";
	    $sql .= "brand VARCHAR(50),";
	    $sql .= "quality VARCHAR(50),";
	    $sql .= "bodytype VARCHAR(50),";
	    $sql .= "fretboard VARCHAR(50),";
	    $sql .= "cutaway VARCHAR(50),";
	    $sql .= "price VARCHAR(50),";
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

# ---------- showList --------------------------------------------------------
function showList($mysqli, $msg)
{
    // this function gathers records from a "mysql table" and builds an "html table"
	
	global $usertable;
	
	//show current user and location_id
	if ($_SESSION["user"] != ""){
		echo "You are logged in as user: ".$_SESSION["user"].
		     " location: ".$_SESSION["location"]."<br>";
	}
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table03.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="12" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Guitar Database (table03)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Category</td><td>Brand</td>
			<td>Quality</td><td>Body Type</td><td>Fretboard</td><td>Cutaway</td><td>Price</td><td>User</td><td>Location</td><td></td></tr>';
			

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
				
	if ($_SESSION["user"]== ""){
		echo    '</table><input type="hidden" id="hid" name="hid" value="">
				<input type="hidden" id="uid" name="uid" value="">
				<input type="submit" name="" value="Add an Entry" class="btn btn-primary">
				</form></div>';
		echo    "<h4>You must be logged in to add an entry!</h4>";
	}
	else
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
		 
	echo '<a href="http://www.cis355.com/student03/Info.txt">Visit the Bio!</a>';
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
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . 
				$row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				'</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . 
				$row[7] . '</td><td>' . $row[8] . '</td><td>' . $row[9];
				
			echo '</td><td><input style="margin-left: 10px;" type="submit"
					name="viewSelected" class="btn btn-success" value="View"
					onclick="setUid(' . $row[0] . ');" />'; 
				 
			if ($_SESSION["id"]==$row[8]){
				echo '<input style="margin-left: 10px;" type="submit"
					name="deleteSelected" class="btn btn-danger" value="Delete"
					onclick="setHid(' . $row[0] .');" />'; 
				echo '<input style="margin-left: 10px;" type="submit"
					name="updateSelected" class="btn btn-primary" value="Update"
					onclick="setUid(' . $row[0] .');" />';
				}
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
	echo "You are logged in as user: ".$_SESSION["user"].
		 " location: ".$_SESSION["location"]."<br>";

    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table03.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Guitar Information</h2></td></tr>
			
			<tr><td>Category:</td>
			<td><select name="category">
			<option></option>
			<option>Acoustic</option>
			<option>Electric</option>
			<option>Electric-Acoustic</option>
			</select></td></tr>
			
			<tr><td>Brand:</td>
			<td><select name="brand">
			<option></option>
			<option>Fender</option>
			<option>Ibanez</option>
			<option>Gibson</option>
			<option>Schecter</option>
			<option>Ephiphone</option>
			<option>Dean</option>
			<option>Gretsch</option>
			<option>PRS</option>
			<option>ESP</option>
			<option>Martin</option>
			</select></td></tr>
			
			<tr><td>Quality:</td>
			<td><select name="quality">
			<option></option>
			<option>New</option>
			<option>Used</option>
			<option>Worn</option>
			<option>Blemished</option>
			<option>Vintage</option>
			</select></td></tr>
			
			<tr><td>Body Type:</td>
			<td><select name="bodytype">
			<option></option>
			<option>Solid</option>
			<option>Hollow</option>
			<option>Semi-Hollow:</option>
			</select></td></tr>
			
			<tr><td>Fretboard:</td>
			<td><select name="fretboard">
			<option></option>
			<option>Ebony</option>
			<option>Rosewood</option>
			<option>Maple</option>
			</select></td></tr>
			
			<tr><td>Cutaway:</td>
			<td><select name="cutaway">
			<option></option>
			<option>Single</option>
			<option>Double</option>
			<option>None</option>
			</select></td></tr>
			
			<tr><td>Price: </td><td><input type="edit" name="price" value="$" size="10"></td></tr>
			<tr><td>User: </td><td><input type="edit" name="user_id" value="'.$_SESSION["id"].'" size="10" readonly></td></tr>';
	
            echo '<tr><td>Location ID: </td><td>';
            echo "<select class='form-control' name = 'location_id' id='location'>";

				if($sql_statement = $mysqli->query("SELECT * FROM locations"))
				{
					while($row = $sql_statement->fetch_object())
					{
						if($row->location_id === $location_id)
							echo"<option value='".$row->location_id. "' selected='selected'>".$row->name. "</option>";
						else
							echo "<option value='".$row->location_id. "' >".$row->name. "</option>";
					}
					$sql_statement->close();
				}
				else
					echo $mysqli->error;
            echo "</select>";
			echo '</td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
			</table><a href="table03.php" class="btn btn-primary">Display Database</a></form></div>';
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
					<form name="basic" method="POST" action="table03.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Guitar Information</h2></td></tr>
							<tr><td>Category: </td><td><input type="edit" name="category" value="'. $row[1] .'" size="50"></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="50"></td></tr>
							<tr><td>Quality: </td><td><input type="edit" name="quality" value="' . $row[3] . '" size="50"></td></tr>
							<tr><td>Body Type: </td><td><input type="edit" name="bodytype" value="' . $row[4] . '" size="50"></td></tr>
							<tr><td>Fretboard: </td><td><input type="edit" name="fretboard" value="' . $row[5] . '" size="50"></td></tr>
							<tr><td>Cutaway: </td><td><input type="edit" name="cutaway" value="' . $row[6] . '" size="50"></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[7] . '" size="50"></td></tr>
							<tr><td>User: </td><td><input type="edit" name="user_id" value="' . $row[8] . '" size="50" readonly></td></tr>';
							echo '<tr><td>Location ID: </td><td>';
							echo "<select class='form-control' name = 'location_id' id='location'>";
							if($sql_statement = $mysqli->query("SELECT * FROM locations"))
							{
								while($loc_row = $sql_statement->fetch_object())
								{
									if($loc_row->location_id === $row[8])
										echo"<option value='".$loc_row->location_id. "' selected='selected'>".$loc_row->name. "</option>";
									else
										echo "<option value='".$loc_row->location_id. "' >".$loc_row->name. "</option>";
								}
								$sql_statement->close();
							}
							else
							echo $mysqli->error;
							echo "</select>";		
							echo '</td></tr>
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

# ---------- showViewForm --------------------------------------------------
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
					<form name="basic" method="POST" action="table03.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Guitar Information</h2></td></tr>
							<tr><td>Category: </td><td><input type="edit" name="category" value="'. $row[1] .'" size="50" readonly></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="50" readonly></td></tr>
							<tr><td>Quality: </td><td><input type="edit" name="quality" value="' . $row[3] . '" size="50" readonly></td></tr>
							<tr><td>Body Type: </td><td><input type="edit" name="bodytype" value="' . $row[4] . '" size="50" readonly></td></tr>
							<tr><td>Fretboard: </td><td><input type="edit" name="fretboard" value="' . $row[5] . '" size="50" readonly></td></tr>
							<tr><td>Cutaway: </td><td><input type="edit" name="cutaway" value="' . $row[6] . '" size="50" readonly></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[7] . '" size="50" readonly></td></tr>
							<tr><td>User: </td><td><input type="edit" name="user_id" value="' . $row[8] . '" size="50" readonly></td></tr>
							<tr><td>Location: </td><td><input type="edit" name="location_id" value="' . $row[9] . '" size="50" readonly></td></tr>
							<tr><td><input type="submit" name="viewCompleted" class="btn btn-primary" value="Back"></td></tr>
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
    global $category, $brand, $quality, $bodytype, $fretboard, $cutaway, $price, $user_id, $location_id, $usertable;
	
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (category,brand,quality,bodytype,fretboard,cutaway,price, user_id, location_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssssii', $category, $brand, $quality, $bodytype, $fretboard, $cutaway, $price, $user_id, $location_id);
        $stmt->execute();
        $stmt->close();
    }

}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $category, $brand, $quality, $bodytype, $fretboard, $cutaway, $price, $user_id, $location_id, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET category = ?, brand = ?, quality = ?, bodytype = ?, fretboard = ?, cutaway = ?, price = ?, user_id = ?, location_id = ? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('sssssssiii', $category, $brand, $quality, $bodytype, $fretboard, $cutaway, $price, $user_id, $location_id, $index);
            $stmt->execute();
            $stmt->close();
    }
}

# -------------------------------------------------------------------------------------
function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table03.php</title>
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
?>