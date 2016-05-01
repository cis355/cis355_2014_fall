<?php
# --------------------------------------------------------------------------- #
# program: GenPick.php
# author:  Billy Wright
# course:  cis355 fall 2014
# purpose:
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

// session start happens first!!
//Add session start.
//session_start();

// a. ---------- display (echo) html head and link to bootstrap css ----------
/*
echo '<!DOCTYPE html><html> 
	<head>
	<title>Generators.php</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
    </head><body>';
*/

// ---------- b. set connection variables and verify connection ----------
$hostname="localhost";
$username="student21";
$password="password21";
$dbname="GenPick";
$usertable="Generators";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

if($mysqli)            // if successful connection...
{
    // c. ---------- create table, if necessary ----------

	createTable($mysqli); 
	
	// d. ---------- initialize userSelection variables ----------
	$userSelection 		= 0;
	$firstCall 		= 1; // first time program is called
	$insertSelected 	= 2; // after user clicked insertSelected button on list 
	$updateSelected 	= 3; // after user clicked updateSelected button on list 
	$deleteSelected 	= 4; // after user clicked deleteSelected button on list 
	$insertCompleted 	= 5; // after user clicked insertSubmit button on form
	$updateCompleted 	= 6; // after user clicked updateSubmit button on form
	$deleteCompleted 	= 7; // after user clicked deleteSubmit button on form
	
       	$SupplyType			= $_POST['SupplyType'];
	$SupplyVoltage			= $_POST['SupplyVoltage'];
	$UnitLoad			= $_POST['UnitLoad'];
	$LoadQuantity			= $_POST['LoadQuantity'];
	$TotalLoad			= $_POST['TotalLoad'];
	$PowerFactor			= $_POST['PowerFactor'];
	$StartingMotor			= $_POST['StartingMotor'];
	$NumMotors			= $_POST['NumMotors'];
	$LockRotorCode			= $_POST['LockRotorCode'];
	$StartingDraw			= $_POST['StartingDraw'];
	$SustainedDraw			= $_POST['SustainedDraw'];
	$GenModel			= $_POST['GenModel'];



// Add location_id and user_id
/*
        $location_id   =  $_SESSION['location'];        // = $_POST['location_id'];
	$user_id       =  $_SESSION['id'];        // = $_POST['user_id'];
        $email = $_SESSION['user'];
*/	
    // e. ---------- determine what user clicked ----------
	$userSelection = $firstCall;
	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;
	// the code above assumes this is the first time program is called 
	// unless one of the buttons named above was clicked
	
	// f. ---------- call function based on what user clicked ----------
	switch( $userSelection ):
	    case $firstCall: 
//Add code for user session.

//Not using these -line 79-79 instead.
         //  $_SESSION["login_user"] = 2;        //User 2
          // $_SESSION["login_location"] = 1;    //Location 1
		    $msg = '';
//Add code for htmldisplayhead.
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
		case $deleteSelected:        // currently no confirmation form is displayed on delete
           displayHTMLHead();
			// showDeleteForm($mysqli);
			deleteRecord($mysqli);   // selecting delete immediately deetes the record
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
           header("Location: " . $_SERVER['REQUEST_URI']); // redirect
           displayHTMLHead();
		    updateRecord($mysqli);
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (possible upgrade later?)
           header("Location: " . $_SERVER['REQUEST_URI']); // redirect
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
	    $sql = "CREATE TABLE Generators (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "SupplyType VARCHAR(20),";
	    $sql .= "SupplyVoltage int(4),";
	    $sql .= "UnitLoad int(4),";
	    $sql .= "LoadQuantity int(4),";
	    $sql .= "TotalLoad int(4),";
	    $sql .= "PowerFactor float(4),"; //Float?
            $sql .= "StartingMotor tinyint(1),";
            $sql .= "NumMotors int(4),";
            $sql .= "LockRotorCode VARCHAR(15),";
            $sql .= "StartingDraw  VARCHAR(15),";
            $sql .= "SustainedDraw  VARCHAR(15),";
            $sql .= "GenModel  VARCHAR(20)";


/*
// Add location_id and user_id
            $sql .= "location_id INT NOT NULL,";
	    $sql .= "user_id INT NOT NULL,";
	    $sql .= "FOREIGN KEY (location_id) REFERENCES locations (location_id),";
            $sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id)";

*/

	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
    } // end if (!$id)
} // end createTable

