<?php

	// Get the directory
	$dir = $_GET['dir'];
	$file = $_GET['f'];
	
	// Delete the file
	unlink("DATA/resources/" . $dir ."/" . $file);
	
	// Scan the directory
	$dirFiles = scandir("DATA/resources/" . $dir);
	
	// If the directory is empty
	if(count($dirFiles) <= 2)
	{
		// Remove the directory
		rmdir("DATA/resources/" . $dir);
	}
	
	// Relocate to resources
	header("Location: resources.php");
	
?>
