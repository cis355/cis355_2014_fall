<?php
	include "functions.php";
	
	$heading = $_POST["formHeading"];
	$title = $_POST["formTitle"];
	$text = $_POST["formText"];
	$dateDue = $_POST["formDue"];
	$datePosted = $_POST["formPosted"];
	
	$mysqli = ConnectToDb("ezlearn");
	
	InsertAssignment($mysqli, $heading, $title, $text, $dateDue, $datePosted);
	
	$mysqli->close();
	
	header("Location: assignments.php");
	
?>
