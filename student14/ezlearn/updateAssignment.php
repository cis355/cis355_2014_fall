<?php

	include "functions.php";
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Get the assignment information
	$title = $_POST['title'];
	$text = $_POST['hiddenAss'];
	$dateDue = $_POST['dateDue'];
	$id = $_POST['hiddenId'];

	// Update the assignment
	UpdateAssignment($mysqli, $title, $text, $dateDue, $id);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate
	header("Location: viewAssignment.php?id=" . $id)
?>
