<?php
ini_set("session.cookie_domain", ".cis355.com");
session_start();

ini_set('display_errors', 1);
error_reporting(e_all);

//---------- display (echo) html head and link to bootstrap css ----------
echo '<!DOCTYPE html><html> 
	 <head>
	 <title>Golf_Course_Score.php</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
     </head><body>';

//---------- set connection variables ------------------------------------
$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="LDC_ScoreTable";

$mysqli = new mysqli($hostname, $username, $password, $dbname);

$userSelection 		= 0;
$firstCall 			= 1; // first time program is called
$insertSelected 	= 2; // after user clicked insertSelected button on list 
$updateSelected 	= 3; // after user clicked updateSelected button on list 
$deleteSelected 	= 4; // after user clicked deleteSelected button on list 
$insertCompleted 	= 5; // after user clicked insertSubmit button on form
$updateCompleted 	= 6; // after user clicked updateSubmit button on form
$deleteCompleted 	= 7; // after user clicked deleteSubmit button on form

$Score_id 			= $_POST['Score_id'];
$Hole1 				= $_POST['Hole1'];
$Hole2 				= $_POST['Hole2'];
$Hole3 				= $_POST['Hole3'];
$Hole4 				= $_POST['Hole4'];
$Hole5 				= $_POST['Hole5'];
$Hole6 				= $_POST['Hole6'];
$Hole7 				= $_POST['Hole7'];
$Hole8 				= $_POST['Hole8'];
$Hole9 				= $_POST['Hole9'];
$Hole10				= $_POST['Hole10'];
$Hole11				= $_POST['Hole11'];
$Hole12				= $_POST['Hole12'];
$Hole13				= $_POST['Hole13'];
$Hole14				= $_POST['Hole14'];
$Hole15				= $_POST['Hole15'];
$Hole16				= $_POST['Hole16'];
$Hole17				= $_POST['Hole17'];
$Hole18				= $_POST['Hole18'];
$Total				= $_POST['Total'];

//---------- determine what user clicked ----------
$userSelection = $firstCall;
if( isset( $_POST['insertSelected'] ) ) $userSelection = $insertSelected;
if( isset( $_POST['updateSelected'] ) ) $userSelection = $updateSelected;
if( isset( $_POST['deleteSelected'] ) ) $userSelection = $deleteSelected;
if( isset( $_POST['insertCompleted'] ) ) $userSelection = $insertCompleted;
if( isset( $_POST['updateCompleted'] ) ) $userSelection = $updateCompleted;
if( isset( $_POST['deleteCompleted'] ) ) $userSelection = $deleteCompleted;

	switch( $userSelection ):
	    case $firstCall: 
			displayHTMLHead($mysqli);
			loadTable($mysqli);
			break;		
		case $insertSelected:
			displayHTMLHead($mysqli);
		    showInsertForm($mysqli);
			break;
/*		case $updateSelected :
			displayHTMLHead($mysqli);
		    showUpdateForm($mysqli);
			break;
		case $deleteSelected:        // currently no confirmation form is displayed on delete
			deleteRecord($mysqli);   // selecting delete immediately deetes the record
			displayHTMLHead($mysqli);
			$msg = 'record deleted';
			showList($mysqli, $msg);
			break;
			*/
		case $insertCompleted: 
//		    insertRecord($mysqli);
//			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
//			displayHTMLHead($mysqli);
//			$msg = 'record inserted';
//			showList($mysqli, $msg);
			break;
			/*
		case $updateCompleted:
		    updateRecord($mysqli);
			header("Location: " . $_SERVER['REQUEST_URI']); // redirect
			displayHTMLHead($mysqli);
			$msg = 'record updated';
			showList($mysqli, $msg);
			break;
		case $deleteCompleted:        // this case never occurs (possible upgrade later?)
			$msg = 'record deleted';  
			showList($mysqli, $msg);
			break;
			*/
	endswitch;		

# ========== FUNCTIONS ========================================================
#----------- displayHTMLHead -------------------------------------------------------
function displayHTMLHead($mysqli)
{
 echo '<meta charset="utf-8">
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
			Welcome <b>' .$_SESSION['user']. '</b>!</p>
					
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
         <div class="row">';
}

