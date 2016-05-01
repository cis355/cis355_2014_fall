<?php

ini_set("session.cookie_domain", ".cis355.com");
session_start();

echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table18.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js">
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
<div align="center"><h1>Shawn Wagner</h1>
<h2>Bio</h2>
<p>Shawn Wagner - Fourth Year</br>
   Computer Information Systems Major</p></div>
<a href="http://www.cis355.com/student18/table18.php">Back</a>
'
?>