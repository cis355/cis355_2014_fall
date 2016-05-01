<?php
	// If the upload button was pressed
	if(isset($_POST["upload"]))
	{
		// Get the filename and extension of the uploaded file
		$filename = $_FILES["uploadedFile"]["name"];
		$ext = end((explode(".", $filename)));
		
		// Get the heading and title of the resource
		$title = $_POST["title"];
		$heading = $_POST["heading"];
		
		// Create the directories 
		if(!is_dir("DATA/resources/" . $heading))
		{
			mkdir("DATA/resources/" . $heading);
		}
		
		
		$targetFile = "DATA/resources/" . $heading . "/" . $title . "." . $ext;
		
		// Move the uploaded file into the folder
		move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetFile);
	}
	
	// Relocate to the resources page
	header("Location: resources.php");
	exit;
?>