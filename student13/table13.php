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
		<title>Watches</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
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

			// Assign & execute query
			$sql = "SELECT * FROM $usertable";
			$result = $db->query($sql);
			
			// Display table
			echo "
			<form name='basic' id='genForm' method='POST' action='/'>
				<div class='panel panel-danger' style='width:1200px; box-shadow: 5px 5px 7px #888888; margin-top: 50px;'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Watch Table</h3>
				</div>
				<div class='panel-body'>
					<table class='table table-hover' cellpadding=\"5\" cellspacing=\"0\">
						<thead>
							<th><strong>Type</strong></th>
							<th><strong>Brand</strong></th>
							<th><strong>Model</strong></th>
							<th><strong>Case Type</strong></th>
							<th><strong>Band Type</strong></th>
							<th><strong>Band Size</strong></th>
							<th><strong>Water Resistance</strong></th>
							<th><strong>Price</strong></th>
						</thead>
						<tbody>";
			
			// Print each row of table
			while ($row = $result->fetch_array())
			{
				echo "
					<tr>
					<td width=\"12%\">".$row[1]."</td>
					<td width=\"12%\">".$row[2]."</td>
					<td width=\"12%\">".$row[3]."</td>
					<td width=\"12%\">".$row[4]."</td>
					<td width=\"12%\">".$row[5]."</td>
					<td width=\"12%\">" .$row[6]."</td>
					<td width=\"12%\">".$row[7]."</td>
					<td width=\"12%\">".$row[8]."</td>
					
					<td><button class='btn-sm btn-success' type='submit' onclick='changeForm(\"view\",".$row[0].");'>
					<span class='glyphicon glyphicon-eye-open'></span></button></td>";
					
					if ($_SESSION['id'] == $row[10])
					{
						echo"
						<td><button class='btn-sm btn-default' type='submit' onclick='changeForm(\"delete\",".$row[0].");'>
						<span class='glyphicon glyphicon-trash'></span></button></td>
						<td><button class='btn-sm btn-default' type='submit' onclick='changeForm(\"update\",".$row[0].");'>
						<span class='glyphicon glyphicon-pencil'></span></button>
						</td></tr>";
					}
			}
		?>
		<tr><td colspan='15'>
		<?php
		if ($_SESSION['user'] != "")
		{
			echo "
			<div>
				<button class='btn-sm btn-default' style='float:right;' type='submit' onclick='changeForm(\"add\",-1);'>
				<span class='glyphicon glyphicon-plus'></span> Add Watch </button>
			</div>";
		}
		?>
		</td></tr>
		</tbody></table>
		<input name='alter' type='hidden' id='itemID' value=''></div></div>
		</div>
		</center>
		</form>
		<a href="bio.html" style="margin-left:50px; color: black;">About Me</a>
	</body>
</html>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
<script src='js/bootstrap.min.js'></script>
<script>
	
	/************************************************************/
	/*	changeForm function										*/
	/************************************************************/
	
	function changeForm(frmAction, idNum)
	{
		var frm = document.getElementById('genForm');
		
		if (frmAction == "delete")
		{
			frm.action = "delete.php";
		}
		else if (frmAction == "update")
		{
			frm.action = "update.php";
		}
		else if (frmAction == "view")
		{
			frm.action = "view.php";
		}
		else
		{
			frm.action = "add.php";
		}
		document.getElementById('itemID').value = idNum;
	}
	
</script>