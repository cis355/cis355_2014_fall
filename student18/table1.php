<?php
# --------------------------------------------------------------------------- #
# program: project.php
# author:  Shawn Wagner
# course:  cis355 fall 2014
# purpose: main data table page
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
ini_set("session.cookie_domain", ".cis355.com/student18");
session_start();

$_SESSION["user"] = "user";
$_SESSION["id"] = 2;


// ---------- a. display (echo) html head and link to bootstrap css -----------
// moved to section "f" to solve Post/Redirect/Get problem
// displayHTMLHead();

// ---------- b. set connection variables and verify connection ---------------
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="datatable18";

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
	$seller 			= $_POST['seller'];
	$price 				= $_POST['price'];
	$date_purchased 	= $_POST['date_purchased'];
	$date_failed        = $_POST['date_failed'];
	$rma_accepted       = $_POST['rma_accepted'];
	$user_id        	= $_POST['user_id'];
	$comment_id         = $_POST['comment_id'];
	
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
//checks if the page can connect to the database, if not display a message and do nothing
function checkConnect($mysqli)
{
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}
# ---------- createTable ------------------------------------------------------
//creates a table if there isn't one already.
function createTable($mysqli)
{
    global $usertable;
    if($result = $mysqli->query("select id from datatable18 limit 1"))
    {
        $row = $result->fetch_object();
		$id = $row->id;
        $result->close();
    }
    if(!$id)
    {
	    $sql = "CREATE TABLE datatable18 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),";
	    $sql .= "type VARCHAR(20),";
	    $sql .= "brand VARCHAR(30),";
	    $sql .= "model VARCHAR(20),";
		$sql .= "seller VARCHAR(30),";
	    $sql .= "price FLOAT(7,2),";
	    $sql .= "date_purchased VARCHAR(10),";
		$sql .= "date_failed VARCHAR(10),";
		$sql .= "rma_accepted VARCHAR(3),";
		$sql .= "user_id INT,";		
		$sql .= "comment_id INT,";
		$sql .= "FOREIGN KEY (user_id) REFERENCES users(user_id),";
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
	
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="table1.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="13" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">RMA Counter Database (datatable18)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Type</td><td>Brand</td>
			<td>Model</td><td>Seller</td><td>Price</td><td>Date Purchased</td><td>Date Failed</td>
			<td>RMA Accepted?</td><td>User ID</td><td>Comment ID</td></tr>';

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM datatable18");
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
			<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary"">
			<a href="comments.php" class="btn btn-primary">Comments Table</a>
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
	
	if($result = $mysqli->query("SELECT * FROM datatable18"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				'</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . '</td><td>' . $row[8] . '</td><td>' . $row[9] . '</td><td>' . $row[10];
			
			echo '</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');"/></td>';
				
		    if ($_SESSION["id"]==$row[9]) {
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
//shows the insert form when the insert button on the table page is clicked
 function showInsertForm ($mysqli)
{		
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table1.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>RMA Counter Form</h2></td></tr>
			<tr><td>Type: </td><td><input type="edit" name="type" value="" size="20"></td></tr>
			<tr><td>Brand: </td><td><input type="edit" name="brand" value="" size="20"></td></tr>
			<tr><td>Model: </td><td><input type="edit" name="model" value="" size="30"></td></tr>
			<tr><td>Seller: </td><td><input type="edit" name="seller" value="" size="30"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" size="20"></td></tr>
			<tr><td>Date Purchased: </td><td><input type="edit" name="date_purchased" value="" size="10"></td></tr>
			<tr><td>Date Failed: </td><td><input type="edit" name="date_failed" value="" size="10"></td></tr></td></tr>
			<tr><td>RMA Accepted (YES or NO): </td><td><input type="edit" name="rma_accepted" value="" size="10"></td></tr></td></tr>
			<tr><td>User ID: </td><td><input type="text" name="user_id" value="'.$_SESSION["id"].'" size="5" readonly></td></tr></td></tr>
			<tr><td>Comment ID: </td><td><input type="edit" name="comment_id" value="" size="5" ></td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="table1.php" class="btn btn-primary">Cancel</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
//shows the update form and fills it with the data to be updated
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM datatable18 WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table1.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
							<h2>Computer Component Form</h2></td></tr>
							<tr><td>Type: </td><td><input type="edit" name="type" value="'. $row[1] .'" size="20"></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="20"></td></tr>
							<tr><td>Model: </td><td><input type="edit" name="model" value="' . $row[3] . '" size="30"></td></tr>
							<tr><td>Seller: </td><td><input type="edit" name="seller" value="' . $row[4] . '" size="30"></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[5] . '" size="10"></td></tr>
							<tr><td>Date Purchased: </td><td><input type="edit" name="date_purchased" value="' . $row[6] . '" size="10"></td></tr>
							<tr><td>Date Failed: </td><td><input type="edit" name="date_failed" value="' . $row[7] . '" size="10"></td></tr>
							<tr><td>RMA Accepted: </td><td><input type="edit" name="rma_accepted" value="' . $row[8] . '" size="3"></td></tr>';
				echo	   '</td></tr>
						    <tr><td>User_ID: </td><td><input type="edit" name="user_id" value="' . $row[9] . '" size="20" readonly></td></tr>
						    <tr><td><input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry"></td>
						    <td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
					</form><a href="table1.php" class="btn btn-primary">Cancel</a></form>
				</div>';
		}
		$result->close();
	}
}

# ---------- deleteRecord -----------------------------------------------------
//the code that is run when the delete button is clicked
function deleteRecord($mysqli)
{
	$index = $_POST['hid'];  // "hid" is id of db record to be deleted
	global $usertable;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("DELETE FROM datatable18 WHERE id=?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('i', $index);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- insertRecord -----------------------------------------------------
//the code that is run when the add entry button on the insert form is clicked
function insertRecord($mysqli)
{
    global $type, $brand, $model, $seller, $price, $date_purchased, $date_failed, $rma_accepted, $user_id, $comment_id;
	global $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO datatable18 (type,brand,model,seller,price,date_purchased,date_failed,rma_accepted,user_id,comment_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('ssssdsssii', $type, $brand, $model, $seller, $price, $date_purchased, $date_failed, $rma_accepted, $user_id, $comment_id);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord -----------------------------------------------------
//the code that is run when the update entry button on the update form is clicked
function updateRecord($mysqli)
{
	global $type, $brand, $model, $seller, $price, $date_purchased, $date_failed, $rma_accepted, $user_id, $comment_id;
	global $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE datatable18 SET type = ?, brand = ?, model=?, seller=?, price=?, date_purchased=?, date_failed=?, rma_accepted=?, user_id=?, comment_id=? WHERE id = ?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('ssssdsssiii', $type, $brand, $model, $seller, $price, $date_purchased, $date_failed, $rma_accepted, $user_id, $comment_id, $index);
        $stmt->execute();
        $stmt->close();
    }
} 

# ---------- displayHTMLHead -----------------------------------------------------
//displays the html header on the page
function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table1.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>
		<div class="col-md-12" style="background-color: #B4EAF0; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="//www.cis355.com/student18/project.php"><img src="http://cis355.com/student18/RMA_Logo.png" style="margin-top: 5px;"></a>';

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
//the code that is run when the view button on the table page is clicked
function viewRecords($mysqli)
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;

	if($result = $mysqli->query("SELECT * FROM datatable18 WHERE id = $index"))
	{
	    $row = $result->fetch_row();
		
  		 echo '	<br>
				<div class="col-md-4">
				<form name="basic" method="POST" action="table1.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
				<h2>Comments</h2></td></tr>
				<tr><td>Type: </td><td>'. $row[1] .'</td></tr>
				<tr><td>Brand: </td><td>' . $row[2] . '</td></tr>
				<tr><td>Model: </td><td>' . $row[3] . '</td></tr>
				<tr><td>Seller: </td><td>' . $row[4] . '</td></tr>
				<tr><td>Price: </td><td>' . $row[5] . '</td></tr>
				<tr><td>Date Purchased: </td><td>' . $row[6] . '</td></tr>
				<tr><td>Date Failed: </td><td>' . $row[7] . '</td></tr>
				<tr><td>RMA Accepted: </td><td>' . $row[8] . '</td></tr>
				<tr><td>User ID: </td><td>' . $row[9] . '</td></tr>
				<tr><td>Comment ID: </td><td>' . $row[10] . '</td></tr>
				</table>
                <a href="table1.php" class="btn btn-primary">Display Database</a>
				</form></div>';  
		$result->close();
	}
} 

?>