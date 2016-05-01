<?php
	// Include the functions file
	include "functions.php";
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Get the student id and username
	$id = $_GET['id'];
	$user = $_GET['uname'];
	
	// Get the CWD
	$cwd = getcwd();
	
	// Get the students directory
	$path = "DATA/classlist/" . $user;
	
	// Remove the pic
	unlink($path . "/pic.png");
	
	// remove the directory
	rmdir($path);
	
	// Delete the student
	DeleteStudent($mysqli, $id);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate
	header("Location: classlist.php");
	exit;
?>
	
	
