<?php
# --------------------------------------------------------------------------- #
# program: table01.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          instructions: change all "table01.php" to your php filename, and 
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
// session start happens first!!
ini_set("session.cookie_domain", ".cis355.com");
session_start();

// ---------- a. display (echo) html head and link to bootstrap css -----------
// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// ---------- b. set connection variables and verify connection ---------------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table18";

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
	$viewSelected       = 8;
	
    $type 				= $_POST['type']; // if does not exist then value is ""
	$brand 				= $_POST['brand'];
	$model 				= $_POST['model'];
	$color 				= $_POST['color'];
	$price 				= $_POST['price'];
	$descript 			= $_POST['descript'];
	$loc_id             = $_POST['loc_id'];
	$user_id        	= $_POST['user_id'];
	
    // ---------- e. determine what user clicked ------------------------------
	// the $_POST['buttonName'] is the name of the button clicked in browser
	$userSelection = $firstCall; // assumes first call unless button was clicked
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	if( isset( $_POST['viewSelected'] ) ) $userSelection = $viewSelected;
	
	// ---------- f. call function based on what user clicked -----------------
	switch( $userSelection ):
	    case $firstCall: 
		    //$_SESSION["login_user"] = 2;
            //$_SESSION["login_location"] = 1;
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
	    $sql = "CREATE TABLE table18 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "type VARCHAR(20),";
	    $sql .= "brand VARCHAR(30),";
	    $sql .= "model VARCHAR(20),";
	    $sql .= "price VARCHAR(30),";
	    $sql .= "descript VARCHAR(100),";
		$sql .= "loc_id INT,";		
		$sql .= "user_id INT,";
		$sql .= "FOREIGN KEY (user_id) REFERENCES users(user_id),";
		$sql .= "FOREIGN KEY (loc_id) REFERENCES locations(location_id)";
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
	
	// display current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"].
	    " location: ".$_SESSION["location"]."<br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table18.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="10" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Computer Component Database (table18)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Type</td><td>Brand</td>
			<td>Model</td><td>Price</td><td>Description</td><td>Location</td><td>User ID</td></tr>';

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
	if($_SESSION['user'] != "")
	{
	echo    '</table>
			<input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">
			<input type="submit" name="insertSelected" value="Add an Entry" 
			class="btn btn-primary"">
			</form></div>';
	}
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
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				'</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7];
				
			echo '</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');"/></td>';
				
		    if ($_SESSION["id"]==$row[7]) {
			echo '</td><td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
			    <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
				}
			else
				{
			echo '<td></td></tr>';
				}
		}
	}
	$result->close();
}

# ---------- showInsertForm ---------------------------------------------------
function showInsertForm ($mysqli)
{
	// display current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"].
	    " location: ".$_SESSION["location"]."<br>";
		
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table18.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Computer Component Form</h2></td></tr>
			<tr><td>Type: </td><td><input type="edit" name="type" value="" size="20"></td></tr>
			<tr><td>Brand: </td><td><input type="edit" name="brand" value="" size="20"></td></tr>
			<tr><td>Model: </td><td><input type="edit" name="model" value="" size="30"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" size="20"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>';
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

				$mysqli->close();
                echo "</select>";

			echo '</td></tr>
			<tr><td>User ID: </td><td><input type="text" name="user_id" value="'.$_SESSION["id"].'" size="5" readonly></td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table18.php" class="btn btn-primary">Display Database</a></form></div>';
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
		    // display current user and location_id
	        echo "You are logged in as user: ".$_SESSION["user"].
	              " location: ".$_SESSION["location"]."<br>";
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table18.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Computer Component Form</h2></td></tr>
							<tr><td>Type: </td><td><input type="edit" name="type" value="'. $row[1] .'" size="20"></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="20"></td></tr>
							<tr><td>Model: </td><td><input type="edit" name="model" value="' . $row[3] . '" size="30"></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[4] . '" size="20"></td></tr>
							<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[5] . '</textarea></td></tr>';
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
				echo	'</td></tr>
						 <tr><td>User_ID: </td><td><input type="edit" name="user_id" value="' . $row[7] . '" size="20" readonly></td></tr>
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
    global $type, $brand, $model, $price, $descript, $loc_id, $user_id;
	global $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (type,brand,model,price,descript,loc_id,user_id) VALUES (?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('sssssii', $type, $brand, $model, $price, $descript, $loc_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

#echo "You are logged in as user: ".$_SESSION["user"].
	    #" location: ".$_SESSION["location"]."<br>";

# ---------- updateRecord -----------------------------------------------------
function updateRecord($mysqli)
{
	global $type, $brand, $model, $price, $descript, $loc_id,$user_id; 
	global $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET type = ?, brand = ?, model=?, price=?, descript=?, loc_id=?, user_id=? WHERE id = ?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('sssssiii', $type, $brand, $model, $price, $descript, $loc_id, $user_id, $index);
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
	<title>table18.php</title>
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
				<form name="basic" method="POST" action="table18.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Computer Components Form</h2></td></tr>
				<tr><td>Type: </td><td>'. $row[1] .'</td></tr>
				<tr><td>Brand: </td><td>' . $row[2] . '</td></tr>
				<tr><td>Model: </td><td>' . $row[3] . '</td></tr>
				<tr><td>Price: </td><td>' . $row[4] . '</td></tr>
				<tr><td>Description: </td><td>' . $row[5] . '</td></tr>
				<tr><td>Location ID: </td><td>' . $row[6] . '</td></tr>
				<tr><td>User ID: </td><td>' . $row[7] . '</td></tr>
				</table>
                <a href="table18.php" class="btn btn-primary">Display Database</a>
				</form></div>';
		$result->close();
	}
}
?>

<a class="btn" href="http://www.cis355.com/student18/bio.php">Bio</a>