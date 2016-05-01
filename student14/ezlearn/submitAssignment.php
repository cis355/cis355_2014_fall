<?php
	// Start the session
	session_start();
	
	// Get the assignment heading
	$heading = $_POST['headingSubmit'];
	
	// Get the assignment title
	$title = $_POST['titleSubmit'];
	
	$filename = $_FILES["fileUpload"]["name"];
	
	// Set the target
	$targetFile = "DATA/assignments/" . $heading . "/" . $title . "/" . $_SESSION['username'] . "/" . $filename;
	
	// Create all directories needed to post the assignment
	if( !is_dir("DATA/assignments") )
	{
		mkdir("DATA/assignments");
	}
	
	if ( !is_dir("DATA/assignments/" . $heading) )
	{
		mkdir("DATA/assignments/" . $heading );
	}
	
	if (!is_dir("DATA/assignments/" . $heading . "/" . $title) )
	{
		mkdir("DATA/assignments/" . $heading . "/" . $title);
	}
	
	if (!is_dir("DATA/assignments/" . $heading . "/" . $title . "/" . $_SESSION['username']) )
	{
		mkdir("DATA/assignments/" . $heading . "/" . $title . "/" . $_SESSION['username']);
	}
	
	// Move the uploaded file into the
	move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $targetFile);
	
	// Relocate
	header("Location: assignments.php");
	exit;
?>
