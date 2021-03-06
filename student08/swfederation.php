<?php

# --------------------------------------------------------------------------- #

# program: swfederation.php

# author:  Michael Coppolino

# course:  cis355 fall 2014

# purpose: Individual Project for CIS355

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

ini_set("session.cookie_domain", ".cis355.com");

session_start();



            ini_set('display_errors', 1);

			error_reporting(e_all);



// ---------- a. display (echo) html head and link to bootstrap css -----------

// moved to section "f" to solve Post/Redirect/Get problem

// displayHTMLHead();



// ---------- b. set connection variables and verify connection ---------------

$hostname="localhost";

$username="student";

$password="learn";

$dbname="lesson01";

$usertable="wrestlers";


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

	

  	$name 				= $_POST['name']; // if does not exist then value is ""

	$height 				= $_POST['height'];

	$weight 				= $_POST['weight'];

	$hometown 				= $_POST['hometown'];

	$finisher 			= $_POST['finisher'];

	$description 			= $_POST['description'];

	$user_id        	= $_POST['user_id']; //foriegn key, connects with swfederation table


    // ---------- e. determine what user clicked ------------------------------

	// the $_POST['buttonName'] is the name of the button clicked in browser

	$userSelection = $firstCall; // assumes first call unless button was clicked

	if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;

	if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;

	if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;

	if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;

	if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;

	if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;

	

	// ---------- f. call function based on what user clicked -----------------

	switch( $userSelection ):

	    case $firstCall: 

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

	    $sql = "CREATE TABLE wrestlers

		       (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),"; //each new entry will increase id by 1, if not specified

	    $sql .= "name VARCHAR(25),"; //varchar means text

	    $sql .= "height INT,";

	    $sql .= "weight INT,";

	    $sql .= "hometown VARCHAR(25),";

	    $sql .= "finisher VARCHAR(25),";

	    $sql .= "description VARCHAR(255),";

		$sql .= "user_id INT,";


	        $sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id)"; // connects with users table, to allow entries.




	    $sql .= ")";



        if($stmt = $mysqli->prepare($sql))

        {

            $stmt->execute(); //executes the statement

        }

    }

}



# ---------- showList ---------------------------------------------------------

// this function gets records from a "mysql table" and builds an "html table"

function showList($mysqli, $msg) 

{

	global $usertable;

	

	// display current user and location_id

	echo "You are logged in as user: ".$_SESSION["user"]." (".$_SESSION["id"].") ".

	    " location: ".$_SESSION["location"]."<br>";

	

    // display html table column headings

	echo 	'<div class="col-md-12">

			<form action="swfederation.php" method="POST">

			<table class="table table-condensed" 

			style="border: 1px solid #dddddd; border-radius: 5px; 

			box-shadow: 2px 2px 10px;">

			<tr><td colspan="11" style="text-align: center; border-radius: 5px; 

			color: white; background-color:#333333;">

			<h2 style="color: white;">The Roster List</h2>

			</td></tr><tr style="font-weight:800; font-size:20px;">

			<td>ID</td><td>Name</td><td>height</td>

			<td>Weight</td><td>Hometown</td><td>Finisher</td>

			<td>Description</td>						

			<td>User</td><td></td><td></td></tr>';



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

	echo    '</table>

			<input type="hidden" id="hid" name="hid" value="">

			<input type="hidden" id="uid" name="uid" value="">

			<input type="submit" name="insertSelected" value="Add an Entry" 

			class="btn btn-primary"">

			</form></div>';



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

			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . 

			    $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 

				'</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . 

				$row[7] . '</td><td>' . $row[8] ;

			

            echo '</td><td><input type="submit" 



				name="viewSelected" class="btn btn-success" value="View" 



				onclick="setUid(' . $row[0] . ');" />';

			



		



			echo '<input style="margin-left: 0px;" name="deleteSelected" type="submit" 



				class="btn btn-danger" value="Delete" onclick="setHid(' . 



				$row[0] .')" />' ;



			echo '<input style="margin-left: 0px;" type="submit" 



				name="updateSelected" class="btn btn-primary" value="Update" 



				onclick="setUid(' . $row[0] . ');" />';

			

		}

	}

	$result->close();

}



# ---------- showInsertForm ---------------------------------------------------

function showInsertForm ($mysqli)

