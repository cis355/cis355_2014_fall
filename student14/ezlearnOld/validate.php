<?php

	session_start();
	
	include "functions.php";

	if(isset($_POST['loginButton']))
	{
		$username = "'";
		$username .= $_POST['email'];
		$username .= "'";
		
		$password = $_POST['password'];
		
		$mysqli = ConnectToDb("ezlearn");
		
		$result = GetRecordsWhere($mysqli, "*" , "students", "username", $username);
		
		if($result)
		{
			$row = $result->fetch_row();
			if($password == $row[4])
			{
				$_SESSION['username'] = $row[3];
				$_SESSION['student_id'] = $row[0];
				$_SESSION['fname'] = $row[1];
				$_SESSION['lname'] = $row[2];
				
				header("Location: home.php");
				exit;
			}
		
		}
		
		
	
	}
	header("Location: login.html");
	exit;
	
	
?>