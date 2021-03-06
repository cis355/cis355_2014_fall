<?php
# --------------------------------------------------------------------------- #
# program: table05.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          to update this template, change all instances of "table05.php" to your php filename
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

//ini_set("session.cookie_domain", ".cis355.com");

// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// session start happens first!
	session_start();
//	$_SESSION["login_user"] = 2;
//	$_SESSION["location"] = 1;

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table05";
$loctable="locations";
$usersdbtable="users";

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
	$viewCompleted  	= 9; // after user clicked viewSubmit button on form
	
    $toolkind 		= $_POST['toolkind']; // if does not exist then value is ""
	$brand 			= $_POST['brand'];
	$powerkind 		= $_POST['powerkind'];
	$cond 			= $_POST['cond'];
	$hascase 		= $_POST['hascase'];
	$hue 			= $_POST['hue'];
	$price 			= $_POST['price'];
	$user_id		= $_POST['user_id'];
	$location_id	= $_POST['location_id'];
	
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
		    showInsertForm();
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
		case $viewCompleted:
			header("Location: " . $_SERVER['REQUEST_URI']);
			displayHTMLHead();
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
	    $sql = "CREATE TABLE table05 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "toolkind VARCHAR(50),";
	    $sql .= "brand VARCHAR(50),";
	    $sql .= "powerkind VARCHAR(50),";
	    $sql .= "cond VARCHAR(50),";
	    $sql .= "hascase VARCHAR(50),";
	    $sql .= "hue VARCHAR(20),";
	    $sql .= "price VARCHAR(20),";
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
	if($_SESSION["user"] != "")
	{
	    echo "You are logged in as user: ".$_SESSION["user"]. " location: ".$_SESSION["location"]."<br>";
	}
	else
	{
	    echo "You are not logged in. Head to the home page to create an account or log in.";
	}
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table05.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="12" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: orange;">Power Tools Database (table05)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>toolkind</td><td>Brand</td>
			<td>Power kind</td><td>conditio</td><td>Has Case?</td><td>hue</td><td>Price</td><td>User</td><td>Location</td><td></td></tr>';
			

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
	
	echo '</table><input type="hidden" id="hid" name="hid" value="">
	  		  <input type="hidden" id="uid" name="uid" value="">';
	
	// display html buttons 
	if($_SESSION["user"] != "")
	{
	  // display html buttons 
	  echo    '<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary">
			  </form></div>';
	}
	else
	{
	    echo "<h2><b>Log in to add an entry</b></h2>";
	}

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
		//show current user and location_id
	
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
					onclick="setHid(' . $row[0] .')" />'; 
				echo '<input style="margin-left: 10px;" type="submit"
					name="updateSelected" class="btn btn-primary" value="Update"
					onclick="setUid(' . $row[0] . ');" />';
				}
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ()
{
	if($_SESSION["user"] != "")
	{
	    echo "You are logged in as user: ".$_SESSION["user"].
		 " location: ".$_SESSION["location"]."<br>";
	}
	else
	{
	    echo "You are not logged in. Head to the home page to create an account or log in.<br>";
	}

    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table05.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Power Tools Form</h2></td></tr>
			
			<tr><td>toolkind2</td>
			<td><select name="toolkind">
			<option></option>
			<option>Power Drill</option>
			<option>Hammer Drill</option>
			<option>Circular Saw</option>
			<option>Table Saw</option>
			<option>Impact Driver</option>
			<option>Detail Sander</option>
			<option>Belt Sander</option>
			<option>Oscillating Tool</option>
			<option>Air Compressor</option>
			<option>Angle Grinder</option>
			<option>Nail Gun</option>
			<option>Wet/Dry Vacuum</option>
			<option>Chainsaw</option>
			<option>Pressure Washer</option>
			<option>Reciprocating Saw</option>
			</select></td></tr>
			
			<tr><td>Brand</td>
			<td><select name="brand">
			<option></option>
			<option>Milwaukee</option>
			<option>Makita</option>
			<option>DEWALT</option>
			<option>Bosch</option>
			<option>Ryobi</option>
			<option>RIDGID</option>
			<option>Skil</option>
			<option>JET</option>
			<option>Hilti</option>
			<option>BLACK + DECKER</option>
			<option>Craftsman</option>
			<option>Chicago</option>
			<option>Porter-Cable</option>
			</select></td></tr>
			
			<tr><td>Power kind</td>
			<td><select name="powerkind">
			<option></option>
			<option>Battery</option>
			<option>Standard Plug In</option>
			</select></td></tr>
			
			<tr><td>conditio</td>
			<td><select name="cond">
			<option></option>
			<option>Brand New</option>
			<option>Used</option>
			<option>Like New</option>
			</select></td></tr>
			
			<tr><td>Case Included?</td>
			<td><select name="hascase">
			<option></option>
			<option>Yes</option>
			<option>No</option>
			</select></td></tr>
			
			<tr><td>hue: </td><td><input type="edit" name="hue" value="" size="20"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="$" size="20"></td></tr>
			<tr><td>User: </td><td><input type="edit" name="user_id" value="" size="20"></td></tr>
			<tr><td>Location: </td><td><input type="edit" name="location_id" value="" size="20"></td></tr>
			
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table05.php" class="btn btn-primary">Display Database</a></form></div>';
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
					<form name="basic" method="POST" action="table05.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Power Tools Form</h2></td></tr>
							<tr><td>Tool kind: </td><td><input type="edit" name="toolkind" value="'. $row[1] .'" size="50"></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="50"></td></tr>
							<tr><td>Power kind: </td><td><input type="edit" name="powerkind" value="' . $row[3] . '" size="50"></td></tr>
							<tr><td>conditio: </td><td><input type="edit" name="cond" value="' . $row[4] . '" size="50"></td></tr>
							<tr><td>Case Included: </td><td><input type="edit" name="hascase" value="' . $row[5] . '" size="50"></td></tr>
							<tr><td>hue: </td><td><input type="edit" name="hue" value="' . $row[6] . '" size="20"></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[7] . '" size="20"></td></tr>
							<tr><td>User: </td><td><input type="edit" name="user_id" value="' . $row[8] . '" size="20"></td></tr>
							<tr><td>Location: </td><td><input type="edit" name="location_id" value="' . $row[9] . '" size="20"></td></tr>
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
					<form name="basic" method="POST" action="table05.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Power Tools Form</h2></td></tr>
							<tr><td>Tool kind: </td><td><input type="edit" name="toolkind" value="'. $row[1] .'" size="50" readonly></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="50" readonly></td></tr>
							<tr><td>Power kind: </td><td><input type="edit" name="powerkind" value="' . $row[3] . '" size="50" readonly></td></tr>
							<tr><td>conditio: </td><td><input type="edit" name="cond" value="' . $row[4] . '" size="50" readonly></td></tr>
							<tr><td>Case Included: </td><td><input type="edit" name="hascase" value="' . $row[5] . '" size="50" readonly></td></tr>
							<tr><td>hue: </td><td><input type="edit" name="hue" value="' . $row[6] . '" size="20" readonly></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[7] . '" size="20" readonly></td></tr>
							<tr><td>User: </td><td><input type="edit" name="user_id" value="' . $row[8] . '" size="20" readonly></td></tr>
							<tr><td>Location: </td><td><input type="edit" name="location_id" value="' . $row[9] . '" size="20" readonly></td></tr>
							<tr><td><input type="submit" name="viewCompleted" class="btn btn-primary" value="Back"></td>
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
    global $toolkind, $brand, $powerkind, $cond, $hascase, $hue, $price, $user_id, $location_id, $usertable;
	
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (toolkind,brand,powerkind,cond,hascase,hue,price,user_id,location_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssssii', $toolkind, $brand, $powerkind, $cond, $hascase, $hue, $price, $user_id, $location_id);
        $stmt->execute();
        $stmt->close();
    }

}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $toolkind, $brand, $powerkind, $cond, $hascase, $hue, $price, $user_id, $location_id, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET toolkind = ?, brand = ?, powerkind = ?, cond = ?, hascase = ?, hue = ?, price = ? user_id = ?, location_id = ? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('sssssssii', $toolkind, $brand, $powerkind, $cond, $hascase, $hue, $price, $user_id, $location_id, $index);
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
	<title>table05.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script>
	</head><body>
	
	<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="//www.cis355.com/student14/landing.php"><img src="http://cis355.com/student14/LWIP_logo.png" style="margin-top: 5px;"></a>
		<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="http://cis355.com/student14/login.php">
			  <input type="text" size="9" name="username" class="form-control" placeholder="Username">
			  <input type="password" size="9" name="password" class="form-control" placeholder="Password">
			  <button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
		</form><br>
		  <br>
	</div>
	<br>
<a href="http://cis355.com/student05/file_download.php">Download</a><br>
<br>
<a href="http://cis355.com/student05/file_upload.php">Download</a><br>
	';
}
?>