<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable and connect to database
	$usertable = "tpm_shopping_cart";
	$db = dbConnect($usertable);

	// Create and execute query
	$sql = "UPDATE $usertable SET quantity = ".$_GET['quant']." WHERE cart_id = ".$_GET['id']."";
	$result = mysqli_query($db,$sql)or die(mysqli_error($db));
	
	// If valid query, redirect
	if($result)
	{
		header("Location: shoppingCart.php");
		exit();
	}
?>