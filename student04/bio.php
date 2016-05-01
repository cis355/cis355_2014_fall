<?php

ini_set("session.cookie_domain", ".cis355.com");
		session_start();

echo '<!DOCTYPE html>
		<html> 
		<head>
		<title>Bio table04</title>
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
		3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
		3.2.0/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
		3.2.0/js/bootstrap.min.js">
		</script></head><body>
		<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="//www.cis355.com/student14/landing.php"><img src="http://cis355.com/student14/LWIP_logo.png" style="margin-top: 5px;"></a>';

	if ($_SESSION["user"] != '')
	{
		$user = $_SESSION['user'];
		echo '<p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p>';
	}
	else
	{
		echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="http://cis355.com/student14/login.php">
			  <input type="text" size="9" name="username" class="form-control" placeholder="Username">
			  <input type="password" size="9" name="password" class="form-control" placeholder="Password">
			  <button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
			  </form>';
	}

	echo '<br>
		  <br>
	      </div>';
		
echo '
<center><h1>Olivia Archambo</h1>
<h2>Table 04 LWIP</h2>
<h3>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</h3></center>
<center><p>I am a 4th year student at SVSU. <br>
My major is Computer Information Systems. <br>
This LWIP project was created during the Fall semester of 2014.</p>
<p>This table is used for displaying information about Compound Bows.
It helps users find the best price in the area, and allows users to
insert information of their own.</p></center>
<br><center><a href="http://www.cis355.com/student04/table04.php">Back to table</a></center>
</html>';

?>