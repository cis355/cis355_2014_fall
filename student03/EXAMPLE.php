<!DOCTYPE html>
<html>
	<head>
	<title>Zach Metiva - Database Table</title>
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
$usertable="table14";


$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);

if($mysqli)
{
	$dTable = true;
	if($_POST["hid"] != "")
	{
		$newID = $_POST['hid'];
		deleteRecord($mysqli);
		displayTable($mysqli);
		$dTable = false;
	}

	if($_POST['uid'] != "")
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
			$dTable = false;
		}
	}
	
	if(isset($_POST['submitUpdate']))
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
		displayTable($mysqli);
		$dTable = false;
	}
	
	if(isset($_POST['submit']))
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
	echo 	'<div class="col-md-12"><form action="table14.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: white;">Musical Instrument Database</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Type</td><td>Brand</td><td>Model</td><td>Color:</td><td>String/Wind</td><td>Price</td><td>Description</td><td></td></tr>';
	
	populateTable($mysqli);

	echo    '</table><input type="hidden" id="hid" name="hid" value=""><input type="hidden" id="uid" name="uid" value=""></form><a href="table14.html" class="btn btn-primary">Add an Entry</a></div>';
	
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
