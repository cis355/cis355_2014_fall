<?php
    ini_set("session.cookie_domain", ".cis355.com");
	
	// Start the session
	session_start();
	
	// Set an error message
	$error = "";
	
	// If the user pressed the Submit button
	if(isset($_POST['loginSubmit']))
	{
		// This will not be used if successful login
		$error = '<div class="alert alert-danger" role="alert"><b>Login Error:</b> Please enter a valid username and password.</div>';
		
		// Required Databsae Information
		$hostname="localhost";
		$username="student";
		$password="learn";
		$dbname="lesson01";
		
		// Entered user information
		$uname = $_POST['username'];
		$pass = $_POST['password'];
		
		// Session Information
		$dataID = "";
		$dataUname = "";
		$dataLocation = "";
		
		// Create a mysqli object
		$mysqli = new mysqli($hostname, $username, $password, $dbname);
		
		// Init statement
		$stmt = $mysqli->stmt_init();
		
		// Create query
		$sql = "SELECT user_id, email, location_id FROM nma_Users WHERE email = ? AND password_hash = ?";
		
		if($stmt = $mysqli->prepare($sql))
		{
            // Bind params
            $stmt->bind_param('ss', $uname, $pass);

			// Execute statement
            if($stmt->execute())
            {
				// Bind query result
				$stmt->bind_result($dataID, $dataUname, $dataLocation);
				
				// Fetch the statement
				if ($stmt->fetch())
				{
					// Set SESSION vars
					$_SESSION["id"] = $dataID;
					$_SESSION["user"] = $dataUname;
					$_SESSION["location"] = $dataLocation;
					
					// Close statement and mysqli object
					$stmt->close();
					$mysqli->close();
					
					// If the user came from the login page, direct them to the landing page
					if ($_SERVER['HTTP_REFERER'] == "http://cis355.com/student03/Login.php")
					{
						// Relocate to landing page
						header('Location: landing.php');
						exit;
					}
					
					else
					{
						// Relocate to landing page
						header('Location: '. $_SERVER['HTTP_REFERER']);
						exit;
					}
				}
				
			}
                      
            // Close statement
			$stmt->close();
		}
		$mysqli->close();
	}
?>

<body>
	<div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="landing.php"><img src="LWIP_logo.png" style="margin-top: 5px;"></a>
		<br>
		<br>
	</div>
	<div class="col-md-4"></div>	
	<div class="col-md-4" style="margin-top: 40px;">

		<br/>
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Login</b></div>
				<div class="panel-body">
				<?php
					echo '<form method="POST" action="login.php">
					<input type="text" size="10" name="username" class="form-control" value="'. $uname .'" placeholder="Username">
					<input type="password" size="10" name="password" style="margin-top: 5px;" class="form-control" placeholder="Password"><br>
					'.$error.'
					<button type="submit" name="loginSubmit" style="width: 100%;" class="btn btn-success">Submit</button>
				</form>';
				?>
				</div>
		</div>
		<a href="landing.php" style="text-decoration: none;"><span class="glyphicon glyphicon-arrow-left" style="padding-right:3px;"></span> Back to Home</a>
		
	</div>
	<div class="col-md-4"></div>
	</div>
	</center>
	</body>
	</html>	
	
	echo'
<!DOCTYPE html>
<!-- saved from url=(0039)http://getbootstrap.com/examples/cover/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">

    <title>Rent a Guitar</title>

    <!-- Bootstrap core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">';
	
	echo'<!-- Custom styles for this template -->
    <link href="http://cis355.com/student03/Login.css" rel="stylesheet">

    <!-- Just for debugging purposes. -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./index_files/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

   <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">Rent a Guitar</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li><a href="http://cis355.com/student03/RentaGuitar.php">Home</a></li>
                  <li><a id="myLink" href="?run=search">Search</a></li>
                  <li><a id="myLink" href="?run=about">About</a></li>
				  <li class="active"><a id="myLink" href="?run=login">Log In</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading"></h1>
            <p class="lead"></p>
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>Cover template by <a href="http://getbootstrap.com/">Bootstrap</a>.</p>
            </div>
          </div>

        </div>';
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Login</b></div>
				<div class="panel-body">
				<?php
					echo '<form method="POST" action="Login.php">
					<input type="text" size="10" name="username" class="form-control" value="'. $uname .'" placeholder="Username">
					<input type="password" size="10" name="password" style="margin-top: 5px;" class="form-control" placeholder="Password"><br>
					'.$error.'
					<button type="submit" name="loginSubmit" style="width: 100%;" class="btn btn-success">Submit</button>
				</form>';
				?>
				</div>
		</div>
		<a href="http://cis355.com/student03/RentaGuitar.php" style="text-decoration: none;"> Back to Home</a>
		
	</div>
      echo'</div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./index_files/jquery.min.js"></script>
    <script src="./index_files/bootstrap.min.js"></script>
    <script src="./index_files/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./index_files/ie10-viewport-bug-workaround.js"></script>
  

<div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" title="" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;" data-original-title="Copy to clipboard">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">
<param name="movie" value="/assets/flash/ZeroClipboard.swf?noCache=1415910539484">
<param name="allowScriptAccess" value="sameDomain"><param name="scale" value="exactfit"><param name="loop" value="false">
<param name="menu" value="false"><param name="quality" value="best"><param name="bgcolor" value="#ffffff">
<param name="wmode" value="transparent"><param name="flashvars" value="trustedOrigins=getbootstrap.com%2C%2F%2Fgetbootstrap.com%2Chttp%3A%2F%2Fgetbootstrap.com">
<embed src="/assets/flash/ZeroClipboard.swf?noCache=1415910539484" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="100%" height="100%" name="global-zeroclipboard-flash-bridge" 
allowscriptaccess="sameDomain" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" 
flashvars="trustedOrigins=getbootstrap.com%2C%2F%2Fgetbootstrap.com%2Chttp%3A%2F%2Fgetbootstrap.com" scale="exactfit">                
</object>
</div>
</body>
</html>';
		
		
