<?php
	
	// Get the information required to update the file
	$dir = $_GET['dir'];
	$file = $_GET['f'];
	$ext = end((explode(".", $file)));
	
	// 
	$title = $_POST['title'];
	$heading = $_POST['heading'];
	
	// Make the heading directory if it doesnt exist
	if(!is_dir("DATA/resources/" . $heading))
		mkdir("DATA/resources/" . $heading);
	
	// rename the file to the title
	rename("DATA/resources/" . $dir ."/" . $file, "DATA/resources/" . $heading . "/" . $title . "." . $ext);
	
	// Scan the old directoy for files
	$dirFiles = scandir("DATA/resources/" . $dir);
	
	// if the directory is empty, remove it
	if(count($dirFiles) <= 2)
	{
		rmdir("DATA/resources/" . $dir);
	}
	
	// Relocate
	header("Location: resources.php");
?>
