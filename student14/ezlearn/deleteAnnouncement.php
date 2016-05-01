<?php
	// Include the functions file
	include "functions.php";
	
	// Open the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Get the id
	$id = $_GET['id'];
	
	// Delete the announcement
	DeleteAnnouncement($mysqli, $id);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate home
	header("Location: home.php");
	exit;
?>
	
	