{



	// display current user and location_id

	echo "You are logged in as user: ".$_SESSION["user"].

	    "<br>";

		

    echo '<div class="col-md-4">

	<form name="basic" method="POST" action="swfederation.php" 

	    onSubmit="return validate();">

		<table class="table table-condensed" style="border: 1px solid #dddddd; 

		    border-radius: 5px; box-shadow: 2px 2px 10px;">

			<tr><td colspan="2" style="text-align: center; border-radius: 5px; 

			    color: white; background-color:#333333;">

			<h2>Submit your Information!</h2></td></tr>

			<tr><td>Name: </td><td><input type="edit" name="name" value="" 

			size="20"></td></tr>

			<tr><td>Height: (Use X.Y Format!) </td><td><input type="edit" name="height" value="" 

			size="20"></td></tr>

			<tr><td>Weight: </td><td><input type="edit" name="weight" value="" 

			size="30"></td></tr>

			<tr><td>Hometown: </td><td><input type="edit" name="hometown" value="" 

			size="20"></td></tr>

			<tr><td>Finisher: </td><td><input type="edit" name="finisher" 

			value="" size="30"></td></tr>

			<tr><td>Description: </td><td><textarea style="resize: none;" 

			name="description" cols="40" rows="3"></textarea></td></tr>';
		

			echo '<tr><td>User ID: </td><td><textarea style="resize: none;" 

			name="user_id" cols="40" rows="3"></textarea></td></tr>';
	


			echo '<tr><td><input type="submit" name="insertCompleted" 

			    class="btn btn-success" value="Add Entry"></td>

			    <td style="text-align: right;"><input type="reset" 

			    class="btn btn-danger" value="Reset Form"></td></tr>

		        </table><a href="swfederation.php" class="btn btn-primary">

		        Display Database</a></form></div>';

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

					<form name="basic" method="POST" action="swfederation.php">

						<table class="table table-condensed" 

						    style="border: 1px solid #dddddd; 

							border-radius: 5px; box-shadow: 2px 2px 10px;">

							<tr><td colspan="2" style="text-align: center; 

							border-radius: 5px; color: white; 

							background-color:#333333;">

							<h2>Update your information!</h2></td></tr>

							<tr><td>Name: </td><td><input type="edit" 

							name="name" value="'. $row[1] .'" size="20">

							</td></tr>

							<tr><td>Height: (Use X.Y Format!) </td><td><input type="edit" 

							name="height" value="' . $row[2] . '" size="20">

							</td></tr>

							<tr><td>Weight: </td><td><input type="edit" 

							name="weight" value="' . $row[3] . '" size="30">

							</td></tr>

							<tr><td>Hometown: </td><td><input type="edit" 

							name="hometown" value="' . $row[4] . '" size="20">

							</td></tr>

							<tr><td>Finisher: </td><td><input type="edit" 

							name="finisher" value="' . $row[5] . '" size="30">

							</td></tr>

							<tr><td>Description: </td><td><textarea 

							style="resize: none;" name="description" cols="40" 

							rows="3">' . $row[6] . '</textarea></td></tr>																					

							<tr><td>User ID: </td><td><textarea 							

							style="resize: none;" name="user_id" cols="40" 							

							rows="3">' . $row[7] . '</textarea></td></tr>	

							<tr><td><input type="submit" name="updateCompleted" 

							class="btn btn-primary" value="Update Entry"></td>

							<td style="text-align: right;"><input type="reset" 

							class="btn btn-danger" value="Reset Form"></td></tr>

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

    if($stmt = $mysqli->prepare("DELETE FROM $usertable WHERE id=?")) // id can't be duplicated so it will only delete one record

    {

        // Bind parameters. Types: s=string, i=integer, d=double, etc.

		// protects against sql injections

        $stmt->bind_param('i', $index); // only checking id, no need to grab others

        $stmt->execute();

        $stmt->close();

    }

}



# ---------- insertRecord -----------------------------------------------------

function insertRecord($mysqli) //when insert record is completed

{

    global $name, $height, $weight, $hometown, $finisher, $price, $description, $user_id;

	global $usertable;

    

    $stmt = $mysqli->stmt_init();

    if($stmt = $mysqli->prepare("INSERT INTO $usertable (name,height,weight,hometown,

	    finisher,description,user_id) VALUES (?, ?, ?, ?, ?, ?, ?)"))

    {

        // Bind parameters. Types: s=string, i=integer, d=double, etc.

		// protects against sql injections, must match completely, or won't work

        $stmt->bind_param('ssssssi', $name, $height, $weight, $hometown, $finisher, 

		    $description, $user_id);

        $stmt->execute();

        $stmt->close();

    }

}



# ---------- updateRecord -----------------------------------------------------

function updateRecord($mysqli) //when the update record button is clicked

{

	global $name, $height, $weight, $hometown, $finisher, $price, $description, $user_id; 

	global $usertable;

	$index = $_POST['uid'];  // "uid" is id of db record to be updated 

    

    $stmt = $mysqli->stmt_init();

    if($stmt = $mysqli->prepare("UPDATE $usertable SET name = ?, height = ?, 

	    weight=?, hometown=?, finisher=?, description=?, user_id=? WHERE id = ?")) //sets up the paramaters

    {

        // Bind parameters. Types: s=string, i=integer, d=double, etc.

		// protects against sql injections, it must match exactly, or it won't work

        $stmt->bind_param('ssssssii', $name, $height, $weight, $hometown, $finisher, 

		    $description, $user_id, $index);

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

	<title>swfederation.php</title>

	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/

	3.2.0/css/bootstrap.min.css">

	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/

	3.2.0/css/bootstrap-theme.min.css">

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/

	3.2.0/js/bootstrap.min.js">

	</script></head><body>';

	//displays html for the body, uses wrestling pic on server.

echo '<div class="col-md-12" style="background-color: seagreen; border-bottom: 

    2px solid black; box-shadow: 3px 3px 5px #FFFFFF;">

	<img src="Wrestling.png" alt="Mountain View" style="width:150px;height:150px;margin-top: 5px;"></a>';

if ($_SESSION["user"] != '') //if a user is logged in

{

	$user = $_SESSION['user'];

	echo '<p style="font-size:18px; float: right; margin-top: 40px; 

	    margin-right: 20px;">Welcome <b>' .	$user . '</b>!</p>';

}

else // if a user is not logged in

{

	echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" 

	    action="../student14/login.php">

		<input type="text" size="9" name="username" class="form-control" placeholder="Username">

		<input type="password" size="9" name="password" class="form-control" placeholder="Password">

		<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>

	    </form>';

}

echo '</div>'; //ends html part of the header

}

?>