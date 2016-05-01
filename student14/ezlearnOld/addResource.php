<?php
	// If the POST data was set
	if(isset($_POST["upload"]))
	{
		// Get the uploaded file name and extension
		$filename = $_FILES["uploadedFile"]["name"];
		$ext = end((explode(".", $filename)));
		
		// Get the title and heading of the resource
		$title = $_POST["title"];
		$heading = $_POST["heading"];
		
		// If the Resource header doesn't exist
		if(!is_dir("DATA/resources/" . $heading))
		{
			mkdir("DATA/resources/" . $heading);
		}
		
		// Get the target of the file
		$targetFile = "DATA/resources/" . $heading . "/" . $title . "." . $ext;
		
		// Move the file to the target
		move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetFile);
	}
	
	// Relocate
	header("Location: resources.php");
	exit;
?>