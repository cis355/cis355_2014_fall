<?php
	// Include the functions file
	include "functions.php";
	
	// Set the title, text and announcement id
	$title = $_POST['title'];
	$text = $_POST['HideMe'];
	$id = $_POST["annId"];
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Update the announcement
	UpdateAnnouncement($mysqli, $title, $text, $id);
	
	// Relocate to home
	header('Location: home.php');
	exit;
?>
