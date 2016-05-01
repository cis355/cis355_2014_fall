<?php

ini_set("session.cookie_domain", ".cis355.com");
session_start();

ini_set('display_errors', 1);
error_reporting(e_all);

// a. ---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	 <head>
	 <title>Golf_Course_Score.php</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
     </head><body>';

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="LDC_ScoreTable";


$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

if($mysqli)            // if successful connection..
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
	
	$user_id			= $_POST['user_id'];
    $Score_id 			= $_POST['Score_id'];
	$Hole1 				= $_POST['Hole1'];
	$Hole2 				= $_POST['Hole2'];
	$Hole3 				= $_POST['Hole3'];
	$Hole4 				= $_POST['Hole4'];
	$Hole5 				= $_POST['Hole5'];
	$Hole6 				= $_POST['Hole6'];
	$Hole7 				= $_POST['Hole7'];
	$Hole8 				= $_POST['Hole8'];
	$Hole9 				= $_POST['Hole9'];
	$Hole10				= $_POST['Hole10'];
	$Hole11				= $_POST['Hole11'];
	$Hole12				= $_POST['Hole12'];
	$Hole13				= $_POST['Hole13'];
	$Hole14				= $_POST['Hole14'];
	$Hole15				= $_POST['Hole15'];
	$Hole16				= $_POST['Hole16'];
	$Hole17				= $_POST['Hole17'];
	$Hole18				= $_POST['Hole18'];
	$Total				= $_POST['Total'];
	
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
			firstPage($mysqli);
			break;
/*
			case $insertSelected:
			displayHTMLHead();
		    showInsertForm($mysqli);
			break;
		case $updateSelected :
			displayHTMLHead();
		    showUpdateForm($mysqli);
			break;
		case $deleteSelected:        // currently no confirmation form is displayed on delete
			deleteRecord($mysqli);   // selecting delete immediately deetes the record
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
*/
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
	    $sql = "CREATE TABLE LDC_ScoreTable (,";
	    $sql .= "scoreId VARCHAR(30),";
	    $sql .= "Hole1 VARCHAR(5),";
	    $sql .= "Hole2 VARCHAR(5),";
	    $sql .= "Hole3 VARCHAR(5),";
	    $sql .= "Hole4 VARCHAR(5),";
	    $sql .= "Hole5 VARCHAR(5),";
	    $sql .= "Hole6 VARCHAR(5),";
	    $sql .= "Hole7 VARCHAR(5),";
	    $sql .= "Hole8 VARCHAR(5),";
	    $sql .= "Hole9 VARCHAR(5),";
	    $sql .= "Hole10 VARCHAR(5),";
	    $sql .= "Hole11 VARCHAR(5),";
	    $sql .= "Hole12 VARCHAR(5),";
	    $sql .= "Hole13 VARCHAR(5),";
	    $sql .= "Hole14 VARCHAR(5),";
	    $sql .= "Hole15 VARCHAR(5),";
	    $sql .= "Hole16 VARCHAR(5),";
	    $sql .= "Hole17 VARCHAR(5),";
	    $sql .= "Hole18 VARCHAR(5),";
		$sql .= "FOREIGN KEY (scoreIdD) REFERENCES LDC_SetUpTable (scoreId),";
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
#----------- firstPage -------------------------------------------------------
function firstPage($mysqli)
{
echo'<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bare - Start Bootstrap Template</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        padding-top: 70px;
    }
    </style>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="//www.cis355.com/student07/Golf_Course_Score.php" class = "navbar-brand">Golf Course Score</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="login.php">Log In</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
			<img src= "sawmill.jpg" style="margin-top: 5px;">
			</div>
    </div>
