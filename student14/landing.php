<?php
    ini_set("session.cookie_domain", ".cis355.com");
	// Start the sessions
	session_start();

	// Required Database Information
	$hostname="localhost";
	$username="user01";
	$password="cis355lwip";
	$dbname="lwip";
	
	// Set a mysqli object
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Look What I Paid</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
	
	<!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
	<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="landing.php"><img src="LWIP_logo.png" style="margin-top: 5px;"></a>
		<?php
			if ($_SESSION["user"] != '')
			{
				$user = $_SESSION['user'];
				echo '<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p>';
			}
			
			else
			{
				 echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="login.php">
			<input type="text" size="9" name="username" class="form-control" placeholder="Username">
			<input type="password" size="9" name="password" class="form-control" placeholder="Password">
			<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
		</form>';
			}
		?>
		<br>
		<br>
	</div>

	<div class="col-md-2" >
		<br>
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Account Information</b></div>
				<!-- <center><img src="http://media-cache-ak0.pinimg.com/236x/83/dc/42/83dc427ed701bafbf1ca2bc5554af0d5.jpg" style="width: 150px; height: 150px;"></center> -->
				<div class="panel-body">
					<a href="myaccount.php">My Account</a><br/>
					<a href="#">Add an Item</a><br/>
					<hr>
					<?php
						if($_SESSION['user'] == '')
						{
							echo "<a href='login.php'>Log in</a><br/>\n<hr>\n";
							echo "<a href='signup.php'>Sign Up</a><br/>\n";
						}
						else
							echo "<a href='logout.php'>Log Off</a><br/>\n";
					?>
				</div>
		</div>	
	</div>
	
	<div class="col-md-8">
		<br>
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Item Categories</b></div>
				<div class="panel-body">
					<?php
						GetItems();
					?>
				</div>
		</div>
	</div>
	
	<div class="col-md-2">
		<br>
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Locations</b></div>
				<div class="panel-body">
					<?php
						GetLocations($mysqli);
					?>
				</div>
		</div>
	
	</div>
<?php $mysqli->close(); ?>
</body>
</html>

<?php
	/* Get Locations - Gets the locations from the database */
	function GetLocations($mysqli)
	{
		// Init statement
		$stmt = $mysqli->stmt_init();
		
		// Set Select query
		$sql = "SELECT name FROM locations";
		
		// Init location variable
		$location = "";
		
		// If the statement was prepared
		if($stmt = $mysqli->prepare($sql))
		{
            // Execute statement
            if($stmt->execute())
            {
				// Bind query result
				$stmt->bind_result($location);
				
				// Fetch the statement
				while ($stmt->fetch())
				{
					// Output the locations
					echo "<a href='#'>" . $location . "</a><br/>";
				}
			}
		}
	}
	
	/* Get Items - Creates the links to each tableXX.php and populates them into a table */
	function GetItems()
	{
		// Init Variables
		$tableIndex = 0;	// The index of the current table
		$column = "";		// The column information for the table
		
		$items = array("House Insurance", "Video Games", "Guitars", "Compound Bows", "Power Tools", "Books", "Video Games", "Bicycles", "Vehicles", "Jet Skis", "Jewelry", "Unknown", "Watches", "Musical Instruments", "Cameras", "Game Consoles", "Deep Freezers", "Computer Components", "Cellphones", "Recreational Vehicles", "MotorCycle", "Boats", "Unknown");
		
		// Create the table
		echo "
					<table class='table table-bordered' style='border-raduis: 3px; width:100%;'>\n";
		
		// Loop through the tables
		while ( $tableIndex < 23)
		{
			// Inserts a '0' to all of the tables less than 10
			if ($tableIndex < 9)
				$column = "<td><a href='http://www.cis355.com/student0" . ($tableIndex+1) . "/table0". ($tableIndex+1) .".php'>" . $items[$tableIndex] . " (Table 0" . ($tableIndex+1). ")</a></td>";
			
			// Otherwise, insert the respective number
			else
				$column = "<td><a href='http://www.cis355.com/student" . ($tableIndex+1) . "/table". ($tableIndex+1) .".php'>" . $items[$tableIndex] . " (Table " . ($tableIndex+1) . ")</a></td>";
			
			// Begins a new row every third column and ends a row
			switch($tableIndex % 3)
			{
				case 0: echo "\t\t\t\t\t\t<tr>"; echo $column; break;
				case 2: echo $column; echo "</tr>\n"; break;
				default: echo $column; break;
			}
			
			// Increment the table index
			$tableIndex++;
		}
		
		// Close the table
		echo "\n\t\t\t\t\t</table>\n";
	}

?>
