<?php
	include "functions.php";
	$mysqli = ConnectToDb("ezlearn");
	
	$id = $_GET['id'];
	$user = $_GET['uname'];
	
	$cwd = getcwd();
	
	$path = "DATA/classlist/" . $user;
	
	unlink($path . "/pic.png");
	
	rmdir($path);
	
	DeleteStudent($mysqli, $id);
	
	$mysqli->close();
	
	header("Location: classlist.php");
	exit;
?>
	
	