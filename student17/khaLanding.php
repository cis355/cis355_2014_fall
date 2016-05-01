<?php
    ini_set("session.cookie_domain", ".cis355.com");
	// Start the sessions
	session_start();
	
	// Required Database Information
	$hostname="localhost";
	$username="student";
	$password="learn";
	$dbname="lesson01";
	
	// Set a mysqli object
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Kitty Haven Adoption</title>
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
	<div class="col-md-12" style="background-color: Cream; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="khaLanding.php"><img src="KHA_logo.png" style="margin-top: 5px;"> </a>
		<?php
			echo '<br> You are logged in as: '. $_SESSION["id"];

			if ($_SESSION["user"] != '')
			{
				$user = $_SESSION['user'];
				echo '<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p>';
			}
			
			else
			{
				 echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="khaLogin.php">
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
			<div class="panel-heading"><b>Account Information</b></div><br>
				<!-- <center><img src="http://media-cache-ak0.pinimg.com/236x/83/dc/42/83dc427ed701bafbf1ca2bc5554af0d5.jpg" style="width: 150px; height: 150px;"></center> -->
				<div class="panel-body">
					<?php
						if($_SESSION['user'] == '')
						{
							echo "<a href='khaLogin.php'>Log in</a><br/>\n<hr>\n";
							echo "<a href='khaSignUp.php'>Sign Up</a><br/>\n";
						}
						else
							echo "<a href='logout.php'>Log Off</a><br/>\n";
					?>
				</div>
		</div>	
	</div>
	
<?php
	echo'<div><div style="float:center;margin: 40px; font-size:42px; font-family: Times New Roman, Times, serif;" >
		<a href="kha.php" style="color:black;">View Kitty Haven Adoption Database</a></div></div></body></html>';

?>

<?php $mysqli->close(); ?>
</body>
</html>



