<?php
# --------------------------------------------------------------------------- #
# program: table06.php
# author:  george corser
# course:  cis355 fall 2014
# purpose: template for cis355
#          instructions: change all "table06.php" to your php filename, and 
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


function parms($string,$data) {
        $indexed=$data==array_values($data);
        foreach($data as $k=>$v) {
            if(is_string($v)) $v="'$v'";
            if($indexed) $string=preg_replace('/\?/',$v,$string,1);
            else $string=str_replace(":$k",$v,$string);
        }
        return $string;
    }


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
$usertable="table06";

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
	
    $book_title			= $_POST['book_title']; // if does not exist then value is ""
	$book_author 		= $_POST['book_author'];
	$book_publisher		= $_POST['book_publisher'];
	$book_published		= $_POST['book_published'];
	$book_edition		= $_POST['book_edition'];
	$book_genre			= $_POST['book_genre'];
	$book_isbn 			= $_POST['book_isbn'];
	$book_price			= $_POST['book_price'];
	$book_description 	= $_POST['book_description'];
	$book_condition		= $_POST['book_condition'];
	$location_id 		= $_POST['location_id'];
	
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
		case $viewSelected:
			displayHTMLHead();
			show_view_page($mysqli);
			break;
		case $insertCompleted: // updated to do Post/Redirect/Get (PRG)
			// if the data the user inserted is valid, insert it into the database
			if( isRecordValid() ) {
				insertRecord($mysqli);
				header("Location: " . $_SERVER['REQUEST_URI']); // redirect
				displayHTMLHead();
				$msg = 'record inserted';
				showList($mysqli, $msg);
			} else {
			// if the data the user enterted is invalid, do not insert anything into the database
				displayHTMLHead();
				echo 'You have entered invalid data! Record not inserted.';
				fixInsertForm($mysqli);
			}
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
	    $sql = "CREATE TABLE table06(
			ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
			post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			book_author TINYTEXT NOT NULL,
			book_title TINYTEXT NOT NULL,
			book_publisher TINYTEXT NOT NULL,
			book_published DATE NOT NULL,
			book_edition TINYINT(3) UNSIGNED,
			book_genre TINYTEXT NOT NULL,
			book_isbn TINYTEXT NOT NULL,
			book_price DOUBLE UNSIGNED NOT NULL,
			book_description TEXT NOT NULL,
			book_condition ENUM('poor', 'fair', 'good', 'new'),
			location_id INT,
			user_id INT,
			PRIMARY KEY ( ID ),
			FOREIGN KEY (location_id) REFERENCES locations (location_id),
			FOREIGN KEY (user_id) REFERENCES users (user_id)
		)";

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
			<form action="table06.php" method="POST">
			<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
				<tr>
					<td colspan="11" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
						<h2 style="color: white;">Book Database (table06)</h2>
					</td>
				</tr>
				<tr style="font-weight:800; font-size:20px;">
						<td>Title</td>
						<td>Author</td>
						<td>Genre</td>
						<td>Edition</td>
						<td>Book Price</td>
						<td>&nbsp;</td>
				</tr>';

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
			<input type="hidden" id="uid" name="uid" value="">';
			if( isset( $_SESSION["id"] ) && intval( $_SESSION["id"] ) > 0 ) {
				echo '<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary"">';
			}

	echo    '</form></div>';
	echo '<div style="margin: 15px;"><a href="http://cis355.com/student06/bionotes.html">Author Bio &amp; Program Notes</a></div>';

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
	
	if($result = $mysqli->query("SELECT id, book_title, book_author, book_genre, book_edition, book_price, user_id FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr>
				<td>' . $row[1] . '</td>
				<td>' . $row[2] . '</td>
				<td>' . $row[3] . '</td>
				<td>' . $row[4] . '</td>
				<td>' . $row[5] . '</td>';
			
            if ( $_SESSION["id"] == $row[6]) {
			echo '<td><input name="deleteSelected" type="submit" 
				class="btn btn-danger" value="Delete" onclick="setHid(' . 
				$row[0] .')" />' ;
			echo '<input style="margin-left: 10px;" type="submit" 
				name="updateSelected" class="btn btn-primary" value="Update" 
				onclick="setUid(' . $row[0] . ');" />';
			echo '<input style="margin-left: 10px;" type="submit" 
				name="viewSelected" class="btn btn-success" value="View" 
				onclick="setUid(' . $row[0] . ');" /></td>';
			} else {
				echo '<td><input style="margin-left: 10px;" type="submit" 
				name="viewSelected" class="btn btn-success" value="View" 
				onclick="setUid(' . $row[0] . ');" /></td>';
			}
			
		}

		$result->close();
	}
}

