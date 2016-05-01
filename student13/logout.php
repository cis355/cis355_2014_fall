<?php
	// Start and destroy session
	session_start();
	session_destroy();
	session_write_close();
	
	// Redirect
	header("Location: home.php");
	exit();
?>