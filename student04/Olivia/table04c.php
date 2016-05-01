<!DOCTYPE html>
<html> 
	<head> <!---------- access bootstrap and display title ------------>
	<title>Olivia Archambo - Database Table</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
</head>
<body>

<?php
// ========== 1. Connect Process ================
// ---------- set connection variables ----------
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table04c";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);

if($mysqli) // if successful connection
{
    // ---------- 2. Get Records Process ----------
	$result = $mysqli->query("SELECT COUNT(*) FROM $usertable");
	$val1 = $result->fetch_row();
	
    if($val1[0] > 0) // if records > 0 exist in database table
	{
	if(!isset($_POST['submit']))
	{
	    // 6. Show Item List
		displayTable($mysqli);
	}	
	}
	else // zero records in db or no table
	{
	    // Create table if necessary
		createTable($mysqli);
		// 3. Show insert form
		insertForm();
	}

	
} // ========== end of program ==========

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
	echo 	'<div class="col-md-12"><form action="table04c.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: white;">Compound Bow Database</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>User ID</td><td>Location ID</td><td>Name</td><td>Description</td><td>Price</td><td>Draw Weight</td><td>Draw Length</td><td>Condition</td></tr>';
	
	populateTable($mysqli);

	echo    '</table><input type="hidden" id="hid" name="hid" value=""><input type="hidden" id="uid" name="uid" value=""></form><a href="table04c.html" class="btn btn-primary">Add an Entry</a></div>';
	
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
	   $sql = "CREATE TABLE $usertable (";
        $sql .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
		$sql .= "user_id INT, ";
		$sql .= "loc_id INT, ";
        $sql .= "name VARCHAR(30), ";
        $sql .= "descript VARCHAR(100), ";
		$sql .= "amount_paid DECIMAL(5,2), ";
		$sql .= "drweight INT, ";
		$sql .= "drlength INT, ";
		$sql .= "cond VARCHAR(20)";
        $sql .= ");";

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
	<form name="basic" method="POST" action="table04c.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Musical Instrument Form</h2></td></tr>
			<tr><td>User ID: </td><td><input type="edit" name="user_id" value="" size="6"></td></tr>
			<tr><td>Location ID/ZIP: </td><td><input type="edit" name="loc_id" value="" size="5"></td></tr>
			<tr><td>Compound Bow Name: </td><td><input type="edit" name="name" value="" size="20"></td></tr>
			<tr><td>Bow Description: </td><td><input type="edit" name="descript" value="" size="40"></td></tr>
			<tr><td>Price: </td><td><input type="edit" name="amount_paid" value="" size="7"></td></tr>
			<tr><td>Draw Weight (lbs): </td><td><input type="edit" name="drweight" value="" size="4"></td></tr>
			<tr><td>Draw Length (inches):</td><td><input type="edit" name="drlength" value='' size=4></td></tr>
			<tr><td>Condition (New/Used):</td><td><input type="edit" name="cond" value='' size=30></td></tr>
			<tr><td><input type="submit" name="submit" class="btn btn-success" value="Add Entry"></td>
				<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table>
		
		<a href="table04c.php" class="btn btn-primary">Display Database</a>
	</form>
</div>';
}