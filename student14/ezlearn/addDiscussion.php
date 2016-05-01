<?php

	// Include the functions file
	include "functions.php";
	
	// Get the heading and title of the discussion
	$heading = $_POST['heading'];
	$title = $_POST['title'];
	
	// Create a Insert query
	$sql = "INSERT INTO zmm_discussion_base VALUES(? ,?)";
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Init the statement
	$statement = $mysqli->stmt_init();
	
	// Prepare and Excute the statement
	if($statement = $mysqli->prepare($sql))
	{
		$statement->bind_param('ss', $heading, $title);
		$statement->execute();
		$statement->close();
	}
	
	// Relocate to the discussion page
	header("Location: discussions.php");
	exit;
?>