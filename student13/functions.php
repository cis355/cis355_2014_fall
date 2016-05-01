<?php
	
	/**********************************************************************/
	/* This function renders header HTML information and links to         */
	/* Bootstrap's core CSS.                                              */
	/**********************************************************************/
	
	function showHeader()
	{
		echo '
				<head>

				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="description" content="">
				<meta name="author" content="">

				<title>Guitar Shop 13</title>

				<!-- Bootstrap Core CSS -->
				<link href="css/bootstrap.min.css" rel="stylesheet">

				</head>
			';
	}
	
	/**********************************************************************/
	/* This function renders the navigation bar that is present on every  */
	/* page on the website. It is better to call this one function than   */
	/* to copy and paste a large amount of code on every page.            */
	/**********************************************************************/
	
	function showNavbar()
	{
		echo '
				<!-- Navigation -->
				<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
					<div class="container">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="home.php">Guitar Shop 13</a>
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
							';
							// If the user is logged in, display
							if ($_SESSION['user'] || $_SESSION['userId'])
							{
								echo '<li><a href="logout.php">Logout</a></li>';
							}
							else
							{
								echo '<li><a href="signUp.php">Sign up</a></li>';
									
							}
							
							echo '
							<li class="dropdown">
							  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Products <span class="caret"></span></a>
							  <ul class="dropdown-menu" role="menu">
								<li><a href="guitarInv.php?type=acoustic">Acoustic Guitars</a></li>
								<li><a href="guitarInv.php?type=guitars">Electric Guitars</a></li>
								<li><a href="guitarInv.php?type=basses">Bass Guitars</a></li>
							  </ul>
							</li>
							';
							// If the user is logged in, display
							if ($_SESSION['user'] || $_SESSION['userId'])
							{
								echo '<li> <a href="account.php">Account</a></li>';
								echo '<li><a href="shoppingCart.php">Shopping Cart <span class="glyphicon glyphicon-shopping-cart"></span></a></li>';	
							}
							
							echo
							'
							</ul>
							<!-- Log-in Form -->
							';
							// Greet user if logged in
							if ($_SESSION['user'] || $_SESSION['userId'])
							{
								echo '
									<div class="nav navbar-nav navbar-right">
										<h4 style="color:white">Hello, '.$_SESSION['user'].'!</h4>
									</div>';

							}
							// Else display the log-in options
							else
							{
								echo '
									<form class="navbar-form navbar-right" style="margin-top:5px;" method="POST" action="login.php">
										<input type="text" size="9" name="username" class="form-control" placeholder="Username">
										<input type="password" size="9" name="password" class="form-control" placeholder="Password">
										<button type="submit" name="loginSubmit" class="btn btn-default">Submit</button>
									</form>';
							}
							echo '
							</div>
							<!-- /.navbar-collapse -->
						</div>
						<!-- /.container -->
					</nav>
			';
	}

	/**********************************************************************/
	/* This function renders the HTML for the footer information present  */
	/* on every page of this website.                                     */
	/**********************************************************************/
	
	function showFooter()
	{
		echo '
			<div class="container">
				<hr>
				<!-- Footer -->
				<footer>
					<div class="row">
						<div class="col-lg-12">
							<p>CIS355 Final Project - Student 13</p>
						</div>
					</div>
				</footer>

			</div>
			<!-- /.container -->

			<!-- jQuery -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

			<!-- Bootstrap Core JavaScript -->
			<script src="js/bootstrap.min.js"></script>
			';
	}

	/**********************************************************************/
	/* This function connects to the database. It takes the db's table    */
	/* name as an arguement.                                              */
	/**********************************************************************/
	
	function dbConnect($table)
	{
		// Assign database parameters
		$hostname = "localhost";
		$username = "student";
		$password = "learn";
		$dbname = "lesson01";
		$usertable = $table;
		
		// Create new db object
		$db = new mysqli($hostname, $username, $password, $dbname);
		
		// Test database connection
		if ($db->connect_errno) 
		{
			die('Unable to connect to database [' . $mysqli->connect_error. ']');
			exit();
		}
		
		// Return mysqli object
		return $db;
	}

?>