<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable & connect to database
	$usertable = "tpm_shopping_cart";
	$db = dbConnect($usertable);
	
	// If form submitted, assign deletion id
	if (isset($_POST['deleteSubmit']))
	{
		$delId = $_POST['delete'];
	}
	
	// Create and execute query
	$sql = "DELETE FROM $usertable WHERE cart_id = $delId";
	$result = mysqli_query($db,$sql)or die(mysqli_error($db));
	
	// If valid query, redirect
	if ($result)
	{
		header("Location: shoppingCart.php");
		exit();
	}
?>