function loadTable($mysqli)
{
	global $usertable;

    // display html table column headings
	echo 	'<div class="col-md-10">
			<form action="Scores.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px;
			box-shadow: 2px 2px 5px;">
			<tr><td colspan="22" style="text-align: center; border-radius: 5px;
			color: green; background-color:#333333;">
			<h2 style="color: white;">Users Golf Score Table</h2></td></tr>
			<tr style="font-weight:80; font-size:20px;">
			<td>ID</td><td>1</td><td>2</td><td>3</td><td>4</td>
			<td>5</td><td>6</td><td>7</td><td>8</td><td>9</td>
			<td>10</td><td>11</td><td>12</td><td>13</td><td>14</td>
			<td>15</td><td>16</td><td>17</td><td>18</td><td>Total</td>
			</div>';
			
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
	  if($_SESSION['scoreId'] != "")
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
		 
	echo	'</div>
			</body>
			</html>';
}

# ---------- populateTable --------------------------------------------------------
function populateTable($mysqli)
{
 global $usertable;
echo $_SESSION['scoreId'];
 if($result = $mysqli->query("SELECT * FROM $usertable WHERE scoreId = ".$_SESSION['scoreId'].""))
  {
   while($row = $result->fetch_row())
	{
	 echo '<tr><td>' . $row[1] . '</td><td>' . $row[2] .
		  '</td><td>' . $row[3] . '</td><td>' . $row[4] .
		  '</td><td>' . $row[5] . '</td><td>' . $row[6] .
		  '</td><td>' . $row[7] . '</td><td>' . $row[8] .
		  '</td><td>' . $row[9] . '</td><td>' . $row[10] .
		  '</td><td>' . $row[11] . '</td><td>' . $row[12] .
		  '</td><td>' . $row[13] . '</td><td>' . $row[14] .
		  '</td><td>' . $row[15] . '</td><td>' . $row[16] .
		  '</td><td>' . $row[17] . '</td><td>' . $row[18] .
		  '</td><td>' . $row[19] . '</td><td>' . $row[20];
				 
	 echo '<td><input style="margin-left: 10px;" type="submit" name="deleteSelected" class="btn btn-danger"
					  value="Delete" onclick="setHid(' . $row[0] .')" /></td>'; 
					  
	 echo  '<td><input style="margin-left: 10px;" type="submit" name="updateSelected" class="btn btn-primary"
					  value="Update" onclick="setUid(' . $row[0] . ');" /></td></tr>';
	}
  }
	$result->close();
	
}

function showInsertForm($mysqli);
{
    echo '<div class="col-md-4">
	<form name="basic" method="POST" action="table07.php" onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;">
			<h2>Golf Course Score Form</h2></td></tr>
			<tr><td>ID: </td><td><input type="edit" name="scoreId" value="" size="30"></td></tr>
			<tr><td>Hole1: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole2: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole3: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole4: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole5: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole6: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole7: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole8: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole9: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole10: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole11: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole12: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole13: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole14: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole15: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole16: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole17: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Hole18: </td><td><input type="edit" name="hole1" value="" size="30"></td></tr>
			<tr><td>Total: </td><td><input type="edit" name="total" value="" size="30"></td></tr>';
			
	echo '<input type="hidden" name="index" value="' . $row[0] . '">
	      <input type="hidden" id="hLoc" name = "location_id" value="' . $row[6] . '">
		  <script>
			document.getElementById("loc").selectedIndex = ' . $row[6] .' - 1; 
			function setLocId()
			{
			var selectBox = document.getElementById("loc");
			document.getElementById("hLoc").value = selectBox.options[selectBox.selectedIndex].value;
			}
		
			</script>';
	
	echo  '<tr><td>ScoreId: </td><td><input type="text" name="scoreId" value="'. $_SESSION["scoreId"] .'" size="7" readonly></td></tr>
		   <tr><td><input type="submit" name="insertCompleted" class="btn btn-success" value="Add Entry"></td>
		   <td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		   </table><a href="Scores.php" class="btn btn-primary">Display Database</a></form></div>';
}




?>