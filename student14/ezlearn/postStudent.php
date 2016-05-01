<?php
	include "functions.php";
	$mysqli = ConnectToDb("ezlearn");
	
	// Get the student information
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$picture = $_FILES["pic"]["name"];
	
	// Get the file extension
	$ext = end((explode(".", $picture)));
	
	// Get the target path
	$target_path = "DATA/classlist/" . $username  . "/pic." . $ext;
	
	// Get the CWD
	$homeDir = getcwd();
	
	// Change to the DATA dir
	chdir("DATA");
	
	// If classlist doesnt exist, create the 
	if(!is_dir("classlist"))
		mkdir("classlist");
	
	chdir("classlist");
	// If the user doesnt exist, make the dir
	if(!is_dir($username))
		mkdir($username);
	
	// Change Dir to the home dir
	chdir($homeDir);
	
	// Move the picture to the students directory
	move_uploaded_file($_FILES['pic']['tmp_name'], $target_path);
	
	// If the choice was a student, set to student in DB
	if($_POST['admin'] == "Student")
	{
		$admin = 0;
	}
	else
	{
		$admin = 1;
	}
	
	// Insert the student into the DB
	InsertStudent($mysqli, $fname, $lname, $username, $password, $admin);
	
	// Close the DB
	$mysqli->close();
	
	// Relocate
	header("Location: classlist.php");
	exit;
?>
