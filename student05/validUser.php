<?php 		
		// Required Databsae Information
		$hostname="localhost";
		$username="cabrown3";
		$password="tastepass";
		$dbname="tastebuds";
		
		$email = $_REQUEST["email"];
		
		// Create a mysqli object
		$mysqli = new mysqli($hostname, $username, $password, $dbname);
		
		$sql = "SELECT email FROM users WHERE email = ?";
		
		// Init statement
		$stmt = $mysqli->stmt_init();
		
		
		if($stmt = $mysqli->prepare($sql))
		{
            // Bind params
            $stmt->bind_param('s', $email);

			// Execute statement
            if($stmt->execute())
            {
				$valid = "";
				$stmt->bind_result($valid);
				
				if($stmt->fetch())
				{
					echo "true";
				}
				
				else
				{
					echo "false";
				}
			}	
		}
		
		$stmt->close();
		$mysqli->close();
?>