</body>
</html>';
}
/*
# ---------- showList --------------------------------------------------------
function showList($mysqli, $msg)
{
    // this function gathers records from a "mysql table" and builds an "html table"
	
	global $usertable;
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table07.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px;
			box-shadow: 2px 2px 10px;">
			<tr><td colspan="11" style="text-align: center; border-radius: 5px;
			color: white; background-color:#333333;">
			<h2 style="color: white;">Video Game Database (table07)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;">
			<td>Name</td><td>Type</td>
			<td>Cond</td><td>AVG_Price</td>
			<td>Console</td></tr>';
			

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
		 
	echo '<br><br><a href = "student07Bio.html">Coder Bio</a><br>';
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
			     '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				 '</td><td>' . $row[5];
				 
			echo  '</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View"
			       onclick="setUid(' . $row[0] . ');"/></td>';
					  
			if($_SESSION["id"]==$row[7]) 
			 {
				echo '<td><input style="margin-left: 10px;" type="submit" name="deleteSelected" class="btn btn-danger"
					  value="Delete" onclick="setHid(' . $row[0] .')" /></td>'; 
					  
				echo  '<td><input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary"
					  value="Update" onclick="setUid(' . $row[0] . ');" /></td></tr>';
			 }
			else
			 {
				echo '<td></td><td></td></tr>';
			 }
		}
	}
	$result->close();
}
# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ($mysqli)
{
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table07.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Video Game Form</h2></td></tr>
			<tr><td>Name: </td><td><input type="edit" name="Name" value="" size="30"></td></tr>
			<tr><td>Type: </td><td><input type="edit" name="Type" value="" size="30"></td></tr>
			<tr><td>Cond: </td><td><input type="edit" name="Cond" value="" size="15"></td></tr>
			<tr><td>AVG_Price: </td><td><input type="edit" name="AVG_Price" value="" size="15"></td></tr>
			<tr><td>Console: </td><td><input type="edit" name="Console" value="" size="30"></td></tr>';
			
	echo   '<tr><td>location_id: </td><td>';
	echo   '<select id="loc" class="form-control" onchange="setLocId();">';
								
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
		
				echo '</select></td></tr>
			<input type="hidden" name="index" value="' . $row[0] . '">
			<input type="hidden" id="hLoc" name = "location_id" value="' . $row[6] . '">
			<script>
			document.getElementById("loc").selectedIndex = ' . $row[6] .' - 1; 
			function setLocId()
			{
			var selectBox = document.getElementById("loc");
			document.getElementById("hLoc").value = selectBox.options[selectBox.selectedIndex].value;
			}
		
			</script>';
	
	echo  '</td></tr>	
		   <tr><td>user_id: </td><td><input type="text" name="user_id" value="'. $_SESSION["id"] .'" size="7" readonly></td></tr>
		   <tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
		   <td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		   </table><a href="table07.php" class="btn btn-primary">Display Database</a></form></div>';
}
# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		$row = $result->fetch_row();
		
		 echo '	<br>
				<div class="col-md-4">
				<form name="basic" method="POST" action="table07.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Video Game Form</h2></td></tr>
				<tr><td>Name: </td><td><input type="edit" name="Name" value="'. $row[1] .'" size="30"></td></tr>
				<tr><td>Type: </td><td><input type="edit" name="Type" value="' . $row[2] . '" size="30"></td></tr>
				<tr><td>Cond: </td><td><input type="edit" name="Cond" value="' . $row[3] . '" size="15"></td></tr>
				<tr><td>AVG_Price: </td><td><input type="edit" name="AVG_Price" value="' . $row[4] . '" size="15"></td></tr>
				<tr><td>Console: </td><td><input type="edit" name="Console" value="' . $row[5] . '" size="30"></td></tr>';
				
				echo   '<tr><td>location_id: </td><td>';
				echo   '<select id="loc" class="form-control" onchange="setLocId();">';
								
	
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
		
		echo '</select></td></tr>
			<input type="hidden" name="index" value="' . $row[0] . '">
			<input type="hidden" id="hLoc" name = "location_id" value="' . $row[6] . '">
			<script>
			document.getElementById("loc").selectedIndex = ' . $row[6] .' - 1; 
			function setLocId()
			{
			var selectBox = document.getElementById("loc");
			document.getElementById("hLoc").value = selectBox.options[selectBox.selectedIndex].value;
			}
		
			</script>';			
							
				echo '<tr><td>user_id: </td><td><input type="edit" name="user_id" value="' . $row[7] . '" size="7" readonly></td></tr>
				<tr><td><input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry"></td>
				<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Data"></td></tr>
				</table>
				<input type="hidden" name="uid" value="' . $row[0] . '">
				</form>
				</div>';
				
				
				
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
    global $Name, $Type, $Cond, $AVG_Price, $Console, $location_id, $user_id, $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (Name,Type,Cond,AVG_Price,Console,location_id, user_id)
                               	 VALUES (?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssii', $Name, $Type, $Cond, $AVG_Price, $Console, $location_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $Name, $Type, $Cond, $AVG_Price, $Console, $location_id, $user_id, $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)

    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET Name = ?, Type = ?, Cond = ?, AVG_Price = ?,
								Console = ?, location_id = ?, user_id = ? WHERE id = ?"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssiii', $Name, $Type, $Cond, $AVG_Price, $Console, $location_id, $user_id, $index);
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
		<title>tableUser.php</title>
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
		3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
		3.2.0/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
		3.2.0/js/bootstrap.min.js">
		</script></head><body>
		<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black;
		box-shadow: 3px 3px 5px #888888;">
		<a href="//www.cis355.com/student14/Golf_Course_Score.php">
		<img src="http://cis355.com/student07/gcsd.png" style="margin-top: 5px;"></a>';

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
				<form name="basic" method="POST" action="table07.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Video Game Form</h2></td></tr>
				<tr><td>Name: </td><td>'. $row[1] .'</td></tr>
				<tr><td>Type: </td><td>' . $row[2] . '</td></tr>
				<tr><td>Cond: </td><td>' . $row[3] . '</td></tr>
				<tr><td>AVG_Price: </td><td>' . $row[4] . '</td></tr>
				<tr><td>Console: </td><td>' . $row[5] . '</td></tr>
				<tr><td>location_id: </td><td>' . $row[6] . '</td></tr>
				<tr><td>user_id: </td><td>' . $row[7] . '</td></tr>
				</table>
                <a href="table07.php" class="btn btn-primary">Display Database</a>
				</form></div>';
		$result->close();
	}
}
*/
?>