<?php

	$dir = $_GET['dir'];
	$file = $_GET['f'];
	$ext = end((explode(".", $file)));
	
	$title = $_POST['title'];
	$heading = $_POST['heading'];
	
	if(!is_dir("DATA/resources/" . $heading))
		mkdir("DATA/resources/" . $heading);
	
	rename("DATA/resources/" . $dir ."/" . $file, "DATA/resources/" . $heading . "/" . $title . "." . $ext);
	
	$dirFiles = scandir("DATA/resources/" . $dir);
	
	if(count($dirFiles) <= 2)
	{
		rmdir("DATA/resources/" . $dir);
	}
	
	header("Location: resources.php");
?>