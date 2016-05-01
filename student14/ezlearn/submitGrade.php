<?php
	include "functions.php";
	session_start();
	
	// If the user is not an admin, relocate
	if($_SESSION['admin'] === 0)
	{
		header("Location: grades.php");
		exit;
	}
	
	// Get the counter and assignment id
	$count = $_POST['counter'];
	$assign = $_POST['assId'];
	
	// Create the query
	$sql = "INSERT INTO zmm_grades VALUES ";
	
	// Loop thru the post data and insert them into the DB
	for ($i = 0; $i < $count; $i++)
	{
		$idNum = $_POST["id$i"];
		$score = $_POST["student$i"];
		
		$sql .= "(" . $idNum . ", " . $assign . ", " . $score . ")";
		
		if($i < $count - 1)
		{
			$sql .= ", ";
		}
	}
	
	// Connect to the DB
	$mysqli = ConnectToDB("ezlearn");
	
	// Run the query
	$result = $mysqli->query($sql);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate
	header("Location: grades.php");
	exit;
	
?>

