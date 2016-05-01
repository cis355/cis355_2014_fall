<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable & connect to database
	$usertable = "tpm_customers";
	$db = dbConnect($usertable);
	
	// Queries to empty shopping cart and delete account
	$sql = "DELETE FROM $usertable WHERE cust_id = ".$_SESSION['userId']."";
	$sql2 = "DELETE FROM tpm_shopping_cart WHERE cust_id = ".$_SESSION['userId']."";
	
	// Execute queries
	$result = mysqli_query($db,$sql)or die(mysqli_error($db));
	$result2 = mysqli_query($db,$sql2)or die(mysqli_error($db));
	
	// If execution successful, redirect
	if($result && $result2)
	{
		
		header("Location: logout.php");
		exit();
	}
?>