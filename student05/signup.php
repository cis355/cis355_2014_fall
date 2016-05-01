<?php
	// Start the sessions
	ini_set("session.cookie_domain", ".cis355.com");
	session_start();

	
	if ($_SESSION["user"] != "")
	{
		header('Location: project5.php');
		echo $_SESSION["user"];
		exit;
	}
	
	// Required Database Information
	$hostname="localhost";
	$username="cabrown3";
	$password="tastepass";
	$dbname="tastebuds";
	
	
	// Set a mysqli object
	$mysqli = new mysqli($hostname, $username, $password, $dbname);	
	
	if (isset($_POST['signupSubmit']))
	{	
		$email = $_POST['username'];
		$password = $_POST['password'];
		$nonesong = 41;
		
		$sql = "INSERT INTO users (user_id, email, password_hash) VALUES (NULL, ?, ?)";
		
		// Init statement 
		$stmt = $mysqli->stmt_init();
		/* Notice the two ? in values, these will be bound parameters*/
		if($stmt = $mysqli->prepare($sql))
		{
			/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
			$stmt->bind_param('ss', $email, $password);

			/* execute prepared statement */
			if ($stmt->execute())
			{
				
			}
			
			/* close statment */
			$stmt->close();
			
		}
		
		if($result = $mysqli->query("SELECT * FROM users WHERE email = '".$email."' AND password_hash = '".$password."' "))
		{
		    $row = $result->fetch_row();
			$newident = $row[0];
			echo 'WE GOT THE IDENT';
			//var_dump($newident);
			//die();
		}
		
		$sql2 = $mysqli->stmt_init();
		// Init statement 
		/* Notice the two ? in values, these will be bound parameters*/
		if($stmt2 = $mysqli->prepare("INSERT INTO topsongs(user_id) VALUES(?)"))
		{echo 'GOT INTO PREPARE IF STATEMENT KKKKKKKKKKKK';
			/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
			$stmt2->bind_param('i', $newident);

			/* execute prepared statement */
			if ($stmt2->execute())
			{
				echo 'EXECUTED *************';
			}
			
			/* close statment */
			$stmt2->close();
			
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Taste Budz</title>
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
	<style>
		.borderless tbody tr td, .borderless thead tr th {
			border: none;
		}
	</style>
	

</head>

<body>
	<div class="col-md-12" style="background-color: black; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="project5.php"><img src="tblogo.jpg" style="margin-top: 5px;"></a>
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
	
	<div class="col-md-4">
	
	</div>
	
	<div class="col-md-4">
	<br>
	<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
		<div class="panel-heading"><b>Sign Up</b></div>
		<div class="panel-body">
			<form method="POST" action="signup.php">
				<table class="table borderless">
					<tr><td style="vertical-align: middle;"><b>Email Address: </b></td><td><input id="uname" type="text" name="username" class="form-control" placeholder="Email" onkeyup="checkPassword();"></td><td></td></tr>
					<tr><td style="vertical-align: middle;"><b>Password: </b></td><td><input type="password" id="password" name="password" class="form-control" placeholder="Password" onkeyup="checkPassword();"></td><td></td></tr>
					<tr><td style="vertical-align: middle;"><b>Retype Password: </b></td><td><input type="password" id="retype" name="retype" class="form-control" placeholder="Retype Password" onkeyup="checkPassword();"></td><td style="vertical-align: middle;"><span id="check" class=""></span></td></tr>
					<tr><td colspan="3"><br><input id="submitButton" type="submit" name="signupSubmit" class="form-control btn btn-success" value="Submit"></td></tr>
				</table>
				
			</form>
		</div>
	</div>
	<a href="project5.php" style="text-decoration: none;"><span class="glyphicon glyphicon-arrow-left" style="padding-right:3px;"></span> Back to Home</a>
	</div>
	
	<div class="col-md-4">
	
	</div>
	
	<script>
		document.getElementById("submitButton").disabled = true;
		function checkPassword()
		{
			var pass1 = document.getElementById("password").value;
			var pass2 = document.getElementById("retype").value;
			var uname = document.getElementById("uname").value;
			
			if (pass1 == "" || pass2 == "")
			{
				document.getElementById("check").className = "";
			}
			
			else
			{
				if(pass1 == pass2)
				{
					document.getElementById("check").style = "color: green;";
					document.getElementById("check").className = "glyphicon glyphicon-ok";
					
					if (uname != "")
					{
						document.getElementById("submitButton").disabled = false;
					}
					
					else
					{
						document.getElementById("submitButton").disabled = true;
					}
				}
				
				else
				{
					document.getElementById("check").style = "color: red;";
					document.getElementById("check").className = "glyphicon glyphicon-remove";
					document.getElementById("submitButton").disabled = true;
				}
			}
		}
	</script>
</body>
</html>
