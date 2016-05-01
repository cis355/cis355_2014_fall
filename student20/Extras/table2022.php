<!DOCTYPE html>
<html>
	<head>
	<title>GW - Database Table</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
</head>
<body>
<?php
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table20";


$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);

if($mysqli)
{
	$dTable = true;    /* This is for updates to show the table */
	/* $_POST is a global array that HTML pushes and 'hid' is a form */
	if($_POST["hid"] != "")
	{
		$newID = $_POST['hid'];
		/*deleteRecord($mysqli);*/
		displayTable($mysqli);
		$dTable = false;
	}

	if($_POST['uid'] != "")
	{
		$index = $_POST['uid'];
		global $usertable;
		
		if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
		{
			/* This is where you fetch all the rows from the table */
			while($row = $result->fetch_row())
			{
				echo '	<br>
						<div class="col-md-4">
						<form name="basic" method="POST" action="table20.php">
							<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>RV</h2></td></tr>
								<tr><td>Item Name</td><td><input type="edit" name="item_name" value="'. $row[1] .' size="30"></td></tr>
								<tr><td>Manufacturer</td><td><input type="edit" name="manufacturer" value="'. $row[2] .' size="20"></td></tr>
								<tr><td>Year</td><td><input type="edit" name="year" value="'. $row[3] .' size="5"></td></tr>
								<tr><td>Class Type </td>
								<td><select name="class_type">
								<option value="'. $row[4] .'>Class A</option>
								<option value="'. $row[4] .'>Class B</option>
								<option value="'. $row[4] .'>Class C</option>
								<option value="'. $row[4] .'>Class D</option>
								</select></td></tr>
								<tr><td>Condition </td>
								<td><select name="condition" >
								<option value="'. $row[5] .'>Excellent</option>
								<option value="'. $row[5] .'>Good</option>
								<option value="'. $row[5] .'>Average</option>
								<option value="'. $row[5] .'>Poor</option>
								<option value="'. $row[5] .'>Very Poor</option>
								</select></td></tr>
								<tr><td>Engine Type</td>
								<td><select name="engine_type">
								<option value="'. $row[6] .'>Gasoline</option>
								<option value="'. $row[6] .'>Diesel</option>
								<option value="'. $row[6] .'>Turbo Diesel</option>
								</select></td></tr>
								<tr><td>Color</td><td><input type="edit" name="color" value="'. $row[7] .' size="20"></td></tr>
								<tr><td>Amount Paid $</td><td><input type="edit" name="amt_paid" value="'. $row[8] .' size="10"></td></tr>
								<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">'. $row[9] .'</textarea></td></tr>
							<tr><td><input type="submit" name="submitUpdate" class="btn btn-primary" value="Update Entry"></td>
							<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
							</table>
							<input type="hidden" name="index" value="' . $row[0] . '">
						</form>
					</div>';
			}
			$result->close();
			$dTable = false;
		}
	}
	
	if(isset($_POST['submitUpdate']))
	{
		$item_name = $_POST['item_name'];
		$manufacturer = $_POST['manufacturer'];
		$year = $_POST['year'];
		$class_type = $_POST['class_type'];
		$condition = $_POST['condition'];
		$engine_type = $_POST['engine_type'];
		$color = $_POST['color'];
		$amt_paid = $_POST['amt_paid'];
		$descript = $_POST['descript'];
		
		updateRecord($mysqli);
		displayTable($mysqli);
		$dTable = false;
	}
	
	if(isset($_POST['submit']))
	{   
		$item_name = $_POST['item_name'];
		$manufacturer = $_POST['manufacturer'];
		$year = $_POST['year'];
		$class_type = $_POST['class_type'];
		$condition = $_POST['condition'];
		$engine_type = $_POST['engine_type'];
		$color = $_POST['color'];
		$amt_paid = $_POST['amt_paid'];
		$descript = $_POST['descript'];
		
		createTable($mysqli);
		insertRecord($mysqli);
		displayTable($mysqli);
		$dTable = false;
	}
	if($dTable)
	{
		displayTable($mysqli);
	}
}

function displayTable($mysqli)
{
	echo 	'<div class="col-md-12"><form action="table20.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: white;">RV</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Item Name</td><td>Manufacturer</td><td>Year</td><td>Class Type</td><td>Condition</td>
			<td>Engine Type</td><td>Color</td><td>Amount Paid</td><td>Description</td></tr>';
	
	populateTable($mysqli);

	echo    '</table><input type="hidden" id="hid" name="hid" value=""><input type="hidden" id="uid" name="uid" value=""></form><a href="table20.html" class="btn btn-primary">Add an Entry</a></div>';
	
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

function checkConnect($mysqli)
{
    
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
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
				 '</td><td>' . $row[8] . '</td><td>' . $row[9] .
				 '</td><td><input name="delete" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /> <input style="margin-left: 10px;" type="submit" name="update" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" />';
			$i++;
		}
	}
	/* $result->close(); */
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
	    $sql = "CREATE TABLE table20 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "item_name VARCHAR(30),";
	    $sql .= "manufacturer VARCHAR(30),";
	    $sql .= "year VARCHAR(4),";
	    $sql .= "class_type VARCHAR(30),";
	    $sql .= "condition VARCHAR(20),";
	    $sql .= "engine_type VARCHAR(30),";
	    $sql .= "color VARCHAR(20)";
		$sql .= "amt_paid VARCHAR(20)";
		$sql .= "descript VARCHAR(100)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            /* execute prepared statement */
            $stmt->execute();
        }
    }  
}

function insertRecord($mysqli)
{
    /* vars from the post data that we will use to bind */
    global $item_name, $manufacturer, $year, $class_type, $condition, $engine_type, $color, $amt_paid, $descript, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (item_name, manufacturer, year, class_type, condition, engine_type, color, amt_paid, descript) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('sssssssss', $item_name, $manufacturer, $year, $class_type, $condition, $engine_type, $color, $amt_paid, $descript);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}

function deleteRecord($mysqli)
{
	/* vars from the post data that we will use to bind */
    global $newID, $usertable;
    
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
	 global $item_name, $manufacturer, $year, $class_type, $condition, $engine_type, $color, $amt_paid, $descript, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("UPDATE $usertable SET item_name = ?, manufacturer = ?, year = ?, class_type = ?, condition = ?, engine_type = ?, color = ?, amt_paid = ?, descript = ? WHERE id = ?"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('sssssssssi', $item_name, $manufacturer, $year, $class_type, $condition, $engine_type, $color, $amt_paid, $descript, $index);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}
?>
<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      ...
    </div>
  </div>
</div>
</body>
</html>
