<?php
	include "functions.php";
	$mysqli = ConnectToDb("ezlearn");
	
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$picture = $_FILES["pic"]["name"];
	
	$ext = end((explode(".", $picture)));
	
	$target_path = "DATA/classlist/" . $username  . "/pic." . $ext;
		
	$homeDir = getcwd();
	
	chdir("DATA");
	
	if(!is_dir("classlist"))
		mkdir("classlist");
	
	chdir("classlist");
	
	if(!is_dir($username))
		mkdir($username);
	
	chdir($homeDir);
	
	
	move_uploaded_file($_FILES['pic']['tmp_name'], $target_path);
	
	if($_POST['admin'] == "Student")
	{
		$admin = 0;
	}
	else
	{
		$admin = 1;
	}
	
	InsertStudent($mysqli, $fname, $lname, $username, $password, $admin);
	
	$mysqli->close();
	
	header("Location: classlist.php");
	exit;
?>