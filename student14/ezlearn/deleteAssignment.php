<?php
	// Include the functions file
	include "functions.php";
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Get the id
	$id = $_GET['id'];
	
	// Delete the assignment
	DeleteAssignment($mysqli, $id);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate to the assignments page
	header("Location: assignments.php");
	
?>
