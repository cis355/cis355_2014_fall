<?php
	include "functions.php";
	
	$mysqli = ConnectToDb("ezlearn");
	
	$id = $_GET['id'];
	
	DeleteAssignment($mysqli, $id);
	
	$mysqli->close();
	
	header("Location: assignments.php");
	
?>
