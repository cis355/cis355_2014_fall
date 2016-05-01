<?php
	ini_set("session.cookie_domain", ".cis355.com");
			
	// Start the session
	session_start();
	
	$hostname = "localhost";
	$username = "user01";
	$password = "cis355lwip";
	$dbname = "lwip";
	$usertable = "table13";

	$db = mysqli_connect($hostname,$username,$password,$dbname);
	
	if($db)
	{
		$delId = $_POST['alter'];
		$sql = "DELETE FROM table13 WHERE id = $delId";
		$result = mysqli_query($db,$sql)or die(mysqli_error($db));
		header("Location: table13.php");
		exit;
	}

?>