# ---------- showList --------------------------------------------------------
function showList($mysqli, $msg)
{
    // this function gathers records from a "mysql table" and builds an "html table"
	
	global $usertable;

/*
        // display current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"].
	    " location: ".$_SESSION["location"]."<br>";	
*/
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="GenPick.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 4px; box-shadow: 2px 2px 8px;">
			<tr><td colspan="8" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">GenPick (Generator Database)</h2></td></tr>
			<tr style="font-weight:700; font-size:15px;">
			<td>ID #</td>
			<td>SupplyType</td><td>SupplyVoltage</td><td>UnitLoad</td><td>LoadQuantity</td><td>TotalLoad</td><td>PowerFactor</td><td>StartingMotor</td>
 
                        <td>NumMotors</td><td>LockRotorCode</td><td>StartingDraw</td><td>SustainedDraw</td><td>GenModel</td>



                        </tr>'; //This was here before adding the Location and User html above.
			

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
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
			     '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . '</td>
                  <td>' . $row[8] . '</td><td>' . $row[9] .'</td> <td>' . $row[10] .'</td> <td>' . $row[11] .'</td> <td>' . $row[12] .'</td>';
		
//	if($_SESSION['id']== $row[9]){
            echo'
                           <td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
			   <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />
                           <input  style="margin-left: 10px;" type="submit" name="viewSelected"  class="btn btn-success" value="View" onclick=setUid(' . $row[0] . ');" />';
//View button above has not been utilized, not sure if we will use it yet...
 //           }
      	}
	}
	$result->close();
}

# ---------- showInsertForm --------------------------------------------------------
function showInsertForm ()
{

/*
        // display current user and location_id
	echo "You are logged in as user: ".$_SESSION["user"].
	    " location: ".$_SESSION["location"]."<br>";
*/
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="GenPick.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Generator Form</h2></td></tr>
			<tr><td>SupplyType </td><td><input type="edit" name="SupplyType" value="" size="20"></td></tr>
		
                	<tr><td>SupplyVoltage </td><td><input type="edit" name="SupplyVoltage" value="" size="20"></td></tr>

			<tr><td>UnitLoad</td><td><input type="edit" name="UnitLoad" value="" size="20"></td></tr>

			<tr><td>LoadQuantity </td><td><input type="edit" name="LoadQuantity" value="" size="20"></td></tr>

			<tr><td>TotalLoad </td><td><input type="edit" name="TotalLoad" value="" size="20"></td></tr>

                	<tr><td>PowerFactor</td><td><input type="edit" name="PowerFactor" value="" size="20"></td></tr> 
                
                	<tr><td>StartingMotor </td><td><input type="edit" name="StartingMotor" value="" size="20"></td></tr>

                 	<tr><td>NumMotors </td><td><input type="edit" name="NumMotors" value="" size="20"></td></tr>

                 	<tr><td>LockRotorCode </td><td><input type="edit" name="LockRotorCode" value="" size="20"></td></tr>

                	<tr><td>StartingDraw </td><td><input type="edit" name="StartingDraw" value="" size="20"></td></tr>

                 	<tr><td>SustainedDraw </td><td><input type="edit" name="SustainedDraw" value="" size="20"></td></tr>

                 	<tr><td>GenModel </td><td><input type="edit" name="GenModel" value="" size="20"></td></tr>
		

   	<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="GenPick.php" class="btn btn-primary">Display Database</a></form></div>';
}
//These two lines go below the GenModel line. For location and user
// <tr><td  </td><td><textarea style="resize: none;" name=" " cols="40" rows="3"></textarea></td></tr>
//  <tr><td </td><td><textarea style="resize: none;" name=" " cols="40" rows="3"></textarea></td></tr>


