<?php

// Start the session
session_start();

// Clear the session
session_unset();

// Destroy the session
session_destroy();

// Relocate to landing page
header('Location: landing.php');
exit;

?>
