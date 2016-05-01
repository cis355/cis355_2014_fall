<?php

	$dir = $_GET['dir'];
	$file = $_GET['f'];
	
	unlink("DATA/resources/" . $dir ."/" . $file);
	
	$dirFiles = scandir("DATA/resources/" . $dir);
	
	if(count($dirFiles) <= 2)
	{
		rmdir("DATA/resources/" . $dir);
	}
	
	header("Location: resources.php");
	
?>