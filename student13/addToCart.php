<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable & connect to database
	$usertable = "tpm_shopping_cart";
	$db = dbConnect($usertable);
	
	// Assign local variables
	$itemId = $_GET['item'];
	$itemType = "tpm_".$_GET['type'];
	$custId = $_SESSION['userId'];
	
	// If form submitted assign quantity
	if (isset($_POST['cartSubmit']))
	{
		$quantity = $_POST['quant'];
	}
	
	// Execute query
	$sql = "SELECT * FROM $itemType WHERE id = '$itemId'";
	
	//echo $sql;
	$result = mysqli_query($db,$sql)or die(mysqli_error($db));
	 
	 // If valid query
	 if ($result)
	 {
		// Fetch table row
		$row = $result->fetch_array();
		
		// Assign local variables
		$brand = $row[1];
		$name = $row[3];
		$color = $row[4];
		$price = $row[5];
		$image = $row[8];
		
		// Execute nested query
		$sql = "INSERT INTO $usertable VALUES (NULL, $custId, '$itemId', '$brand', '$name', '$color', $price, '$image', $quantity)";
		$result = mysqli_query($db,$sql)or die(mysqli_error($db));
		
		// Redirect if valid query
		if ($result)
		{
			header("Location: shoppingCart.php");
			exit();
		}
	 }
?>