# ---------- showUpdateForm --------------------------------------------------
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
                 // display current user and location_id
	         echo "You are logged in as user: ".$_SESSION["user"]. " location: ".$_SESSION["location"]."<br>";

			echo '<br>
		              <div class="col-md-4">
		              <form name="basic" method="POST" action="GenPick.php">
			      <table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
		              <tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>MotorCycle Form</h2></td></tr>
				        <tr><td>SupplyType </td><td><input type="edit" name=">SupplyType" value="' . $row[1] . '" size="20"></td></tr>

					<tr><td>SupplyVoltage </td><td><input type="edit" name="SupplyVoltage" value="' . $row[2] . '" size="30"></td></tr>

					<tr><td>UnitLoad </td><td><input type="edit" name="UnitLoad" value="' . $row[3] . '" size="20"></td></tr>

					<tr><td>LoadQuantity </td><td><input type="edit" name="LoadQuantity" value="' . $row[4] . '" size="30"></td></tr>

					<tr><td>TotalLoad  </td><td><input type="edit" name="TotalLoad" value="' . $row[5] . '" size="20"></td></tr>

                                       	<tr><td>PowerFactor </td><td><input type="edit" name="PowerFactor" value="' . $row[6] . '" size="20"></td></tr>

                                       	<tr><td>StartingMotor </td><td><input type="edit" name="StartingMotor" value="' . $row[7] . '" size="20"></td></tr>

                                 	<tr><td>NumMotors </td><td><input type="edit" name="NumMotors" value="' . $row[8] . '" size="20"></td></tr>

                                 	<tr><td>LockRotorCode </td><td><input type="edit" name="LockRotorCode" value="' . $row[9] . '" size="20"></td></tr>

                                   	<tr><td>StartingDraw </td><td><input type="edit" name="StartingDraw" value="' . $row[10] . '" size="20"></td></tr>

                                	<tr><td>SustainedDraw </td><td><input type="edit" name="SustainedDraw" value="' . $row[11] . '" size="20"></td></tr>

                                	<tr><td>GenModel </td><td><input type="edit" name="GenModel" value="' . $row[12] . '" size="20"></td></tr>

                                       
                                      
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
//These two lines go below the GenModel line. For location and user
 // <tr><td>Location ID: </td><td>
	//			        <textarea style="resize: none;" name="location_id" cols="40" rows="3">' . $row[8] . '</textarea></td></tr>
	//				<tr><td>User ID: </td><td><textarea style="resize: none;" name="user_id" cols="40" rows="3">' . $row[9] . '</textarea></td></tr>



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
// Add location_id and user_id
    global $SupplyType, $SupplyVoltage, $UnitLoad, $LoadQuantity, $TotalLoad, $PowerFactor,  $StartingMotor, $NumMotors, $LockRotorCode, $StartingDraw,
    $SustainedDraw, $GenModel;   //$location_id, $user_id; //, $usertable;

//Add global usertable
global $usertable;
    
    $stmt = $mysqli->stmt_init();

// Add location_id and user_id and ??
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (SupplyType, SupplyVoltage, UnitLoad, LoadQuantity, TotalLoad, PowerFactor, StartingMotor, NumMotors, LockRotorCode,
StartingDraw, SustainedDraw, GenModel) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))

    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
//Add ii and $location_id, $user_id
       
        $stmt->bind_param('ssssssssssss', $SupplyType, $SupplyVoltage, $UnitLoad, $LoadQuantity, $TotalLoad, $PowerFactor, $StartingMotor, $NumMotors, $LockRotorCode,
        $StartingDraw, $SustainedDraw, $GenModel);    //Dont forget Add location_id and user_id
    

             $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord --------------------------------------------------------------
function updateRecord($mysqli)
{
	global $SupplyType, $SupplyVoltage, $UnitLoad, $LoadQuantity, $TotalLoad, $PowerFactor, $StartingMotor, $NumMotors, $LockRotorCode, $StartingDraw, $SustainedDraw,
 $GenModel
// $usertable,
// Add location_id and user_id
//   $location_id, $user_id
;
// Add global usertable.
   global $usertable;

	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the form)
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET SupplyType = ?, SupplyVoltage = ?, UnitLoad = ?, LoadQuantity = ?, TotalLoad = ?, PowerFactor = ?, StartingMotor = ?,
NumMotors = ?, LockRotorCode = ?, StartingDraw = ?, SustainedDraw = ?, GenModel = ?  WHERE id = ?"))

//location_id=?, user_id=?  WHERE id = ?")) // Add location_id and user_id

    {
            // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
			// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
//Add ii and location_id, user_id
            $stmt->bind_param('ssssssssssssi', $SupplyType, $SupplyVoltage, $UnitLoad, $LoadQuantity, $TotalLoad, $PowerFactor, $StartingMotor, $NumMotors,
 $LockRotorCode, $StartingDraw, $SustainedDraw, $GenModel, $index);
//$location_id, $user_id, $index);
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
	<title>GenPick.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
}

?>
