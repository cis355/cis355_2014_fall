<?php
	ini_set("session.cookie_domain", ".cis355.com");
	session_start();
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
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>

<body>
	<div class="col-md-12" style="background-color: black; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
		<a href="project5.php"><img src="tblogo.jpg" style="margin-top: 5px;"></a>
		<?php
			if ($_SESSION["user"] != '')
			{
				$user = $_SESSION['user'];
				echo '<font color="green"><p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">Welcome <b>' . $user . '</b>!</p></font>';
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
	<div class="col-md-12">
	<br/>
<?php

$hostname="localhost";
$username="cabrown3";
$password="tastepass";
$dbname="tastebuds";
$toptable="topsongs";
$songtable="song";
$personid = $_SESSION["id"];
// Inserted info for new song
$irank=$_POST["newrank"];
$isong=$_POST["newsong"];
$iart=$_POST["newart"];
// Rank to be deleted
$delrank;
$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);

if($mysqli)
{
	 
	$dTable = true;
	
	// If DELETE button was selected
	if($_POST["hid"] != "")
	{
		$delrank = $_POST['hid'];
		deleteRecord($mysqli);
		displayTable($mysqli);
		$dTable = false;
	}
	
	// If INSERT button was pushed, insert song into top 50 (and song database if song isn't already in song table)
	if(isset($_POST['submit']))
	{   
		$irank = $_POST['newrank'];
		$isong = $_POST['newsong'];
		$iart = $_POST['newart'];

		insertRecord($mysqli);
		displayTable($mysqli);
		$dTable = false;
	}
	
	// If VIEW button was pushed
	if (isset($_POST['viewItem']))
	{
		viewRecord($mysqli);
		$dTable = false;
	}
	
	// If true, display top 50
	if($dTable)
	{
		displayTable($mysqli);
	}
}

