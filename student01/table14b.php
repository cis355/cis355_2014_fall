<!DOCTYPE html>
<!--
program: table14b.php
author:  george corser
course:  cis355 fall 2014
input:   $_POST, or nothing
processing: this program requires a browser user to call table14b.php
         from the search bar (or other link). 
		 1. Connect to DB
		 2. Get records
		 If records in DB
		     6. Show Item List
	     Else
		     3. Show Insert Form
		If user selected insert
		     3. Show Insert Form
		    
output:  HTML code 
-->
<html> 
	<head> <!---------- access bootstrap and display title ------------>
	<title>Zach Metiva - Database Table</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
</head>
<body>

<?php
// ========== 1. Connect to DB ================
// ---------- set connection variables ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table14";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);

if($mysqli) // if successful connection
{
    // ---------- 2. Get Records Process ----------
	$result = $mysqli->query("SELECT COUNT(*) FROM $usertable");
	$val1 = $result->fetch_row();
	
    if($val1[0] > 0)
	{ 
		// if there are records in the db, records > 0 exist in database table
		
		// if insert was called do not display the table
	    if(!isset($_POST['insertFromTable']) )
		{
	    // 6. Show Item List
		displayTable($mysqli);
		}
	}
		
	else // zero records in db or no table
	// change to show list but also message that rows = 0
	{
	    // Create table if necessary
		createTable($mysqli);
		// 3. Show insert form
		insertForm();
	}
	if(isset($_POST['insertFromTable']))
	{	
		// submit means insert (could have said submitInsert)
		// Create table if necessary
		createTable($mysqli);
		// 3. Show insert form
		insertForm();
	}
	if(isset($_POST['insertFromForm']))
	{
	    $type = $_POST['type'];
		$brand = $_POST['brand'];
		$model = $_POST['model'];
		$color = $_POST['color'];
		$strWind = $_POST['strWind'];
		$price = $_POST['price'];
		$descript = $_POST['descript'];
		
		createTable($mysqli);
		insertRecord($mysqli);
     }
	 if(isset($_POST['delete'])) // delete button on table list
	 {
	     $delID = $_POST['hid'];
		 deleteRecord($mysqli);
	 }
	 if(isset($_POST['update'])) // update button on table list
	 {
	    updateProcess($mysqli);
	 }
	 if(isset($_POST['submitUpdate'])) // update entry button update form
	 {
	    $type = $_POST['type'];
		$brand = $_POST['brand'];
		$model = $_POST['model'];
		$color = $_POST['color'];
		$strWind = $_POST['strWind'];
		$price = $_POST['price'];
		$descript = $_POST['descript'];
		$index = $_POST['index'];
		
	    updateRecord($mysqli);
	 }

        
} // ========== end of program ==========

function insertRecord($mysqli)
{
    /* vars from the post data that we will use to bind */
    global $type, $brand, $model, $color, $strWind, $price, $descript, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (type,brand,model,color,strWind,price,descript) VALUES (?, ?, ?, ?, ?, ?, ?)"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('sssssss', $type, $brand, $model, $color, $strWind, $price, $descript);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}
function checkConnect($mysqli)
{
    
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}

function displayTable($mysqli)
{
	echo 	'<div class="col-md-12"><form action="table14b.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: white;">Musical Instrument Database</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Type</td><td>Brand</td><td>Model</td><td>Color:</td><td>String/Wind</td><td>Price</td><td>Description</td><td></td></tr>';
	
	populateTable($mysqli);

	echo    '</table><input type="hidden" id="hid" name="hid" value=""><input type="hidden" id="uid" name="uid" value=""><input type="submit" name="insertFromTable" value="Add an Entry" class="btn btn-primary"></form></div>';
	
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

function populateTable($mysqli)
{
	global $usertable;
	
	$i = 0;
	
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
				 '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . $row[7] . 
				 '</td><td><input name="delete" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> <input style="margin-left: 10px;" type="submit" name="update" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
			$i++;
		}
	}
	$result->close();
}

function createTable($mysqli)
{
    global $usertable;
    /* test select via object */
    if($result = $mysqli->query("select id from $usertable limit 1"))
    {
        /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
        $row = $result->fetch_object();
		/** The fields in the results come back as properties of the fetched object. 
		*   Here since I selected the "id", the row has a property called "id".
		*/
		$id = $row->id;
        $result->close();
    }
    
    /* if nothing in $id*/
    if(!$id)
    {
	    $sql = "CREATE TABLE table14 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "type VARCHAR(20),";
	    $sql .= "brand VARCHAR(30),";
	    $sql .= "model VARCHAR(20),";
	    $sql .= "color VARCHAR(30),";
	    $sql .= "strWind VARCHAR(20),";
	    $sql .= "price VARCHAR(30),";
	    $sql .= "descript VARCHAR(100)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            /* execute prepared statement */
            $stmt->execute();
        }
    }  
}
function insertForm ()
{
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table14b.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Musical Instrument Form</h2></td></tr>
			<tr><td>Type: </td><td><input type="edit" name="type" value="" size="20"></td></tr>
			<tr><td>Brand: </td><td><input type="edit" name="brand" value="" size="20"></td></tr>
			<tr><td>Model: </td><td><input type="edit" name="model" value="" size="30"></td></tr>
			<tr><td>Color: </td><td><input type="edit" name="color" value="" size="20"></td></tr>
			<tr><td>String/Wind: </td><td><input type="edit" name="strWind" value="" size="30"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" size="20"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>
			<tr><td><input type="submit" name="insertFromForm" class="btn btn-success" value="Add Entry"></td>
				<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table>
		
		<a href="table14b.php" class="btn btn-primary">Display Database</a>
	</form>
</div>';
}

function deleteRecord($mysqli)
{
	/* vars from the post data that we will use to bind */
    global $delID, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("DELETE FROM $usertable WHERE id=?"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('i', $newID);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}

function updateRecord($mysqli)
{
	 global $type, $brand, $model, $color, $strWind, $price, $descript, $index, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("UPDATE $usertable SET type = ?, brand = ?, model = ?, color = ?, strWind = ?, price = ?, descript = ? WHERE id = ?"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('sssssssi', $type, $brand, $model, $color, $strWind, $price, $descript, $index);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}
function updateProcess($mysqli) // display update form 
{
		$index = $_POST['uid'];
		global $usertable;
		
		if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
		{
			while($row = $result->fetch_row())
			{
				echo '	<br>
						<div class="col-md-4">
						<form name="basic" method="POST" action="table14.php">
							<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
								<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Musical Instrument Form</h2></td></tr>
								<tr><td>Type: </td><td><input type="edit" name="type" value="'. $row[1] .'" size="20"></td></tr>
								<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="20"></td></tr>
								<tr><td>Model: </td><td><input type="edit" name="model" value="' . $row[3] . '" size="30"></td></tr>
								<tr><td>Color: </td><td><input type="edit" name="color" value="' . $row[4] . '" size="20"></td></tr>
								<tr><td>String/Wind: </td><td><input type="edit" name="strWind" value="' . $row[5] . '" size="30"></td></tr>
								<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[6] . '" size="20"></td></tr>
								<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[7] . '</textarea></td></tr>
								<tr><td><input type="submit" name="submitUpdate" class="btn btn-primary" value="Update Entry"></td>
									<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
							</table>
							<input type="hidden" name="index" value="' . $row[0] . '">
						</form>
					</div>';
			}
			$result->close();
		}
}