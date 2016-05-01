<?php
	include "functions.php";
	$mysqli = ConnectToDb("ezlearn");
	
	// Get all of the students changed information
	$id = $_POST['formId'];
	$fname = $_POST["formFname"];
	$lname = $_POST["formLname"];
	$username = $_POST["formUsername"];
	$password = $_POST["formPassword"];
	$admin = $_POST["formPermission"];
	
	// Check to see of the admin value changed
	if($admin == "Student") $admin = 0;
	else $admin = 1;
	
	// Update the student
	UpdateStudent($mysqli, $fname, $lname, $username, $password, $admin, $id);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate
	header("Location: classlist.php");
	exit;
?>
