<?php

    ini_set("session.cookie_domain", ".cis355.com");
	
	// Start the session
	session_start();
	
	if ($_SESSION["user"] == "")
	{
		// Relocate to landing page
		header('Location: login.php');
		exit;
	}
	
	// Required Database Information
	$hostname="localhost";
	$username="cabrown3";
	$password="tastepass";
	$dbname="tastebuds";
	
	// Set a mysqli object
	$mysqli = new mysqli($hostname, $username, $password, $dbname);

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
		<br>
		<br>
	</div>
	<div class="col-md-2">
		<br/>
		<center><img src="http://m.c.lnkd.licdn.com/mpr/mpr/p/8/005/04d/318/2e7fcd5.jpg" style="width: 150px; height: 150px;"></center><br>
			<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
				<div class="panel-heading"><b><?php echo $_SESSION['user'] ?></b></div>
				<div class="panel-body">
					<a href="#" onclick="changeData('username');">Change Username</a><br/>
					<a href="#">Change Password</a>
					<hr>
					<a href="logout.php">Log Out</a>
					
				</div>
			</div>
	</div>
	<div class="col-md-8" style="margin-top: 40px;">
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Account Information</b></div>
				<div class="panel-body">
				<table class="table borderless">
					<tr><td style="width: 20%;"><b>User ID: </b></td><td> <?php echo $_SESSION['id']; ?></td></tr>
					<tr><td style="text-align: middle;"><b>Username: </b></td><td><span id="staticUsername"><?php echo $_SESSION['user']; ?></span><input id="inputUsername" type="text" style="display: none; width: 70%;" class="form-control" value= "<?php echo $_SESSION["user"]; ?>" > <input id="inputUsernameButton" style="display: none;"type="button" class="btn btn-success" value="Change" ></td></tr>
					<tr><td colspan="2"><hr></td></tr>
				</table>
				</div>
		</div>
	</div>
	<div class="col-md-2"></div>
<script>

function changeData(data)
{
	if(data == "username")
	{
		document.getElementById("staticUsername").style.display = "none";
		document.getElementById("inputUsername").style.display = "inherit";
		document.getElementById("inputUsernameButton").style.display = "inherit";
	}
}


</script>		
</body>
</html>