# ---------- showInsertForm ---------------------------------------------------
function showInsertForm($mysqli)
{
	
    echo '<br><div class="col-md-4">
	<form name="basic" method="POST" action="table06.php" 
	    onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #DDD; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr>
				<td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333;">
				<h2>Add Book Form</h2></td>
			</tr>
			<tr>
				<td>Title: </td>
				<td>
					<input type="text" name="book_title" size="20">
				</td>
			</tr>
			<tr>
				<td>Author: </td>
				<td>
					<input type="text" name="book_author" size="20">
				</td>
			</tr>
			<tr>
				<td>Publisher: </td>
				<td>
					<input type="text" name="book_publisher" size="20">
				</td>
			</tr>
			<tr>
				<td>Publish Date: </td>
				<td>
					<input type="date" name="book_published">
				</td>
			</tr>
			<tr>
				<td>Edition: </td>
				<td>
					<input type="number" name="book_edition" min="1" max="5" size="20">
				</td>
			</tr>
			<tr>
				<td>Genre: </td>
				<td><input type="text" name="book_genre" size="30">
				</td>
			</tr>
			<tr>
				<td>ISBN: </td>
				<td>
					<input type="text" name="book_isbn" size="30">
				</td>
			</tr>
			<tr>
				<td>Price: </td>
				<td>
					<input type="" name="book_price" size="30">
				</td>
			</tr>
			<tr>
				<td>Description: </td>
				<td>
					<textarea style="resize: none;" name="book_description" cols="40" rows="3"></textarea>
				</td>
			</tr>
			<tr>
				<td>Condition: </td><td>
					<select name="book_condition">
						<option value="poor">Poor</option>
						<option value="fair">Fair</option>
						<option value="good">Good</option>
						<option value="new">New</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Location of Book: </td><td>
					<select name="location_id">';
						
						if($result = $mysqli->query("SELECT * FROM locations")) {

							while($location = $result->fetch_row()) {
								echo '<option value="'. $location[0] .'" '. ( $_SESSION['location'] == $location[0] ? 'selected' : '' ) .'>'. '(' . $location[0] . ') ' . ucwords( $location[1] ) .'</option>';
							}

							$result->close();
						}

					echo '</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry">
				</td>
				<td style="text-align: right;">
					<input type="reset" class="btn btn-danger" value="Reset Form">
				</td>
			</tr>
		</table>
		<a href="table06.php" class="btn btn-primary">Display Database</a>
		<div>&nbsp;</div>
		</form></div>';
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
			echo '<div>&nbsp;</div><div class="col-md-4">
					<form name="basic" method="POST" action="table06.php">
						<input type="hidden" name="hid" value="'. $_POST['uid'] .'">
						<table class="table table-condensed" style="border: 1px solid #DDD; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr>
								<td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333;">
								<h2>Add Book Form</h2></td>
							</tr>
							<tr>
								<td>Title: </td>
								<td>
									<input type="text" name="book_title" size="20" value="' . $row[2] . '">
								</td>
							</tr>
							<tr>
								<td>Author: </td>
								<td>
									<input type="text" name="book_author" size="20" value="' . $row[3] . '">
								</td>
							</tr>
							<tr>
								<td>Publisher: </td>
								<td>
									<input type="text" name="book_publisher" size="20" value="' . $row[4] . '">
								</td>
							</tr>
							<tr>
								<td>Publish Date: </td>
								<td>
									<input type="date" name="book_published" value="' . $row[5] . '">
								</td>
							</tr>
							<tr>
								<td>Edition: </td>
								<td>
									<input type="number" name="book_edition" min="1" max="5" size="20" value="' . $row[6] . '">
								</td>
							</tr>
							<tr>
								<td>Genre: </td>
								<td><input type="text" name="book_genre" size="30" value="' . $row[7] . '">
								</td>
							</tr>
							<tr>
								<td>ISBN: </td>
								<td>
									<input type="text" name="book_isbn" size="30" value="' . $row[8] . '">
								</td>
							</tr>
							<tr>
								<td>Price: </td>
								<td>
									<input type="" name="book_price" size="30" value="' . $row[9] . '">
								</td>
							</tr>
							<tr>
								<td>Description: </td>
								<td>
									<textarea style="resize: none;" name="book_description" cols="40" rows="3">' . $row[10] . '</textarea>
								</td>
							</tr>
							<tr>
								<td>Condition: </td><td>
									<select name="book_condition">
										<option value="poor" '. ( $row[11] === "poor" ? 'selected' : '' ) .'>Poor</option>
										<option value="fair" '. ( $row[11] === "fair" ? 'selected' : '' ) .'>Fair</option>
										<option value="good" '. ( $row[11] === "good" ? 'selected' : '' ) .'>Good</option>
										<option value="new" '. ( $row[11] === "new" ? 'selected' : '' ) .'>New</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Location of Book: </td><td>
									<select name="location_id">';
										
										if($result = $mysqli->query("SELECT * FROM locations")) {

											while($location = $result->fetch_row()) {
												echo '<option value="'. $location[0] .'" '. ($row[12] == $location[0] ? 'selected="selected"' : '') .'>'. '(' . $location[0] . ') ' . ucwords( $location[1] ) . '</option>';
											}

											$result->close();
										}

									echo '</select>
								</td>
							</tr>
							<tr>
								<td>
									<input type="submit" name="updateCompleted" class="btn btn-primary" value="Update Entry">
								</td>
								<td style="text-align: right;">
									<input type="reset" class="btn btn-danger" value="Reset Form">
								</td>
							</tr>
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
						<a href="table06.php" class="btn btn-primary">Display Database</a>
						<div>&nbsp;</div>
					</form>
				</div>';
		}
		$result->close();
	}
}
# ---------- fixUpdateForm --------------------------------------------------
function fixInsertForm($mysqli) 
{
	global $book_title, $book_author, $book_publisher, $book_published, $book_edition, $book_genre, $book_isbn, $book_price, $book_description, $book_condition, $location_id, $user_id;
	global $usertable;
	
	echo '<div>&nbsp;</div><div class="col-md-4">
			<form name="basic" method="POST" action="table06.php">
				<table class="table table-condensed" style="border: 1px solid #DDD; border-radius: 5px; box-shadow: 2px 2px 10px;">
					<tr>
						<td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333;">
						<h2>Add Book Form</h2></td>
					</tr>
					<tr>
						<td>Title: </td>
						<td>
							<input type="text" name="book_title" size="20" value="' . $book_title . '">
						</td>
					</tr>
					<tr>
						<td>Author: </td>
						<td>
							<input type="text" name="book_author" size="20" value="' . $book_author . '">
						</td>
					</tr>
					<tr>
						<td>Publisher: </td>
						<td>
							<input type="text" name="book_publisher" size="20" value="' . $book_publisher . '">
						</td>
					</tr>
					<tr>
						<td>Publish Date: </td>
						<td>
							<input type="date" name="book_published" value="' . $book_published . '">
						</td>
					</tr>
					<tr>
						<td>Edition: </td>
						<td>
							<input type="number" name="book_edition" min="1" max="5" size="20" value="' . $book_edition . '">
						</td>
					</tr>
					<tr>
						<td>Genre: </td>
						<td><input type="text" name="book_genre" size="30" value="' . $book_genre . '">
						</td>
					</tr>
					<tr>
						<td>ISBN: </td>
						<td>
							<input type="text" name="book_isbn" size="30" value="' . $book_isbn . '">
						</td>
					</tr>
					<tr>
						<td>Price: </td>
						<td>
							<input type="" name="book_price" size="30" value="' . $book_price . '">
						</td>
					</tr>
					<tr>
						<td>Description: </td>
						<td>
							<textarea style="resize: none;" name="book_description" cols="40" rows="3">' . $book_description . '</textarea>
						</td>
					</tr>
					<tr>
						<td>Condition: </td><td>
							<select name="book_condition">
								<option value="poor" '. ( $book_condition === "poor" ? 'selected' : '' ) .'>Poor</option>
								<option value="fair" '. ( $book_condition === "fair" ? 'selected' : '' ) .'>Fair</option>
								<option value="good" '. ( $book_condition === "good" ? 'selected' : '' ) .'>Good</option>
								<option value="new" '. ( $book_condition === "new" ? 'selected' : '' ) .'>New</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Location of Book: </td><td>
							<select name="location_id">';
								
								if($result = $mysqli->query("SELECT * FROM locations")) {

									while($location = $result->fetch_row()) {
										echo '<option value="'. $location_id .'" '. ($row[12] == $location_id ? 'selected="selected"' : '') .'>'. '(' . $location[0] . ') ' . $location[1] . '</option>';
									}

									$result->close();
								}

							echo '</select>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="insertCompleted" class="btn btn-primary" value="Add Record">
						</td>
						<td style="text-align: right;">
							<input type="reset" class="btn btn-danger" value="Reset Form">
						</td>
					</tr>
				</table>
			</form>
		</div><br>';
}
# ---------- show_view_page -----------------------------------------------------
function show_view_page($mysqli) {

	$index = $_POST['uid'];  // "uid" is id of db record to be viewed
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '<br><div class="col-md-4">
					<form name="basic" method="POST" action="table06.php">
						<input type="hidden" name="hid" value="'. $_POST['uid'] .'">
						<table class="table table-condensed" style="border: 1px solid #DDD; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr>
								<td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333;">
								<h2>View Book</h2></td>
							</tr>
							<tr>
								<td>Title: </td>
								<td>
									<span>' . $row[2] . '</span>
								</td>
							</tr>
							<tr>
								<td>Author: </td>
								<td>
									<span>' . $row[3] . '</span>
								</td>
							</tr>
							<tr>
								<td>Publisher: </td>
								<td>
									<span>' . $row[4] . '</span>
								</td>
							</tr>
							<tr>
								<td>Publish Date: </td>
								<td>
									<span>' . $row[5] . '</span>
								</td>
							</tr>
							<tr>
								<td>Edition: </td>
								<td>
									<span>' . $row[6] . '</span>
								</td>
							</tr>
							<tr>
								<td>Genre: </td>
								<td>
								<span>' . $row[7] . '</span>
								</td>
							</tr>
							<tr>
								<td>ISBN: </td>
								<td>
									<span>' . $row[8] . '</span>
								</td>
							</tr>
							<tr>
								<td>Price: </td>
								<td>
									<span>' . $row[9] . '</span>
								</td>
							</tr>
							<tr>
								<td>Description: </td>
								<td>
									<span>' . $row[10] . '</span>
								</td>
							</tr>
							<tr>
								<td>Condition: </td><td>
									<span>' . ucfirst( $row[11] ) . '</span>
								</td>
							</tr>
							<tr>
								<td>Location: </td><td><span>';
									
									$location_name = $mysqli->query("SELECT name FROM locations WHERE location_id = " . $row[12] );
									$location_name = $location_name->fetch_row();


								echo $location_name[0] . '</span></td>
							</tr>
							<tr>
								<td>
									<a href="http://cis355.com/student06/table06.php" class="btn btn-primary">Back</a>
								</td>
								<td>&nbsp;</td>
							</tr>
						</table>
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

	global $book_title, $book_author, $book_publisher, $book_published, $book_edition, $book_genre, $book_isbn, $book_price, $book_description, $book_condition, $location_id, $user_id;
	global $usertable;

	$stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (post_date, book_title, book_author, book_publisher, book_published, book_edition, book_genre, book_isbn, book_price, book_description, book_condition, location_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('sssssissdssii', date('Y-m-d H:i:s'), $book_title, $book_author, $book_publisher, $book_published, $book_edition, $book_genre, $book_isbn, $book_price, $book_description, $book_condition, $location_id, $_SESSION['id'] );
        if( ! $stmt->execute() )
        	die("Error executing insert query!");
		$stmt->close();
    }
}

