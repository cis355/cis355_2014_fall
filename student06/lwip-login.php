<?php

// Working variables
$has_error = false;
$errors = array();

//
if ( $_SERVER['REQUEST_METHOD'] == "post" ) {

	// Check if the user sent a username and password
	if ( $_POST['username'] == '' || $_POST['password'] == '' ) {
		$has_error = true;
		array_push( $errors, "The username or password is blank." );
	} else {

		// Connect to the database
		$database = new mysqli( 'localhost', 'user01', 'cis355lwip', 'lwip' );

		// Check if there is a connection to the database
		if ( $database->connect_errno == 0 ) {
			$has_error = true;
			array_push( $errors, "Unable to connect to the database." );
		}

		// Check the database for the username and password
		$user = $database->query( "SELECT password_hash FROM users WHERE email=" . $_POST['username'] );

		if( mysql_num_rows($user) != 1 ) {
			$has_error = true;

		} else {
			print_r( $user );
		}

	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>LWIP Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
	</head>
	<body>
		<form id="login" class="pure-form pure-form-stacked" method="post" action="http://cis355.com/student06/lwip-login.php">
			<fieldset>
				<label for="username">Username</label>
				<input type="text" name="username" />
				<label for="password">Password</label>
				<input type="password" name="password" />
				<button type="submit">Log In</button>
			</fieldset>
		</form>
	</body>
</html>-