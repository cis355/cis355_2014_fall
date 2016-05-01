<?php
	ini_set("session.cookie_domain", ".cis355.com");
			
	// Start the session
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>View Item</title>	
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
		<?php
			
			// Assign database parameters
			$hostname = "localhost";
			$username = "user01";
			$password = "cis355lwip";
			$dbname = "lwip";
			$usertable = "table13";
			
			// Create new db object
			$db = new mysqli($hostname, $username, $password, $dbname);
			
			// Test database connection
			if ($db->connect_errno) 
			{
				die('Unable to connect to database [' . $mysqli->connect_error. ']');
				exit();
			}

			$vwId = $_POST['alter'];
			
			// Assign & execute query
			$sql = "SELECT * FROM $usertable WHERE id = $vwId";
			$result = $db->query($sql);
			
			// Display table
			echo "
				<div class='panel panel-danger' style='width:1200px; box-shadow: 5px 5px 7px #888888; margin-top: 50px;'>
				<div class='panel-heading'>
					<h3 class='panel-title'>View Item</h3>
				</div>
				<div class='panel-body'>
					<table class='table table-hover' cellpadding=\"5\" cellspacing=\"0\">
						<thead>
							<th><strong>I.D.</strong></th>
							<th><strong>Type</strong></th>
							<th><strong>Brand</strong></th>
							<th><strong>Model</strong></th>
							<th><strong>Case Type</strong></th>
							<th><strong>Band Type</strong></th>
							<th><strong>Band Size</strong></th>
							<th><strong>Water Resistance</strong></th>
							<th><strong>Price</strong></th>
							<th><strong>Description</strong></th>
							<th><strong>User Id</strong></th>
							<th><strong>Location Id</strong></th>
						</thead>
						<tbody>";
			
			// Print each row of table
			$row = $result->fetch_array();
			
			echo "
				<tr>
				<td width=\"4%\">" .$row[0]."</td>
				<td width=\"8%\">".$row[1]."</td>
				<td width=\"8%\">".$row[2]."</td>
				<td width=\"8%\">".$row[3]."</td>
				<td width=\"8%\">".$row[4]."</td>
				<td width=\"8%\">".$row[5]."</td>
				<td width=\"8%\">" .$row[6]."</td>
				<td width=\"8%\">".$row[7]."</td>
				<td width=\"5%\">".$row[8]."</td>
				<td width=\"19%\">".$row[9]."</td>
				<td width=\"4%\">" .$row[10]."</td>
				<td width=\"4%\">" .$row[11]."</td>
				
				<td><a href='table13.php'><button class='btn-sm btn-default' type='submit';>
				<span class='glyphicon glyphicon-ok'></span></button></a></td>";
		?>
		</td></tr>
		</tbody></table>
		</div>
		</center>
	</body>
</html>