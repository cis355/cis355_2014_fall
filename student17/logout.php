<?php

ini_set("session.cookie_domain", ".cis355.com");
	
// Start the sessions
session_start();
	
session_destroy();

// Relocate to landing page
	header('Location: khaLanding.php');
	exit;
?>