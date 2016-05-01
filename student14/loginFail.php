<?php
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
	<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="landing.php"><img src="LWIP_logo.png"></a>
		<?php
			if ($_SESSION["user"] != '')
			{
				$user = $_SESSION['user'];
				echo '<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p>';
			}
			
			else
			{
				 echo '<form class="navbar-form navbar-right" method="POST" action="login.php">
			<br>
			<input type="text" size="10" name="username" class="form-control" placeholder="Username">
			<input type="password" size="10" name="password" class="form-control" placeholder="Password">
			<button type="submit" name="loginSubmit" class="btn btn-default">Submit</button>
		</form>';
			}
		?>
		<br>
		<br>
	</div>
	
	
	<div class="col-md-12">
	<center>
		<h2>Invalid Username/Password</h2>
		<div class="panel panel-default" style="width:40%; box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Login</b></div>
				<div class="panel-body">
				<?php
					echo '<form method="POST" action="login.php">
					<input type="text" size="10" name="username" class="form-control" placeholder="Username">
					<input type="password" size="10" name="password" style="margin-top: 5px;" class="form-control" placeholder="Password"><br>
					<button type="submit" name="loginSubmit" style="width: 100%;" class="btn btn-default">Submit</button>
				</form>';
				?>
				</div>
			</div>
		</div>
	</center>
	</div>
	
	
	
	
	
	
	