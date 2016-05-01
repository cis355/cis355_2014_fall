<?php
	
	ini_set("session.cookie_domain", ".cis355.com");
			
	// Start the session
	session_start();
	
	$userId = $_SESSION['id'];
	$locId = $_SESSION['location'];
	
	$hostname = "localhost";
	$username = "user01";
	$password = "cis355lwip";
	$dbname = "lwip";
	$usertable = "table13";

	$db = mysqli_connect($hostname,$username,$password,$dbname);
	
	if (isset($_POST['submit']))
	{
		$type = $_POST['type'];
		$brand = $_POST['brand'];
		$model = $_POST['model'];
		$caseType = $_POST['caseType'];
		$bandType = $_POST['bandType'];
		$bandSize = $_POST['bandSize'];
		$waterResist = $_POST['waterResist'];
		$price = $_POST['price'];
		$descript = $_POST['descript'];
		$locId = $_POST['locId'];
		
		$sql = "INSERT INTO table13 VALUES (NULL, '$type', '$brand', '$model', 
		'$caseType', '$bandType', '$bandSize', '$waterResist', '$price', '$descript', $userId, $locId)";
		
		$result = mysqli_query($db,$sql)or die(mysqli_error($db));
		
		if ($result)
		{
			header("Location: table13.php");
			exit;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Add a Watch to LWIP</title>	
	</head>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <body>
	<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
	<a href="http://www.cis355.com/student14/landing.php"><img src="LWIP_logo.png" style="margin-top: 5px;"></a>
	<?php
		if ($_SESSION["user"] != '')
		{
			$user = $_SESSION['user'];
			echo '<p style="font-size:18px; color:black; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p>';
		}

		else
		{
			 echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="http://www.cis355.com/student14/login.php">
			<input type="text" size="9" name="username" class="form-control" placeholder="Username">
			<input type="password" size="9" name="password" class="form-control" placeholder="Password">
			<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
			</form>';
		}
	?>
	<br>
	<br>
	</div>
	<center>
	<div class='col-md-12'>
	<div class="panel panel-danger" style="width:500px; box-shadow: 5px 5px 7px #888888; height:580px; margin-top:30px;">
		<div class="panel-heading">
			<h3 class="panel-title" align="center">Add Watch</h3>
		</div>
		<div class="panel-body">
			<form name='basic' method='POST' action='add.php';'>
				<table class ="table" border='0' cellpadding='5' cellspacing='0'>
					<thead><td>Type: </td><td colspan="2"><input type='edit' name='type' value='' size='20'></td></thead>
					<tr><td>Brand: </td><td colspan="2"><input type='edit' name='brand' value='' size='20'></td></tr>
					<tr><td>Model: </td><td colspan="2"><input type='edit' name='model' value='' size='30'></td></tr>
					<tr><td>Case Type: </td><td colspan="2"><input type='edit' name='caseType' value='' size='20'></td></tr>
					<tr><td>Band Type: </td><td colspan="2"><input type='edit' name='bandType' value='' size='30'></td></tr>
					<tr><td>Band Size: </td><td colspan="2"><input type='edit' name='bandSize' value='' size='20'></td></tr>
					<tr><td>Water Resistance: </td><td colspan="2"><input type='edit' name='waterResist' value='' size='20'></td></tr>
					<tr><td>Price: </td><td colspan="2"><input type='edit' name='price' value='' size='20'></td></tr>
					<tr><td>Description: </td><td colspan="2"><textarea style="resize:none; width:100%;" name='descript' size='100'></textarea></td></tr>
					<tr><td>Location: </td><td colspan="2">
						<select id="selection" class="form-control" onchange="selectLoc();">
						<?php
							$stmt = $db->stmt_init();
							$sql = "SELECT * FROM locations";
					
							$dbId = "";
							$location = "";
								
								
							// If the statement was prepared
							if($stmt = $db->prepare($sql))
							{
									// Execute statement
								if($stmt->execute())
								{
										// Bind query result
										$stmt->bind_result($dbId, $location);
										
										// Fetch the statement
									while ($stmt->fetch())
									{
										// Output the locations
										echo "<option value='" . $dbId ."'>" . $location . "</option>";
									}
								}
							}        
						?>
						</select></td></tr>
					<input type="hidden" id="lid" value="1" name="locId">
					<td><a class="btn btn-default" href="table13.php"><span class="glyphicon glyphicon-arrow-left"></span> Back</a></td>
					<td align="right"><button type='submit' class="btn btn-default" name='submit' value='Submit'><span class="glyphicon glyphicon-ok"></span> Submit</button></td></tr>
				</table>
			</form>
		</div>
		</div>
	</div>
	</center>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>

<script>
	function selectLoc()
	{
		var selection = document.getElementById("selection");
		var index = selection.options[selection.selectedIndex].value;
		
		document.getElementById("lid").value = index;
	}
</script>