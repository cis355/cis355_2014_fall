<?php
# --------------------------------------------------------------------------- #
# program: GarageSale.php
# author:  Erik Andersson
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
	// session start happens before first!!
		session_start();
		//$_SESSION["login_user"] = 2;
		//$_SESSION["user"] = 2;
		//$_SESSION["login_location"] = 1;


// a. ---------- display (echo) html head and link to bootstrap css ----------
//echo '<!DOCTYPE html><html> 
//	<head>
//	<title>GarageSale.php</title>
//	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
//	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
//	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
//    </head><body>';


// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="GarageSale";

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
	$viewCompleted		= 8; // after user clicked viewSubmit button on form
	$viewSelected		= 9; // after user clicked v
	
    $user_id 				= $_POST['user_id']; // if does not exist then value is ""
	$loc_id 				= $_POST['loc_id'];
	$start_date 			= $_POST['start_date'];
	$end_date 				= $_POST['end_date'];
	//$console_name 			= $_POST['console_name'];
	//$game_description 		= $_POST['game_description'];
	//$quality 				= $_POST['quality'];
	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	if( isset( $_POST['viewCompleted'] ) ) $userSelection = $viewCompleted;
	if( isset( $_POST['viewSelected'] ) ) $userSelection = $viewSelected;
	// the code above assumes this is the first time program is called 
	// unless one of the buttons named above was clicked
	
	// f. ---------- call function based on what user clicked ----------
	switch( $userSelection ):
	    case $firstCall:
		//$_SESSION["login_user"] = 2;
		//$_SESSION["login_location"] = 1;
		//print_r($_SESSION
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
		case $deleteSelected:        // currently no confirmation form is displayed on delete
			// showDeleteForm($mysqli);
			deleteRecord($mysqli);   // selecting delete immediately deetes the record
			displayHTMLHead();
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
		case $insertCompleted: 
		    insertRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']);
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
		case $deleteCompleted:        // this case never occurs (possible upgrade later?)
		displayHTMLHead();
			$msg = 'record deleted';  
			showList($mysqli, $msg);
			break;
		case $viewCompleted:
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
	    $sql = "CREATE TABLE GarageSale (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "user_id INT,";
	    $sql .= "loc_id INT,";
	    $sql .= "start_date VARCHAR(40),";
	    $sql .= "end_date VARCHAR(40),";
	   // $sql .= "console_name VARCHAR(15),";
	   // $sql .= "game_description VARCHAR(150),";
	   // $sql .= "quality VARCHAR(15),";
		$sql .= "FOREIGN KEY (user_id) REFERENCES ENA_users (user_id),";
		$sql .= "FOREIGN KEY (loc_id) REFERENCES ENA_locations (location_id),";
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
	//echo "You are logged in as user: ".$_SESSION["login_user"].
	if ($_SESSION["id"]!="")
	echo "You are logged in as user: ".$_SESSION["user"].$_SESSION["id"].
		 " location: ".$_SESSION["location"]."<br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="GarageSale.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="8" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Garage Sale Database</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>user_id</td><td>loc_id</td>
			<td>start_date:</td><td>end_date:</td>';
			// <td>console_name:</td><td>game_description</td><td>quality</td><td></td></tr>';
			

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
			<input type="hidden" id="uid" name="uid" value="">';
			
			if ($_SESSION["id"]!="")
			echo
		'<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary"></form></div>';
		else
		    echo "<h2><b>Log in to add an entry!</b></h2>";

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
 echo'<a href="http://www.cis355.com/student02/GarageSaleBio.txt">Author Bio and Program Notes</a>'; 
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
				 '</td><td>' . $row[4];
				 // . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7]
				 
				echo '</td><td><input style="margin-left: 10px;" type="submit"
					name="viewSelected" class="btn btn-success" value="View"
					onclick="setUid(' . $row[0] . ');" />';
				 
				 if ($_SESSION["id"]==$row[1])
				  echo
				 '<input style="margin-left: 15px;" input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
				 <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
	//echo "You are logged in as user: ".$_SESSION["login_user"].
	echo "You are logged in as user: ".$_SESSION["user"].$_SESSION["id"].
		 " location: ".$_SESSION["location"]."<br>";
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="GarageSale.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Garage Sale Form</h2></td></tr>
			<tr><td>user_id: </td><td><input type="edit" name="user_id" value="" size="11"></td></tr>
			<tr><td>loc_id: </td><td>';
			
			/* <input type="edit" name="loc_id" value="" size="11"></td></tr>'; */
			
            echo "<select class='form-control' name = 'loc_id' id='location'>";

				if($sql_statement = $mysqli->query("SELECT * FROM ENA_locations")){
        
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
				
				echo '</td></tr>
			<tr><td>start_date: </td><td>
			<form action="action_page.php">
			<input type="datetime-local" name="start_date">
			
			</td></tr>
			<tr><td>end_date: </td><td>
			<form action="action_page.php">
			<input type="datetime-local" name="end_date">
			</form>
			</td></tr>';
			//<tr><td>end_date: </td><td><input type="edit" name="end_date" value="" size="40"></td></tr>';
			//<tr><td>console_name: </td><td><input type="edit" name="console_name" value="" size="15"></td></tr>
			//<tr><td>game_description: </td><td><textarea style="resize: none;" name="game_description" cols="40" rows="3"></textarea></td></tr>
			//<tr><td>quality: </td><td><input type="edit" name="quality" value="" size="15"></td></tr>
         echo'
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="GarageSale.php" class="btn btn-primary">Display Database</a></form></div>';
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
					<form name="basic" method="POST" action="GarageSale.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Garage Sale Form</h2></td></tr>
							<tr><td>user_id: </td><td><input type="edit" name="user_id" value="'. $row[1] .'" size="11"></td></tr>
							<tr><td>loc_id: </td><td>';

            echo "<select class='form-control' name = 'loc_id' id='location'>";
				if($sql_statement = $mysqli->query("SELECT * FROM ENA_locations")){
                  while($loc_row = $sql_statement->fetch_object()){
                    if($loc_row->location_id === $row[2]){
                      echo"<option value='".$loc_row->location_id. 
					  "' selected='selected'>".$loc_row->name. "</option>";
                    }
                    else{
                      echo "<option value='".$loc_row->location_id. 
					  "' >".$loc_row->name. "</option>";
                    }
                  }
				  $sql_statement->close();
				  }
				 else
				    echo $mysqli->error;
                echo "</select>";
							
				echo '</td></tr>		
							<tr><td>start_date: </td><td><input type="edit" name="start_date" value="' . $row[3] . '" size="40"></td></tr>
							<tr><td>end_date: </td><td><input type="edit" name="end_date" value="' . $row[4] . '" size="40"></td></tr>';
							//<tr><td>console_name: </td><td><input type="edit" name="console_name" value="' . $row[5] . '" size="30"></td></tr>
							//<tr><td>game_desciption: </td><td><textarea style="resize: none;" name="game_description" cols="40" rows="3">' . $row[7] . '</textarea></td></tr>
							//<tr><td>quality: </td><td><input type="edit" name="quality" value="' . $row[6] . '" size="15"></td></tr>
				echo'			<tr><td><input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry"></td>
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
					<form name="basic" method="POST" action="GarageSale.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Garage Sale Form/h2></td></tr>
							<tr><td>user_id: </td><td><input type="edit" name="user_id" value="'. $row[1] .'" size="11" readonly></td></tr>
							<tr><td>loc_id: </td><td><input type="edit" name="loc_id" value="' . $row[2] . '" size="11" readonly></td></tr>
							<tr><td>start_date: </td><td><input type="edit" name="start_date" value="' . $row[3] . '" size="40" readonly></td></tr>
							<tr><td>end_date: </td><td><input type="edit" name="end_date" value="' . $row[4] . '" size="40" readonly></td></tr>';
							//<tr><td>console_name: </td><td><input type="edit" name="console_name" value="' . $row[5] . '" size="30" readonly></td></tr>
							//<tr><td>game_desciption: </td><td><textarea style="resize: none;" name="game_description" cols="40" rows="3" readonly>' . $row[7] . '</textarea></td></tr>
							//<tr><td>quality: </td><td><input type="edit" name="quality" value="' . $row[6] . '" size="15" readonly></td></tr>
				echo'		<tr><td><input type="submit" name="viewCompleted" class="btn btn-primary" value="Back to Table"></td></tr>
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
    global $user_id, $loc_id, $start_date, $end_date, $console_name, $game_description, $quality, $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (user_id,loc_id,start_date,end_date) VALUES (?, ?, ?, ?)"))
	//,console_name,game_description,quality , ?, ?, ?
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('iiss', $user_id, $loc_id, $start_date, $end_date);
		//, $console_name, $game_description, $quality
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $user_id, $loc_id, $start_date, $end_date, $usertable;
	//, $console_name, $game_description, $quality
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET user_id = ?, loc_id = ?, start_date = ?, end_date = ? WHERE id = ?"))
	//, console_name = ?, game_description = ?, quality = ? 
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('iissi', $user_id, $loc_id, $start_date, $end_date,$index);
			// $console_name, $game_description, $quality, 
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
	<title>GarageSale.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
	
echo '<div class="col-md-12" style="background-color: tan; border-bottom: 
    2px solid black; box-shadow: 3px 3px 5px #888888;">
	<p><h1><b>Garage Sale</b></h1></p>';
	
	if ($_SESSION["user"] != '')
{
	$user = $_SESSION['user'];
	echo '<p style="font-size:18px; float: right; margin-top: 40px; 
	    margin-right: 20px;">Welcome <b>' .	$user . '</b>!
	<form action="logout.php">
	  <button type="submit" name="logoutSubmit" class="btn btn-danger" action="logout.php">Logout</button>
    </p></form>';

}
else
{
	echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" 
	    action="../student02/login.php">
		<input type="text" size="9" name="username" class="form-control" placeholder="Username">
		<input type="password" size="9" name="password" class="form-control" placeholder="Password">
		<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
	    </form>';
			echo '<p style="font-size:18px; float: right; margin-top: 40px; 
	    margin-right: 20px;">You can login using<b> default </b>as the username and password</p>';
					
}
echo '<br><br></div>';
}
?>