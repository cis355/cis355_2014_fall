<?php
	// Start the Sessions
	session_start();
	
	// Destory the Sessions
	session_destroy();
	
	// Relocate
	header("Location: home.php");
?>
