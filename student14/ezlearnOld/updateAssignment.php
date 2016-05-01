<?php

	include "functions.php";
	
	$mysqli = ConnectToDb("ezlearn");
	
	$title = $_POST['title'];
	$text = $_POST['hiddenAss'];
	$dateDue = $_POST['dateDue'];
	$id = $_POST['hiddenId'];

	
	UpdateAssignment($mysqli, $title, $text, $dateDue, $id);
	
	$mysqli->close();
	
	header("Location: viewAssignment.php?id=" . $id)
?>