# ---------- updateRecord -----------------------------------------------------
function updateRecord($mysqli)
{
	global $book_title, $book_author, $book_publisher, $book_published, $book_edition, $book_genre, $book_isbn, $book_price, $book_description, $book_condition, $location_id, $user_id;
	global $usertable;
	$index = $_POST['uid'];  // "uid" is id of db record to be updated

	$stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE $usertable SET book_title=?, book_author=?, 
	    book_publisher=?, book_published=?, book_edition=?, book_genre=?, book_isbn=?, book_price=?, book_description=?, book_condition=? WHERE id = ?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
         $stmt->bind_param('ssssissdssi', $book_title, $book_author, $book_publisher, $book_published, $book_edition, $book_genre, $book_isbn, $book_price, $book_description, $book_condition, $index );
        $stmt->execute();
        $stmt->close();
    }
}

function isRecordValid() {
	global $book_title, $book_author, $book_publisher, $book_published, $book_edition, $book_genre, $book_isbn, $book_price, $book_description, $book_condition, $location_id, $user_id;
	global $usertable;

	// Check if the book title is valid
	if( ! preg_match( '/[A-Za-z0-9\:\;\"\-\?]{2,}(?:\s[A-Za-z0-9\:\;\"\-\?]{2,})*/', $book_title ) )
		return false;

	// Check if the book author is valid
	if( ! preg_match( '/[A-Za-z\-]{2,}(?:\s[A-Za-z]{2,})*/', $book_author ) )
		return false;

	// Check if the book publisher
	if( ! preg_match( '/[A-Za-z\-]{2,}(?:\s[A-Za-z]{2,})*/', $book_publisher ) )
		return false;

	// If the flow of control made it to this point, all of the user inputted data must be OK
	return true;
}

# ---------- updateRecord -----------------------------------------------------
function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table06.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
	
echo '<div class="col-md-12" style="background-color: tan; border-bottom: 
    2px solid black; box-shadow: 3px 3px 5px #888888;">
	<a href="../student14/landing.php"><img src="../student14/LWIP_logo.png" style="margin-top: 5px;"></a>';
if ($_SESSION["user"] != '')
{
	$user = $_SESSION['user'];
	echo '<p style="font-size:18px; float: right; margin-top: 40px; 
	    margin-right: 20px;">Welcome <b>' .	$user . '</b>!</p>';
}
else
{
	echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" 
	    action="../student14/login.php">
		<input type="text" size="9" name="username" class="form-control" placeholder="Username">
		<input type="password" size="9" name="password" class="form-control" placeholder="Password">
		<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
	    </form>';
}
echo '<br><br></div><span>&nbsp;</span>';
}
?>