// Function for displaying the top 50 table
function displayTable($mysqli)
{
    // Table header
	echo 	'<div class="col-md-12"><form action="top50table.php" method="POST"><table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="11" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2 style="color: green;">Top 50 Songs</h2></td></tr>
			<tr><td>New Song Rank: <input type="edit" name="newrank" value="" size="3"></td>
	          <td>New Song: <input type="edit" name="newsong" value="" size="15"></td>
			  <td>New Song Artist: <input type="edit" name="newart" value="" size="15"></td>
	          <td><input type = "submit" value="Add Song" name="submit" class="btn btn-primary"></td>
			</tr>
			<tr style="font-weight:800; font-size:20px;"><td>Rank</td><td>Song</td><td>Artist</td><td></td></tr>';
			
	// Fill HTML table up with songs from user's top 50		
	populateTable($mysqli);

	
	
	echo    '</table><input type="hidden" id="hid" name="hid" value=""><input type="hidden" id="uid" name="uid" value=""></form>';
	
	// Javascript functions for setting delete and view numbers
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

// Attempt to connect to database
function checkConnect($mysqli)
{
    
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}

// Fill up the table to be displayed for the user's top 50
function populateTable($mysqli)
{
	global $usertable;
	global $personid;
	global $toptable;
	global $songtable;
	
	$currsongid;
	$currsongtitle;
	$currsongauthor;
	
	$songresult;
	$songrow;
	
	$rowI = 1;
	$rankI = 1;

	// If any songs were retrieved from the user's tpo 50 table
	if($result = $mysqli->query("SELECT * FROM $toptable WHERE $personid = user_id"))
	{
	    // While there are tuples received from the query
		while($row = $result->fetch_row())
		{
		    // While a user has songs initialized at the current rank
		    while($row[$rowI] != null)
		    {
			    // If anything was gotten from the song table
				if($songresult = $mysqli->query("SELECT * FROM $songtable WHERE song_id = $row[$rowI]"))
				{
				    // While there are tuples received from the query
					while($songrow = $songresult->fetch_row())
					{
                        // Display a song row
						echo '<tr><td>' . $rankI . '</td><td>' . $songrow[1] . '</td><td>' . $songrow[2] . '</td>';
						
						// If a valid song, then display delete and view buttons
						if($songrow[0] != 41)
						{
						  echo '<td style="width: 213px;"><input name="delete" type="submit" class="btn btn-danger" value="Delete" onclick="setHid(' . $rankI .')" /><input style="float: right;" name="viewItem" type="submit" class="btn btn-success" value="View" onclick="setUid(' . $songrow[0] .')" /></td></tr>';
				        }  
						else
						{
						  echo "<td></tr>";
						}
						  
						// Next rank  
					    $rankI++;
					}
					
					
				}
				$rowI++;
		    }	
		}
		
		
	}
	
	// If there were no songs in the user's top 50 table (no select queries were made)
	if($rankI > 1)
	{
	  $result->close();
	  $songresult->close();
	}
	
	// Display the rest of the songs that have not been initialized in one's top 50
	while($rankI < 51)
	{
	    echo '<tr><td>' . $rankI . '</td><td>None</td><td>None</td><td></td></tr>';
		$rankI++;
	}
	
}



// Insert a song into one's top 50 table
function insertRecord($mysqli)
{
    /* vars from the post data that we will use to bind */
    global $irank, $isong, $iart, $personid, $usertable, $toptable, $songtable;
    
	$swapRank;
	$swapSong;
	$swapArt;
	
	$existResult;
	$swapResult;
	
	$i = 1;
	$i2 = $irank;
	
	//+++++ INSERTING COMPLETELY NEW SONG INTO SONG TABLE ++++++
	$stmtCheck = $mysqli->query("SELECT * FROM $songtable WHERE songtitle ='".$isong."' AND songauthor ='".$iart."'");

	if($stmtCheck->num_rows != 0)
	{	}
	else
	{
    	/* Initialise the statement. */
		$stmtsong = $mysqli->stmt_init();
		/* Notice the two ? in values, these will be bound parameters*/
		if($stmtsong = $mysqli->prepare("INSERT INTO $songtable (songtitle,songauthor) VALUES (?, ?)"))
		{
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmtsong->bind_param('ss', $isong, $iart);

            /* execute prepared statement */
            $stmtsong->execute();
            /* close statment */
            $stmtsong->close();
		}
	}
	
	// Get id of song to insert
	$swapRank = $mysqli->query("SELECT song_id FROM $songtable WHERE songtitle ='".$isong."' AND songauthor ='".$iart."'");
	$sranksongid = $swapRank->fetch_row();
	
	// Prepare update statement to update ranked song in top 50
	$swapResult = $mysqli->stmt_init();
	$swapResult = $mysqli->prepare("UPDATE $toptable SET s".$irank." = $sranksongid[0] WHERE user_id = $personid");
	
	if($swapResult)
	{
	    $swapResult->bind_param('s', $irank);

		/* execute prepared statement */
		$swapResult->execute();
		/* close statment */
		$swapResult->close();
    }
	
}


// Remove a song from one's top 50 table
function deleteRecord($mysqli)
{
	/* vars from the post data that we will use to bind */
    global $irank, $isong, $iart, $personid, $usertable, $toptable, $songtable, $delrank;
    
    $deleteResult = $mysqli->stmt_init();
	$deleteResult = $mysqli->prepare("UPDATE $toptable SET s".$delrank." = '41' WHERE user_id = $personid");

	if($deleteResult)
	{
	    $deleteResult->bind_param('s', $delrank);

		/* execute prepared statement */
		$deleteResult->execute();
		/* close statment */
		$deleteResult->close();
    }
}



// Function for viewing a certain song
function viewRecord($mysqli)
{
    // ID of song to be viewed from view button
	$index = $_POST['uid'];
	global $songtable;
	
	// Prepare query for getting a certain song from the song table
	$result = $mysqli->query("SELECT * FROM $songtable WHERE song_id = $index");
	$topresult = $mysqli->query();
	
	// If anything was gotten from the query
	if($result)
	{
	    // While there are still rows received from the database query
		while($row = $result->fetch_row())
		{		
			echo '	<br>
					<div class="col-md-4">
					<form name="basic" method="POST" action="top50table.php">
						<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
							<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Song</h2></td></tr>
							<tr><td style="width: 100px;"><b>Song ID: </b></td><td>'. $row[0] .'</td></tr>
							<tr><td><b>Title: </b></td><td>' . $row[1] . '</td></tr>
							<tr><td><b>Artist: </b></td><td>' . $row[2] . '</td></tr>
						</table>
					</form>
					<a href="top50table.php" class="btn btn-primary">My Top 50 Songs</a>
				</div>';
		}
		$result->close();
	}
}

?>
</div>
</div>
</body>
</html>
