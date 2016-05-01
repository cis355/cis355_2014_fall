<?php

//Keeps session variables across multiple domains.
ini_set("session.cookie_domain", ".cis355.com");

//This function will display the Look What I Paid sign in banner.
displayHTMLHead();

echo '<br>
	<H3>  Nathan Whitfield</h3>
	<div class="col-md-12">
	<p>Hello! I am a third year Computer Science student at Saginaw Valley State University. I have lived
	in Saginaw for the last few years now and am currently working as a technician at SAMSA Inc. This site 
	is for my CIS 355 Server Side Web Development class. </p>
	
	<br>
	
	<p> My table, the Cellphones Database, will allow you to add, update, delete, and search for entries in the 
	table as long as you are signed into our site. If you are not signed in, you will still be able to search 
	and view, but none of the other fun things.
	</p>
	</div>
	<br><br>
	<a href="table19.php" class="btn btn-primary">Return</a>';
	
# ---------- displayHTMLHead -----------------------------------------------------
function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table19.php</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
	
echo '<div class="col-md-12" style="background-color: tan; border-bottom: 
    2px solid black; box-shadow: 3px 3px 5px #888888;">
	<a href="../student14/landing.php"><img src="../student14/LWIP_logo.png" style="margin-top: 5px;"></a>';
if ($_SESSION["user"] != '')
{
	$user = $_SESSION['user'];
	echo '<p style="font-size:18px; float: right; margin-top: 40px; 
	    margin-right: 20px;">Welcome <b>' .	$user . '</b>!</p>';
}
else
{
	echo '<form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" 
	    action="../student14/login.php">
		<input type="text" size="9" name="username" class="form-control" placeholder="Username">
		<input type="password" size="9" name="password" class="form-control" placeholder="Password">
		<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
	    </form>';
}
echo '<br><br></div>';
}

?>