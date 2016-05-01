<?php


//ini_set("session.cookie_domain", ".cis355.com");

// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// session start happens first!
	session_start();
//	$_SESSION["login_user"] = 2;
//	$_SESSION["location"] = 1;

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="cabrown3";
$password="tastepass";
$dbname="tastebuds";
$toptable="topsongs";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

if($mysqli)            // if successful connection...
{
    // c. ---------- create table, if necessary ----------
	createTopTable($mysqli); 
	
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
	
    $userid 		= $_POST['userid']; // if does not exist then value is ""
	$s1 			= $_POST['s1'];
	$s2 			= $_POST['s2'];
	$s3 			= $_POST['s3'];
	$s4 			= $_POST['s4'];
	$s5 			= $_POST['s5'];
	$s6 			= $_POST['s6'];
	$s7 			= $_POST['s7'];
	$s8 			= $_POST['s8'];
	$s9 			= $_POST['s9'];
	$s10 			= $_POST['s10'];
	$s11 			= $_POST['s11'];
	$s12 			= $_POST['s12'];
	$s13 			= $_POST['s13'];
	$s14 			= $_POST['s14'];
	$s15 			= $_POST['s15'];
	$s16 			= $_POST['s16'];
	$s17 			= $_POST['s17'];
	$s18 			= $_POST['s18'];
	$s19 			= $_POST['s19'];
	$s20 			= $_POST['s20'];
	$s21 			= $_POST['s21'];
	$s22 			= $_POST['s22'];
	$s23 			= $_POST['s23'];
	$s24 			= $_POST['s24'];
	$s25 			= $_POST['s25'];
	$s26 			= $_POST['s26'];
	$s27 			= $_POST['s27'];
	$s28 			= $_POST['s28'];
	$s29 			= $_POST['s29'];
	$s30 			= $_POST['s30'];
	$s31 			= $_POST['s31'];
	$s32 			= $_POST['s32'];
	$s33 			= $_POST['s33'];
	$s34 			= $_POST['s34'];
	$s35 			= $_POST['s35'];
	$s36 			= $_POST['s36'];
	$s37 			= $_POST['s37'];
	$s38 			= $_POST['s38'];
	$s39 			= $_POST['s39'];
	$s40 			= $_POST['s40'];
	$s41 			= $_POST['s41'];
	$s42 			= $_POST['s42'];
	$s43 			= $_POST['s43'];
	$s44 			= $_POST['s44'];
	$s45 			= $_POST['s45'];
	$s46 			= $_POST['s46'];
	$s47 			= $_POST['s47'];
	$s48 			= $_POST['s48'];
	$s49 			= $_POST['s49'];
	$s50 			= $_POST['s50'];

	
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

# ---------- createTopTable ------------------------------------------------------
function createTopTable($mysqli)
{
    global $topsongs;
    if($result = $mysqli->query("select userid from $topsongs limit 1"))
    {
        $row = $result->fetch_object();
		$id = $row->userid;
        $result->close();
    }
    
    if(!$id)
    {
	    $sql = "CREATE TABLE topsongs (userid INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( userid ),";
	    $sql .= "s1 INT,";
		$sql .= "s2 INT,";
		$sql .= "s3 INT,";
		$sql .= "s4 INT,";
		$sql .= "s5 INT,";
		$sql .= "s6 INT,";
		$sql .= "s7 INT,";
		$sql .= "s8 INT,";
		$sql .= "s9 INT,";
		$sql .= "s10 INT,";
		$sql .= "s11 INT,";
		$sql .= "s12 INT,";
		$sql .= "s13 INT,";
		$sql .= "s14 INT,";
		$sql .= "s15 INT,";
		$sql .= "s16 INT,";
		$sql .= "s17 INT,";
		$sql .= "s18 INT,";
		$sql .= "s19 INT,";
		$sql .= "s20 INT,";
		$sql .= "s21 INT,";
		$sql .= "s22 INT,";
		$sql .= "s23 INT,";
		$sql .= "s24 INT,";
		$sql .= "s25 INT,";
		$sql .= "s26 INT,";
		$sql .= "s27 INT,";
		$sql .= "s28 INT,";
		$sql .= "s29 INT,";
		$sql .= "s30 INT,";
		$sql .= "s31 INT,";
		$sql .= "s32 INT,";
		$sql .= "s33 INT,";
		$sql .= "s34 INT,";
		$sql .= "s35 INT,";
		$sql .= "s36 INT,";
		$sql .= "s37 INT,";
		$sql .= "s38 INT,";
		$sql .= "s39 INT,";
		$sql .= "s40 INT,";
		$sql .= "s41 INT,";
		$sql .= "s42 INT,";
		$sql .= "s43 INT,";
		$sql .= "s44 INT,";
		$sql .= "s45 INT,";
		$sql .= "s46 INT,";
		$sql .= "s47 INT,";
		$sql .= "s48 INT,";
		$sql .= "s49 INT,";
		$sql .= "s50 INT,";

	    $sql .= "FOREIGN KEY (userid) REFERENCES user (userid),";
        $sql .= "FOREIGN KEY (s1) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s2) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s3) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s4) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s5) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s6) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s7) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s8) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s9) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s10) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s11) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s12) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s13) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s14) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s15) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s16) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s17) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s18) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s19) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s20) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s21) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s22) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s23) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s24) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s25) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s26) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s27) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s28) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s29) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s30) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s31) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s32) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s33) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s34) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s35) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s36) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s37) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s38) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s39) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s40) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s41) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s42) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s43) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s44) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s45) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s46) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s47) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s48) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s49) REFERENCES song (songid)";
		$sql .= "FOREIGN KEY (s50) REFERENCES song (songid)";
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
	
	global $topsongs;
	
	//show current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"]."<br>";
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="toptable.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="12" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: orange;">Top Songs</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>Rank</td><td>Song</td><td>Author</td>
			</tr>';
			

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $topsongs"); // get count of records in mysql table
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
	global $topsongs;
		//show current user and location_id
	
	if($result = $mysqli->query("SELECT * FROM $topsongs WHERE $_SESSION["id"] = userid"))
	{
		if($row = $result->fetch_row())
		{
		    while()
			{
			    echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . 
				    $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				    '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . 
				    $row[7] . '</td><td>' . $row[8] . '</td><td>' . $row[9];
				
			    echo '</td><td><input style="margin-left: 10px;" type="submit"
				  	name="viewSelected" class="btn btn-success" value="View"
					onclick="setUid(' . $row[0] . ');" />';
				 
			
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
	echo "You are logged in as user: ".$_SESSION["user"]."<br>";

    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="toptable.php" onSubmit="return validate();">
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
		</table><a href="toptable.php" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $topsongs;
	
	if($result = $mysqli->query("SELECT * FROM $topsongs WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="toptable.php">
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
	global $topsongs;
	
	if($result = $mysqli->query("SELECT * FROM $topsongs WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="toptable.php">
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
	global $topsongs;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("DELETE FROM $topsongs WHERE id=?"))
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
    global $toolkind, $brand, $powerkind, $cond, $hascase, $hue, $price, $user_id, $location_id, $topsongs;
	
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $topsongs (toolkind,brand,powerkind,cond,hascase,hue,price,user_id,location_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
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
	global $toolkind, $brand, $powerkind, $cond, $hascase, $hue, $price, $user_id, $location_id, $topsongs;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $topsongs SET toolkind = ?, brand = ?, powerkind = ?, cond = ?, hascase = ?, hue = ?, price = ? user_id = ?, location_id = ? WHERE id = ?"))
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
	<title>toptable.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
}
?>