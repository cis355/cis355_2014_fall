<?php
	include "functions.php";
	
	// Check to see if the button was pressed
	if(isset($_POST['post']))
	{
		// Get the assignment information
		$assignText = $_POST['aText'];
		$assignTit = $_POST['aTitle'];
		$assignDate = $_POST['aDate'];
		
		// Connect to the DB
		$mysqli = ConnectToDb("ezlearn");
		
		// Insert the annoucement
		InsertAnnouncement($mysqli, $assignTit, $assignText, $assignDate);
		
		// Close the DB
		$mysqli->close();

		// Relocate
		header("Location: home.php");
		exit;
	}
?>
