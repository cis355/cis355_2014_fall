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

//what really comes first
ini_set("session.cookie_domain", ".cis355.com");

// session start happens first!!!
session_start();

		    ini_set('display_errors', 1);
			error_reporting(e_all);

// ---------- a. display (echo) html head and link to bootstrap css -----------
// moved to section "f" to solve Post/Redirect/Get problem
echo '<!DOCTYPE html><html> 
	 <head>
	 <title>table17.php</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
     </head><body>';

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
	$viewSelected       = 8; // after user clicked viewSubmit button on form
	
	$manufacturer	= $_POST['manufacturer'];
	$year			= $_POST['year'];
	$color			= $_POST['color'];
	$amountPaid		= $_POST['amountPaid'];
	$location_id	= $_POST['location_id'];
	$user_id		= $_POST['user_id'];
	
		
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
		case $deleteSelected:        // currently no form is displayed
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
		    //deleteRecord($mysqli);
			//header("Location: " . $_SERVER['REQUEST_URI']);
			//displayHTMLHead();
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
	    $sql .= "manufacturer VARCHAR(20),";
	    $sql .= "year VARCHAR(5),";
	    $sql .= "color VARCHAR(10),";
	    $sql .= "amountPaid VARCHAR(10),";
		$sql .= "location_id INT,";
		$sql .= "user_id INT,";
		$sql .= "FOREIGN KEY (location_id) REFERENCES locations (location_id),";
		$sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
		else
		{
			echo $mysqli->errno." ".$mysqli->error;
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
			<form action="table17.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="20" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Deep Freezer Database (Table 17)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;">
			<td>Manufacturer</td>
			<td>Year</td>
			<td>Color</td>
			<td>Amount Paid</td>';
		
	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable"); // get count of records in mysql table
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, else display "no records" message
	if( $countvalue > 0 )
	{
			populateTable($mysqli); // populate html table, from data in mysql table
			echo   '</table><input type="hidden" id="hid" name="hid" value="">
				    <input type="hidden" id="uid" name="uid" value="">';
	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}
	
	echo'<div><div style="float:right;margin: 40px; font-size:20px; font-family: Times New Roman, Times, serif;" >
		<a href="bios_page_student17.html" style="color:black;">Alexys Suisse Bio</a></div></div></body></html>';

	// display html buttons 
	if($_SESSION['user'] != "")
		 {
		
			 echo   '<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary">';
		 }

	// add JavaScript functions to end of html body section
	// note: "hid" is id of item to be deleted; "uid" is id of item to be updated.
	// see also: populateTable function
	echo "</form></div><script>
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
			echo '<tr><td>' . $row[1] . '</td><td>' . $row[2] . 
			     '</td><td>' . $row[3] . '</td><td>' . $row[4];
				 
			echo  '</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View"
			       onclick="setUid(' . $row[0] . ');"/></td>';
					  
			if($_SESSION["id"]==$row[6]) 
			 {
				echo '<td><input style="margin-left: 10px;" type="submit" name="deleteSelected" class="btn btn-danger"
					  value="Delete" onclick="setHid(' . $row[0] .')" /></td>'; 
					  
				echo  '<td><input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary"
					  value="Update" onclick="setUid(' . $row[0] . ');" /></td></tr>';
			 }
	
	
			else
			{
				echo ' <td></td><td></td></tr>';
			}
		}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
	echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table17.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Deep Freezer Form</h2></td></tr>	 
			<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="" size="20"></td></tr>
			<tr><td>Year</td><td><input type="edit" name="year" value="" size="5"></td></tr>
			<tr><td>Color</td><td><input type="edit" name="color" value="" size="10"></td></tr>
			<tr><td>Amount Paid $</td><td><input type="edit" name="amountPaid" value="" size="10"></td></tr>';
			
			echo   '<tr><td>Location: </td><td>';
			echo   "<select class='form-control' name = 'location_id' id='location'>";
			
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
			
	$mysqli->close();			
	
	echo "</select>";
	
	echo  '</td></tr>	
		   <tr><td>User: </td><td><input type="text" name="user_id" value="'. $_SESSION["id"] .'" size="7" readonly></td></tr>
		   <tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
		   <td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		   </table>
		   <a href="table17.php" class="btn btn-primary">Display Database</a></form></div>';
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
		
			//display current user and location
			echo "You are logged in as user: ".$_SESSION["user"].
			"	location: ".$_SESSION["location"]."<br>";
			
			echo '	<br><div class="col-md-4">
					<form name="basic" method="POST" action="table17.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
							<h2>Deep Freezer Form</h2></td></tr>
							<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="'. $row[1] .'" size="20"></td></tr>
							<tr><td>Year</td><td><input type="edit" name="year" value="'. $row[2] .'" size="5"></td></tr>
							<tr><td>Color</td><td><input type="edit" name="color" value="'. $row[3] .'" size="10"></td></tr>
							<tr><td>Amount Paid $</td><td><input type="edit" name="amountPaid" value="'. $row[4] .'" size="10"></td></tr>';
							
								echo   '<tr><td>Location: </td><td>';
								echo   "<select class='form-control' name = 'location_id' id='location'>";
								
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
							
					echo'	<tr><td>User: </td><td><input type="edit" name="user_id" value="' . $row[6] . '" size="7" readonly></td></tr>
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
 global $manufacturer, $year, $color, $amountPaid, $location_id, $user_id, $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (manufacturer, year, color, amountPaid, location_id, user_id) VALUES (?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('ssssii', $manufacturer, $year, $color, $amountPaid, $location_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
 }

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $manufacturer, $year, $color, $amountPaid, $location_id, $user_id, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET manufacturer = ?, year = ?, color = ?, amountPaid = ?, location_id = ?, user_id = ? WHERE id = ?"))
    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
            $stmt->bind_param('ssssiii', $manufacturer, $year, $color, $amountPaid, $location_id, $user_id, $index);
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
		<title>table17.php</title>
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js">
		</script>
		</head>
		<body>
		<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="//www.cis355.com/student14/landing.php">
		<img src="http://cis355.com/student14/LWIP_logo.png" style="margin-top: 5px;"></a>';

	if ($_SESSION["user"] != '')
	{
		$user = $_SESSION['user'];
		echo '<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">
		      Welcome <b>' . $user . '</b>!</p>';
	}
	else
	{
		echo '<form class="navbar-form navbar-right" style="margin-top: 35px;
		      " method="POST" action="http://cis355.com/student14/login.php">
			  <input type="text" size="9" name="username" class="form-control" placeholder="Username">
			  <input type="password" size="9" name="password" class="form-control" placeholder="Password">
			  <button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
			  </form>';
	}

	echo '<br>
		  <br>
	      </div>';
}
# ---------- viewRecord -----------------------------------------------------
function viewRecords($mysqli)
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;

	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
	    $row = $result->fetch_row();
		 echo '	<br>
				<div class="col-md-4">
				<form name="basic" method="POST" action="table17.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Deep Freezer Form</h2></td></tr>
				<tr><td>Manufacturer: </td><td>'. $row[1] .'</td></tr>
				<tr><td>Year: </td><td>' . $row[2] . '</td></tr>
				<tr><td>Color: </td><td>' . $row[3] . '</td></tr>
				<tr><td>Amount: </td><td>' . $row[4] . '</td></tr>
				<tr><td>Location: </td><td>' . $row[5] . '</td></tr>
				<tr><td>User: </td><td>' . $row[6] . '</td></tr>
				</table>
                <a href="table17.php" class="btn btn-primary">Display Database</a>
				</form></div>';
		$result->close();
	}
}
?>