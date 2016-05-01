<?php
	include "functions.php";
	$mysqli = ConnectToDb("ezlearn");
	
	$id = $_GET['id'];
	
	DeleteAnnouncement($mysqli, $id);
	
	$mysqli->close();
	
	header("Location: home.php");
	exit;
?>
	
	