<!DOCTYPE html>
<html>
<!-- File originally created by: Zach Metiva
	 File edited and updated by: Nathan Whitfield
	 
	 
<!-- get css styling and set title -->
	<head>
	<title>Nathan Whitfield - Database Table</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
</head>
<body>
<?php

// Set connection variables
$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table19";


$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);

//Checks if the connection was successful
if($mysqli)
{
	//------- Get Records Process -----------
	//Will create the table if it does not exist, then displays any entries in the table.
	createTable($mysqli);
	ob_end_clean();  //this function  will clear the table that is currently on the page before displaying the other table. Clears anything between this function and ob_start()
	displayTable($mysqli);
	
	//Bring user to the insertForm if the add an entry button is pressed.
	if(isset($_POST['insert']))
	{
		ob_end_clean();
		insertForm();
	}
	
	//insert a record
	if(isset($_POST['submit']))
	{   
		$brand = $_POST['brand'];
		$model = $_POST['model'];
		$color = $_POST['color'];
		$cond = $_POST['cond'];
		$price = $_POST['price'];
		$descript = $_POST['descript'];
		
		createTable($mysqli);
		insertRecord($mysqli);
		ob_end_clean();
		displayTable($mysqli);
	}
	
	// -------- Update Records --------
	if($_POST['uid'] != "")
	{
		ob_end_clean();
		$index = $_POST['uid'];
		global $usertable;
		
		if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
		{
			//updateForm($mysqli);
			while($row = $result->fetch_row())
			{
				
				echo '	
						<br>
						<div class="col-md-4">
						<form name="basic" method="POST" action="table_Ex2.php">
						
							<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
								<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Cellphone Form</h2></td></tr>
								<tr><td>Type: </td><td>
									<select name="brand" id="brnd">
										<option value="">Select...</option>
										<option value="htc">HTC</option>
										<option value="samsung">Samsung</option>
										<option value="motorola">Motorola</option>
										<option value="lg">LG</option>
										<option value="apple">Apple Inc.</option>
										<option value="other">Other</option>
									</select>
								</td></tr>
								<tr><td>Model: </td><td><input type="edit" name="model" value="' . $row[2] . '" size="20"></td></tr>
								<tr><td>Color: </td><td><input type="edit" name="color" value="' . $row[3] . '" size="20"></td></tr>
								<tr><td>Condition: </td><td>
									<select name="cond" id="condit">
										<option value="">Select...</option>
										<option value="new">New</option>
										<option value="refurb">Refurbished</option>
										<option value="used">Used</option>
									</select>
								</td></tr>
								<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[5] . '" size="20"></td></tr>
								<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[6] . '</textarea></td></tr>
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
	
	//Update from update form
	if(isset($_POST['submitUpdate'])) 
	{
		$brand = $_POST['brand'];
		$model = $_POST['model'];
		$color = $_POST['color'];
		$cond = $_POST['cond'];
		$price = $_POST['price'];
		$descript = $_POST['descript'];
		$index = $_POST['index'];
		
		updateRecord($mysqli);
		ob_end_clean();
		displayTable($mysqli);
	}
	
	//If delete button pressed
	if($_POST["hid"] != "") //value of 'hid' is the id to delete
	{
		$delID = $_POST['hid']; // delID will be used to determine which record to delete in deleteRecord()
		deleteRecord($mysqli);
		ob_end_clean();
		displayTable($mysqli);
	}
}
// -----------End of Program----------


//Check connection to the database
function checkConnect($mysqli)
{
    
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}

//Function will display 
function displayTable($mysqli)
{
	ob_start(); // This function will create an object buffer that keeps track of everything printed to the screen that follows this function call.
	echo 	'<div class="col-md-12"><form action="table_Ex2.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="9" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: white;">Cellphone Database</h2></td></tr>
			<tr style="font-weight:800; font-size:20px;"><td>ID #</td><td>Brand</td><td>Model</td><td>Color</td><td>Condition</td><td>Price</td><td>Description</td></tr>';
	
	$result = $mysqli->query("SELECT COUNT (*) FROM $usertable"); // gets the row count and sets it to result
	$recordFetch = $mysqli->fetch_row(); //sets the number of records from the table to record count
	$recordCount = recordFetch[0]; //stores the number of records in the first row of the table
	$result->close();
	
	if($recordCount > 0)
	{
		populateTable($mysqli);
	}
	else
	{
		echo '<h3>No records in the database</h3>';
	}
	echo    '</table><input type="hidden" id="hid" name="hid" value=""><input type="hidden" id="uid" name="uid" value=""><input type="submit" name="insert" class="btn btn-primary" value="Add an Entry"></form>';
	
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

//This function is used to populate the table being displayed.
function populateTable($mysqli)
{
	global $usertable;
	
	// determines if there are records in the table. If there are any records, the records will be added to the table.
	if($result = $mysqli->query("select id from $usertable limit 1")) 
	{
		$i = 0;
	
		if($result = $mysqli->query("SELECT * FROM $usertable"))
		{
			while($row = $result->fetch_row())
			{
				echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . 
					'</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td>
					<td><input name="delete" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $row[0] .')" /></td> <td><input style="margin-left: 10px;" type="submit" name="update" class="btn btn-primary" value="Update" onclick="setUid(' . $row[0] . ');" /></td></tr>';
				$i++;
			}
		}	
		//$result.close();
	}
	
	//If there are not records selected a message will be displayed stating that the table has no records.
	else
	{
		echo '<div align="center"><h2>There are no records in this table.</h2></div>';
	}
	
}

//This function will be called to create a table.
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
	    $sql = "CREATE TABLE table19 (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "brand VARCHAR(20),";
	    $sql .= "model VARCHAR(20),";
	    $sql .= "color VARCHAR(20),";
	    $sql .= "cond VARCHAR(20),";
	    $sql .= "price INTEGER(20),";
	    $sql .= "descript VARCHAR(200)";
	    $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            /* execute prepared statement */
            $stmt->execute();
        }
    }  
}

//This function will be used to display the insert form.
function insertForm()
{
	echo  '<div class="col-md-4">
	<form name="basic" method="POST" action="table_Ex2.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Cellphone Form</h2></td></tr>
			<tr><td>Brand: </td><td>
				<select name="brand">
					<option value="">Select...</option>
					<option value="htc">HTC</option>
					<option value="samsung">Samsung</option>
					<option value="motorola">Motorola</option>
					<option value="lg">LG</option>
					<option value="apple">Apple Inc.</option>
					<option value="other">Other</option>
				</select>
			</td></tr>
			<tr><td>Model: </td><td><input type="edit" name="model" value="" size="30"></td></tr>
			<tr><td>Color: </td><td><input type="edit" name="color" value="" size="20"></td></tr>
			<tr><td>Condition: </td><td>
				<select name="cond">
					<option value="">Select...</option>
					<option value="new">New</option>
					<option value="refurb">Refurbished</option>
					<option value="used">Used</option>
				</select>
			</td></tr>
			<tr><td>Price: </td><td><input type="edit" name="price" value="" size="20"></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>
			<tr><td><input type="submit" name="submit" class="btn btn-success" value="Add Entry"></td>
				<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table>
		
		<a href="table_Ex2.php" class="btn btn-primary">Display Database</a>
	</form>
	</div>';
}

//This function is used to insert a record into the table.
function insertRecord($mysqli)
{
    /* vars from the post data that we will use to bind */
    global $brand, $model, $color, $cond, $price, $descript, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (brand,model,color,cond,price,descript) VALUES (?, ?, ?, ?, ?, ?)"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('ssssis', $brand, $model, $color, $cond, $price, $descript);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}

function updateForm($mysqli)
{
	while($row = $result->fetch_row())
			{
				
				echo '	
						<br>
						<div class="col-md-4">
						<form name="basic" method="POST" action="table_Ex2.php">
						
							<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
								<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Cellphone Form</h2></td></tr>
								<tr><td>Type: </td><td>';
				
				selectBrand($mysqli);
								/*
									<select name="brand" id="brnd">
										<option value="">Select...</option>
										<option value="htc">HTC</option>
										<option value="samsung" selected="selected">Samsung</option>
										<option value="motorola">Motorola</option>
										<option value="lg">LG</option>
										<option value="apple">Apple Inc.</option>
										<option value="other">Other</option>
									</select>
									*/
									
				echo			'</td></tr>
								<tr><td>Model: </td><td><input type="edit" name="model" value="' . $row[2] . '" size="20"></td></tr>
								<tr><td>Color: </td><td><input type="edit" name="color" value="' . $row[3] . '" size="20"></td></tr>
								<tr><td>Condition: </td><td> ';
								
				selectCond($mysqli);
				
									/*<select name="cond" id="condit">
										<option value="">Select...</option>
										<option value="new">New</option>
										<option value="refurb">Refurbished</option>
										<option value="used">Used</option>
									</select>
									*/
									
				echo '			</td></tr>
								<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[5] . '" size="20"></td></tr>
								<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3">' . $row[6] . '</textarea></td></tr>
								<tr><td><input type="submit" name="submitUpdate" class="btn btn-primary" value="Update Entry"></td>
									<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
							</table>
							<input type="hidden" name="index" value="' . $row[0] . '">
						</form>
					</div>';
			}
}

//This function will update a record
function updateRecord($mysqli)
{
	 global $brand, $model, $color, $cond, $price, $descript, $index, $usertable;
    
    /* Initialise the statement. */
    $stmt = $mysqli->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $mysqli->prepare("UPDATE $usertable SET brand = ?, model = ?, color = ?, cond = ?, price = ?, descript = ? WHERE id = ?"))
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('ssssisi', $brand, $model, $color, $cond, $price, $descript, $index);

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}

function selectBrand($mysqli)
{
	switch ($row[1])
	{
		case 'htc':
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="htc" selected="selected">HTC</option>
				<option value="samsung">Samsung</option>
				<option value="motorola">Motorola</option>
				<option value="lg">LG</option>
				<option value="apple">Apple Inc.</option>
				<option value="other">Other</option>
			</select>';
			break;
		case "samsung":
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="htc">HTC</option>
				<option value="samsung" selected="selected">Samsung</option>
				<option value="motorola">Motorola</option>
				<option value="lg">LG</option>
				<option value="apple">Apple Inc.</option>
				<option value="other">Other</option>
			</select>';
			break;
		case "motorola":
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="htc">HTC</option>
				<option value="samsung" selected="selected">Samsung</option>
				<option value="motorola">Motorola</option>
				<option value="lg">LG</option>
				<option value="apple">Apple Inc.</option>
				<option value="other">Other</option>
			</select>';
			break;
		case "lg":
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="htc">HTC</option>
				<option value="samsung" selected="selected">Samsung</option>
				<option value="motorola">Motorola</option>
				<option value="lg">LG</option>
				<option value="apple">Apple Inc.</option>
				<option value="other">Other</option>
			</select>';
			break;
		case "apple":
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="htc">HTC</option>
				<option value="samsung" selected="selected">Samsung</option>
				<option value="motorola">Motorola</option>
				<option value="lg">LG</option>
				<option value="apple">Apple Inc.</option>
				<option value="other">Other</option>
			</select>';
			break;
		case "other":
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="htc">HTC</option>
				<option value="samsung" selected="selected">Samsung</option>
				<option value="motorola">Motorola</option>
				<option value="lg">LG</option>
				<option value="apple">Apple Inc.</option>
				<option value="other">Other</option>
			</select>';
			break;
	}
}

function selectCond($mysqli)
{
	switch ($row[4])
	{
		case "new":
			echo '<select name="cond"> 
				<option value="">Select...</option>
				<option value="new" selected>New</option>
				<option value="used">Used</option>
				<option value="refurb">Refurbished</option>
			</select>';
			break;
		case "used":
			echo '<select name="cond"> 
				<option value="">Select...</option>
				<option value="new" selected>New</option>
				<option value="used">Used</option>
				<option value="refurb">Refurbished</option>
			</select>';
			break;
		case "refurb":
			echo '<select name="brand" id="brnd"> 
				<option value="">Select...</option>
				<option value="new" selected>New</option>
				<option value="used">Used</option>
				<option value="refurb">Refurbished</option>
			</select>';
			break;
	}
}

//This function will be used to delete records from the table.
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
            $stmt->bind_param('i', $delID);

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