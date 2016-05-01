<?php
	// Start the sessions
	session_start();

	// Incldue the functions file
	include "functions.php";
	
	// Get the discussion info
	$heading = $_POST['heading'];
	$title = $_POST['title'];
	$message = $_POST['message'];
	$datePosted = $_POST['datePosted'];
	$stuId = $_SESSION['student_id']; 
	
	// Connect to DB
	$mysqli = ConnectToDb("ezlearn");

	// Insert the discussion intpo the DB
	$sql = "INSERT INTO zmm_discussions (heading, title, message, datePosted, student_id) VALUES(?, ?, ?, ?, ?)";
	
	// Init the statement
	$statement = $mysqli->stmt_init();
	
	// Excute the SQL
	if($statement = $mysqli->prepare($sql))
	{
		$statement->bind_param('ssssi', $heading, $title, $message, $datePosted, $stuId);
		if($statement->execute())
		{
			$statement->close();
		}
	}
	
	// Relocate
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
?>
