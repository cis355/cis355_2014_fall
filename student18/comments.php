<?php
# --------------------------------------------------------------------------- #
# program: comments.php
# author:  Shawn Wagner
# course:  cis355 fall 2014
# purpose: secondary page
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
$usertable="comments18";

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
	
	$comment_id         = $_POST['comment_id'];
	$user_id        	= $_POST['user_id'];
	$comment            = $_POST['comment'];
	
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
    if($result = $mysqli->query("select id from comments18 limit 1"))
    {
        $row = $result->fetch_object();
		$id = $row->id;
        $result->close();
    }
    if(!$id)
    {
	    $sql = "CREATE TABLE comments18(";
	    $sql .= "comment_id INT NOT NULL,PRIMARY KEY(comment_id),";
	    $sql .= "user_id INT,";
	    $sql .= "comment VARCHAR(140),";
		$sql .= "FOREIGN KEY (user_id) REFERENCES users18(user_id))";
		
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
			<form action="comments.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="12" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2 style="color: white;">Comments Database (comments18)</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>Comment ID #</td><td>User ID #</td><td>Comment</td></tr>';

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM comments18");
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
			<a href="table1.php" class="btn btn-primary">Main Table</a>
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
	
	if($result = $mysqli->query("SELECT * FROM comments18"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2];
				
		    if ($_SESSION["id"]==$row[1]) {
			echo '</td><td><input name="deleteSelected" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> 
			    <input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />
				<input name="viewSelected" type="submit" class="btn btn-success" value="View" onclick="setUid(' . $row[0] . ');"/>';
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
	<form name="basic" method="POST" action="comments.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Comment Form</h2></td></tr>
			<tr><td>Comment ID #: </td><td><input type="edit" name="comment_id" value="" size="10"></td></tr>
			<tr><td>User ID: </td><td><input type="text" name="user_id" value="'.$_SESSION["id"].'" size="5" readonly></td></tr>
			<tr><td>Comment:</td><td><input type="text" name="comment" value="" size="140"></td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table><a href="comments.php" class="btn btn-primary">Cancel</a></form></div>';
}

# ---------- showUpdateForm --------------------------------------------------
//shows the update form and fills it with the data to be updated
function showUpdateForm($mysqli) 
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM comments18 WHERE comment_id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="comments.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
							<h2>Comment Form</h2></td></tr>
							<tr><td>Comment ID: </td><td><input type="edit" name="comment_id" value="' . $row[0] . '" size="10"></td></tr>';
				echo	   '</td></tr>
						    <tr><td>User_ID: </td><td><input type="edit" name="user_id" value="' . $row[1] . '" size="10" readonly></td></tr>
							<tr><td>Comment: </td><td><input type="edit" name="comment" value="' . $row[2] . '" size="140"></td></tr>
						    <tr><td><input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry"></td>
						    <td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
					</form><a href="comments.php" class="btn btn-primary">Cancel</a></form>
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
    if($stmt = $mysqli->prepare("DELETE FROM comments18 WHERE comment_id=?"))
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
    global $comment_id, $user_id, $comment;
	global $usertable;
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO comments18 (comment_id,user_id,comment) VALUES (?, ?, ?)"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('iis', $comment_id, $user_id, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

# ---------- updateRecord -----------------------------------------------------
//the code that is run when the update entry button on the update form is clicked
function updateRecord($mysqli)
{
	global $comment_id, $user_id, $comment;
	global $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated 
    
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE comments18 SET comment_id=?, user_id=?, comment=? WHERE comment_id = ?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('iisi', $comment_id, $user_id, $comment, $index);
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
	<title>comments.php</title>
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

	if($result = $mysqli->query("SELECT * FROM comments18 WHERE comment_id = $index"))
	{
	    $row = $result->fetch_row();
		 echo '	<br>
				<div class="col-md-4">
				<form name="basic" method="POST" action="comments.php">
				<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px;
				box-shadow: 2px 2px 10px;">
				<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white;
				background-color:#333333;">
				<h2>Comments</h2></td></tr>
				<tr><td>Comment ID: </td><td>'. $row[0] .'</td></tr>
				<tr><td>User ID: </td><td>' . $row[1] . '</td></tr>
				<tr><td>Comment: </td><td>' . $row[2] . '</td></tr>
				</table>
                <a href="comments.php" class="btn btn-primary">Display Database</a>
				</form></div>';
		$result->close();
	}
} 

?>