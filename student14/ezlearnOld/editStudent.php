<?php
	include "functions.php";
	$mysqli = ConnectToDb("ezlearn");
	
	$id = $_POST['formId'];
	$fname = $_POST["formFname"];
	$lname = $_POST["formLname"];
	$username = $_POST["formUsername"];
	$password = $_POST["formPassword"];
	$admin = $_POST["formPermission"];
	
	if($admin == "Student") $admin = 0;
	else $admin = 1;
	
	UpdateStudent($mysqli, $fname, $lname, $username, $password, $admin, $id);
	
	$mysqli->close();
	
	header("Location: classlist.php");
	exit;
?>