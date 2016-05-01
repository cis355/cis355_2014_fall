<?php
    ini_set("session.cookie_domain", ".cis355.com");
	// Start the sessions
session_start();
	
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="RentaGuitar";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection

if ($mysqli)
{
	createTable($mysqli);
	
    $category 			= $_POST['category']; // if does not exist then value is ""
	$brand 				= $_POST['brand'];
	$quality 			= $_POST['quality'];
	$bodytype 			= $_POST['bodytype'];
	$fretboard 			= $_POST['fretboard'];
	$cutaway 			= $_POST['cutaway'];
	$price 				= $_POST['price'];
	$user_id			= $_POST['user_id'];
	$location_id		= $_POST['location_id'];
	
	if (isset($_POST['insertCompleted']))
	{
		insertRecord($mysqli);
	} 
	if (isset($_POST['viewSelected']))
	{
		displayMyGuitarView($mysqli);
	} 
	if (isset($_POST['loginSubmit']))
	{
		echo "TESTING";
	}
	
	if (isset($_GET['run'])) $linkchoice=$_GET['run'];
		else $linkchoice='';

	switch($linkchoice){

	case 'search' :
		displaySearch($mysqli); 
		break;
	case 'about' :
		displayAbout();
		break;
	case 'login' :
		displayLogin();
		break;
	case 'my-guitar':
		displayMyGuitarForm($mysqli);
		break;
	default :
		displayHTMLHead();
} 
	
} // ---------- end if ---------- and end main processing ----------

# ========== FUNCTIONS ========================================================

# ---------- checkConnect -----------------------------------------------------
function checkConnect($mysqli)
{
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}
# ---------- createTable ------------------------------------------------------
function createTable($mysqli)
{
    global $usertable;
    if($result = $mysqli->query("select id from $usertable limit 1"))
    {
        $row = $result->fetch_object();
		$id = $row->id;
        $result->close();
    }
    
    if(!$id)
    {
	    $sql = "CREATE TABLE RentaGuitar (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
	    $sql .= "category VARCHAR(50),";
	    $sql .= "brand VARCHAR(50),";
	    $sql .= "quality VARCHAR(50),";
	    $sql .= "bodytype VARCHAR(50),";
	    $sql .= "fretboard VARCHAR(50),";
	    $sql .= "cutaway VARCHAR(50),";
	    $sql .= "price VARCHAR(50),";
	    $sql .= "user_id INT,";
        $sql .= "location_id INT,";
	    $sql .= "FOREIGN KEY (user_id) REFERENCES nma_Users (user_id),";
        $sql .= "FOREIGN KEY (location_id) REFERENCES nma_Locations (location_id)";
        $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
    }
}

# ---------- showList --------------------------------------------------------
function showList($mysqli, $msg)
{
    // this function gathers records from a "mysql table" and builds an "html table"
	
	global $usertable;
	
		//show current user and location_id
	if ($_SESSION["user"] != ""){
		echo "You are logged in as user: ".$_SESSION["user"].
		     " location: ".$_SESSION["location"]."<br>";
	}
			 
    // display html table column headings
	echo 	'<div class="col-md-13">
			<form action="RentaGuitar.php" method="POST">
			<table class="table table-condensed" style="border: 3px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">

			<tr style="font-weight:800; font-size:20px;">
			<td>ID #</td><td>Category</td><td>Brand</td><td>Quality</td><td>Body Type</td><td>Fretboard</td>
			<td>Cutaway</td><td>Price</td><td>User</td><td>Location</td></td></tr>';
			

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable"); // get count of records in mysql table
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, else display "no records" message
	if( $countvalue > 0 )
	{
			populateTable($mysqli); // populate html table, from data in mysql table
	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}

	// add JavaScript functions to end of html body section
	// note: "hid" is id of item to be deleted; "uid" is id of item to be updated.
	// see also: populateTable function
	echo "<script>
			function setHid(num)
			{
				document.getElementById('hid').value = num;
		    }
		    function setUid(num)
			{
				document.getElementById('uid').value = num;
		    }
		 </script>";
		 
}

# ---------- populateTable ---------------------------------------------------
// populate html table, from data in mysql table
function populateTable($mysqli)
{
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . 
				$row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . 
				'</td><td>' . $row[5] . '</td><td>' . $row[6] . '</td><td>' . 
				$row[7] . '</td><td>' . $row[8] . '</td><td>' . $row[9] . '</td><td>' . $row[10];
				
				echo '</td><td><input style="margin-left: 10px;" type="submit"
					name="viewSelected" class="btn btn-success" value="View"
					onclick="setUid(' . $row[0] . ');" />'; 

					
			if ($_SESSION["id"]==$row[8]){
				echo '<input style="margin-left: 10px;" type="submit"
					name="deleteSelected" class="btn btn-danger" value="Delete"
					onclick="setHid(' . $row[0] .');" />'; 
				echo '<input style="margin-left: 10px;" type="submit"
					name="updateSelected" class="btn btn-primary" value="Update"
					onclick="setUid(' . $row[0] .');" />';
				}
		}
	}
	$result->close();
}

