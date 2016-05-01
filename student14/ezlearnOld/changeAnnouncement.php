<?php
	include "functions.php";
	
	$title = $_POST['title'];
	$text = $_POST['HideMe'];
	$id = $_POST["annId"];
	
	$mysqli = ConnectToDb("ezlearn");
	UpdateAnnouncement($mysqli, $title, $text, $id);
	
	header('Location: home.php');
	exit;
?>
