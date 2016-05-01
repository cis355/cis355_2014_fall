<?php
ini_set("session.cookie_domain", ".cis355.com");
session_start();

ini_set('display_errors', 1);
error_reporting(e_all);

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="LDC_SetupTable";

$mysqli = new mysqli($hostname, $username, $password, $dbname);

$scoreId 			= $_POST['scoreId'];
$date				= $_POST['date'];
$userId				= $_POST['userId'];
  
if(isset($_POST['viewSelected']))
 {
  $_SESSION['scoreId'] = $_POST['uid'];
  header("Location: //cis355.com/student07/Scores.php");
 }
?>

<html> 
<head>
	 <title>Golf_Course_Score.php</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
	 
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="description" content="">
     <meta name="author" content="">

     <style>body {padding-top: 70px;}</style>
	
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	 
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
			<p style="font-size:12px; float: right; margin-top: 40px; margin-right: 20px;">
			Welcome <b>
			
			<?php
			echo $_SESSION['user'];
			?>
			
			</b>!</p>
					
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class = "navbar-brand">Golf Course Score</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="//www.cis355.com/student07/login.php?loggingOut=true">Log Out</a>
					</li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
<?php
 if(isset($_POST['insertSelected']))
  {
   insertForm($mysqli);
  }
 else if(isset($_POST['insertData']))
  {
   insert($mysqli);
   loadTable($mysqli);
  }
 else
  { 
   loadTable($mysqli);
  }
?>
		</div>
    </div>
</body>
</html>

<?php
# ========== FUNCTIONS ========================================================
# ---------- loginTable --------------------------------------------------------
function loadTable($mysqli)
{
	global $usertable;

    // display html table column headings
	echo 	'<div class="col-md-7">
			<form action="Setup.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px;
			box-shadow: 2px 2px 5px;">
			<tr><td colspan="3" style="text-align: center; border-radius: 5px;
			color: black; background-color:#333333;">
			<h2 style="color: white;">Users Golf Score Table</h2></td></tr>
			<tr style="font-weight:80; font-size:20px;">
			<td>Date</td><td>User_ID</td>';

			
 $countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable"); // get count of records in mysql table
  $countfetch  = $countresult->fetch_row();
 $countvalue  = $countfetch[0];
 $countresult->close();
 
 if( $countvalue > 0 )
	{

			populateTable($mysqli); // populate html table, from data in mysql table
			echo   '</table><input type="hidden" id="hid" name="hid" value="">
				    <input type="hidden" id="uid" name="uid" value="">';

	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}
	
	if($_SESSION['user'] != "")
	 {
	
		 echo   '<input type="submit" name="insertSelected" value="Add an Entry" class="btn btn-primary">';
     }
	// add JavaScript functions to end of html body section
	// note: "hid" is id of item to be deleted; "uid" is id of item to be updated.
	// see also: populateTable function
	echo "</form></div><script>
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

# ---------- populateTable --------------------------------------------------------
function populateTable($mysqli)
{
	global $usertable;
	
	if($result = $mysqli->query("SELECT * FROM $usertable WHERE userId = ".$_SESSION['id'].""))
	{
		while($row = $result->fetch_row())
		{
			echo '<tr><td>' . $row[1] . '</td><td>' . $row[2];
				 
			echo  '</td><td><input name="viewSelected" type="submit" class="btn btn-success" value="View"
			       onclick="setUid(' . $row[0] . ');"/></td></tr>';
		}
	}
	$result->close();
}

# ---------- insertForm --------------------------------------------------------
function insertForm($mysqli)
{
     echo '<div class="col-md-4">
	<form name="basic" method="POST" action="Setup.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Golf Course Setup</h2></td></tr>
			<tr><td>Date: </td><td><input type="edit" name="date" value="" size="30"></td></tr>
		   <tr><td>User_id: </td><td><input type="text" name="userId" value="'. $_SESSION["id"] .'" size="7" readonly></td></tr>
		   <tr><td><input type="submit" name="insertData" class="btn btn-success" value="Add Entry"></td>
		   <td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		   </table><a href="Setup.php" class="btn btn-primary">Display Database</a></form></div>';
}

# ---------- insert --------------------------------------------------------
function insert($mysqli)
{
 global $date, $userId, $usertable;
 
 $userId = $_SESSION["id"];
 
 $stmt = $mysqli->stmt_init();
 
 if($stmt = $mysqli->prepare("INSERT INTO $usertable (date, userId) VALUES (?,?)"));
  {
   $stmt->bind_param('si', $date, $userId);
   $stmt->execute();
   $stmt->close();  
  }
}
  
# ---------- setScoreId --------------------------------------------------------
?>