# -----------showLoginForm -----------------------------------------------------
function showLoginForm($mysqli)
{
	// If the user pressed the Submit button
	if(isset($_POST['loginSubmit']))
	{
		// This will not be used if successful login
		$error = '<div class="alert alert-danger" role="alert"><b>Login Error:</b> Please enter a valid username and password.</div>';
		
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
					if ($_SERVER['HTTP_REFERER'] == "http://cis355.com/student03/RentaGuitar.php?run=login")
					{
						// Relocate to landing page
						header('Location: http://cis355.com/student03/RentaGuitar.php?run=search');
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

}
# -----------displayHTMLHead ---------------------------------------------------
function displayHTMLHead()
{
echo '<!DOCTYPE html>
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
    <link href="http://cis355.com/student03/Home.css" rel="stylesheet">

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
                  <li class="active"><a href="http://cis355.com/student03/RentaGuitar.php">Home</a></li>
                  <li><a id="myLink" href="?run=search">Search</a></li>     
                  <li><a id="myLink" href="?run=about">About</a></li>
				  <li><a id="myLink" href="?run=login">Log In</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">Try before you buy.</h1>
            <p class="lead">Rent a Guitar is a website currently under construction!</p>
            <p>Thinking about getting your hands on a new guitar for that new gig/sound?</p>
							<p>Why not rent?</p>
							<p>This way, you can test the guitar on your own time and on your own equipment. 
							We offer a cheaper alternative to affording quality guitars.
							Just pick the amount of days you would wish to rent and we ship it straight to your home.
							Once the renting period ends, bring the guitar to one of our locations!</p>
							<p>You can even decide to buy!</p>

              <h4><a id="myLink" href="?run=login">Get Started</a></h4>
            </p>
          </div>';
		
          echo '<div class="mastfoot">
            <div class="inner">
              <p>Cover template by <a href="http://getbootstrap.com/">Bootstrap</a>.</p>
            </div>
          </div>

        </div>

      </div>

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
}

# -----------displaySearch ---------------------------------------------------
function displaySearch($mysqli)
{
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
    <link href="http://cis355.com/student03/Search.css" rel="stylesheet">
	
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
				  <li class="active"><a id="myLink" href="?run=search">Search</a></li>     
                  <li><a id="myLink" href="?run=about">About</a></li>
				  <li><a id="myLink" href="?run=login">Log In</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">Guitar Database</h1>
			<h4><a id="myLink" href="?run=my-guitar"><button type="submit" style="width: 40%;" class="btn btn-primary">List Your Guitar for Rent!</button></a></h4>
          </div>';
					  
          echo '<div class="mastfoot">
            <div class="inner">
              <p>Cover template by <a href="http://getbootstrap.com/">Bootstrap</a>.</p>
            </div>
          </div>
		  
 
        </div>';
		  showList($mysqli);
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
}

# -----------displayAbout ---------------------------------------------------
function displayAbout()
{
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
    <link href="http://cis355.com/student03/About.css" rel="stylesheet">

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
                  <li class="active"><a id="myLink" href="?run=about">About</a></li>
				  <li><a id="myLink" href="?run=login">Log In</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">Niklas Andersson</h1>
            <p class="lead">CIS 355</p>
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>Cover template by <a href="http://getbootstrap.com/">Bootstrap</a>.</p>
            </div>
          </div>

        </div>

      </div>

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
}

# -----------displayLogin ---------------------------------------------------
function displayLogin()
{
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
		showLoginForm($mysqli);
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
}

# -----------displayMyGuitarForm ---------------------------------------------------
function displayMyGuitarForm($mysqli)
{
    echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>Rent a Guitar</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js">
	</script>
	</head>
	<body>
	<div class="col-md-4">
	<form name="basic" method="POST" action="RentaGuitar.php?run=search" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Guitar Information</h2></td></tr>
			
			<tr><td>Category:</td>
			<td><select name="category">
			<option></option>
			<option>Acoustic</option>
			<option>Electric</option>
			<option>Electric-Acoustic</option>
			</select></td></tr>
			
			<tr><td>Brand:</td>
			<td><select name="brand">
			<option></option>
			<option>Fender</option>
			<option>Ibanez</option>
			<option>Gibson</option>
			<option>Schecter</option>
			<option>Ephiphone</option>
			<option>Dean</option>
			<option>Gretsch</option>
			<option>PRS</option>
			<option>ESP</option>
			<option>Martin</option>
			</select></td></tr>
			
			<tr><td>Quality:</td>
			<td><select name="quality">
			<option></option>
			<option>New</option>
			<option>Used</option>
			<option>Worn</option>
			<option>Blemished</option>
			<option>Vintage</option>
			</select></td></tr>
			
			<tr><td>Body Type:</td>
			<td><select name="bodytype">
			<option></option>
			<option>Solid</option>
			<option>Hollow</option>
			<option>Semi-Hollow:</option>
			</select></td></tr>
			
			<tr><td>Fretboard:</td>
			<td><select name="fretboard">
			<option></option>
			<option>Ebony</option>
			<option>Rosewood</option>
			<option>Maple</option>
			</select></td></tr>
			
			<tr><td>Cutaway:</td>
			<td><select name="cutaway">
			<option></option>
			<option>Single</option>
			<option>Double</option>
			<option>None</option>
			</select></td></tr>
			
			<tr><td>Price: </td><td><input type="edit" name="price" value="$" size="10"></td></tr>
			<tr><td>User: </td><td><input type="edit" name="user_id" value="'.$_SESSION["id"].'" size="10"></td></tr>';
	
            echo '<tr><td>Location ID: </td><td>';
            echo "<select class='form-control' name = 'location_id' id='location'>";

				if($sql_statement = $mysqli->query("SELECT * FROM nma_Locations"))
				{
					while($row = $sql_statement->fetch_object())
					{
						if($row->location_id === $location_id)
							echo"<option value='".$row->location_id. "' selected='selected'>".$row->name. "</option>";
						else
							echo "<option value='".$row->location_id. "' >".$row->name. "</option>";
					}
					$sql_statement->close();
				}
				else
					echo $mysqli->error;
            echo "</select>";
			echo '</td></tr>
			<tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
			<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
			</table><a href="http://cis355.com/student03/RentaGuitar.php?run=search" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- insertRecord --------------------------------------------------------------
function insertRecord($mysqli)
{
    global $category, $brand, $quality, $bodytype, $fretboard, $cutaway, $price, $user_id, $location_id, $usertable;
	
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO $usertable (category,brand,quality,bodytype,fretboard,cutaway,price, user_id, location_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
        // Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc.
		// bind_param replaces question mark(s) with contents of variable(s) to protect against sql injection
        $stmt->bind_param('sssssssii', $category, $brand, $quality, $bodytype, $fretboard, $cutaway, $price, $user_id, $location_id);
        $stmt->execute();
        $stmt->close();
    }

}

# -----------displayMyGuitarView ---------------------------------------------------
function displayMyGuitarView($mysqli)
{
	$index = $_POST['uid'];  // "uid" is id of db record to be updated (from the list)
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE id = $index"))
	{
		while($row = $result->fetch_row())
		{
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="table03.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Guitar Information</h2></td></tr>
							<tr><td>Category: </td><td><input type="edit" name="category" value="'. $row[1] .'" size="50" readonly></td></tr>
							<tr><td>Brand: </td><td><input type="edit" name="brand" value="' . $row[2] . '" size="50" readonly></td></tr>
							<tr><td>Quality: </td><td><input type="edit" name="quality" value="' . $row[3] . '" size="50" readonly></td></tr>
							<tr><td>Body Type: </td><td><input type="edit" name="bodytype" value="' . $row[4] . '" size="50" readonly></td></tr>
							<tr><td>Fretboard: </td><td><input type="edit" name="fretboard" value="' . $row[5] . '" size="50" readonly></td></tr>
							<tr><td>Cutaway: </td><td><input type="edit" name="cutaway" value="' . $row[6] . '" size="50" readonly></td></tr>
							<tr><td>Price: </td><td><input type="edit" name="price" value="' . $row[7] . '" size="50" readonly></td></tr>
							<tr><td>User: </td><td><input type="edit" name="user_id" value="' . $row[8] . '" size="50" readonly></td></tr>
							<tr><td>Location: </td><td><input type="edit" name="location_id" value="' . $row[9] . '" size="50" readonly></td></tr>
							<tr><td><input type="submit" name="viewCompleted" class="btn btn-primary" value="Back"></td></tr>
						</table>
						<input type="hidden" name="uid" value="' . $row[0] . '">
					</form>
				</div>';
		}
		$result->close();
	}
}
?>
