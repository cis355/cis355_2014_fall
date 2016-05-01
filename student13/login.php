<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// If user tried to log-in
	if (isset($_POST['loginSubmit']))
	{
		// Test valid credentials
		if (empty($_POST['username']) || empty($_POST['password']))
		{
			echo "Login failed...";
		}
		else
		{	
			// Assign $usertable and connect to database
			$usertable = "tpm_customers";
			$db = dbConnect($usertable);
			
			// Assign local username & password variables
			$usernameLog = $_POST['username'];
			$passwordLog = $_POST['password'];
			
			// Execute query
			$sql = "SELECT * FROM $usertable WHERE password = '$passwordLog' AND username = '$usernameLog'";
			$result = $db->query($sql);
			
			// Get number of rows (should only be 1)
			$numRows = mysqli_num_rows($result);
			
			// If only 1 row
			if ($numRows == 1)
			{
				// Assign SESSION variables
				$row = $result->fetch_array();
				$_SESSION['user'] = $row[3];
				$_SESSION['userId'] = $row[0];
				$_SESSION['last'] = $row[4];
				$_SESSION['address'] = $row[8];
				$_SESSION['city'] = $row[9];
				$_SESSION['state'] = $row[10];
				$_SESSION['zip'] = $row[11];
				$_SESSION['card'] = $row[5];
				header("Location: home.php");
			}
			else
			{	
				echo "Login failed...";
			}
		}
	}
	else
	{
		echo "Login failed...";
	}
?>