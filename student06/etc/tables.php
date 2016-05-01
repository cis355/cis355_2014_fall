<?php
/**
 * Displays an simple web interface for viewing a table from any database
 *
 * @author		Nicholas Chapin <nmchapin@svsu.edu>
 * @copyright	2014 Nicholas Chapin
 * @version		1.0.0
 *
 */

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>View Table</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
				background-color: #EEE;
			}
			#content-wrapper {
				margin: 0 auto;
				min-width: 480px;
				width: 80%;
			}
			form {
				max-width: 350px;
				margin: 0 auto;
			}
			form input {
				width: 100% !important;
			}
			table {
				min-width: 350px;
				margin: 25px auto 0 auto;
			}
			.error {
				background-color: #EB4646;
				border: solid 1px #B82B2B;
				border-radius: 3px;
				box-shadow: inset 0 0 1px #FFF;
				color: #FFF;
				font-size: 14px;
				padding: 10px;
				margin: 5px 0;
			}
		</style>
	</head>
	<body>
		<div id="content-wrapper">
			<form method="post" class="pure-form">
				<fieldset class="pure-group">
					<input type="hidden" name="action" value="view">
					<input type="text" name="hostname" class="pure-input-1-2" placeholder="Host" value="<?php echo ( $_POST['hostname'] ? $_POST['hostname'] : '' ) ?>">
					<input type="text" name="username" class="pure-input-1-2" placeholder="Username" value="<?php echo ( $_POST['username'] ? $_POST['username'] : '' ) ?>">
					<input type="text" name="password" class="pure-input-1-2" placeholder="Password" value="<?php echo ( $_POST['password'] ? $_POST['password'] : '' ) ?>">
					<input type="text" name="dbname" class="pure-input-1-2" placeholder="Database" value="<?php echo ( $_POST['dbname'] ? $_POST['dbname'] : '' ) ?>">
					<input type="text" name="table" class="pure-input-1-2" placeholder="Table" value="<?php echo ( $_POST['table'] ? $_POST['table'] : '' ) ?>">
				</fieldset>
				<fieldset class="pure-group">
					<input type="text" name="query" class="pure-input-1-2" placeholder="Query" value="<?php echo ( $_POST['query'] ? $_POST['query'] : '' ) ?>">
				</fieldset>
				<input type="submit" name="submit" class="pure-button pure-input-1-2 pure-button-primary" value="View Table/Perform Query">
			</form>

<?
// Check if the user has submitted a request to view a DB table
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	// Extract all items from the form array
	extract( $_POST );

	if( $_POST['action'] == 'view' ) {
		// Make sure the user provided all of the required data
		$required_keys = array(
			'hostname',
			'username',
			'password',
			'dbname',
			'table'
		);

		foreach( $required_keys as $required_key ) {
			if ( !array_key_exists( $required_key, $_POST ) )
				die( 'ERROR: The form submission was missing the key "' . $required_key . '".' );
		}

		extract( $_POST );

		// Try to establish a connection with the database
		$mysqli = new mysqli( $hostname, $username, $password, $dbname );

		if( $mysqli->connect_errno )
			die('ERROR: Unable to connect to database [' . $mysqli->connect_error. ']');

		if( $new_result = $mysqli->query( $_POST['query'] ) ) {
			//if( $mysqli->error() )
				//printf("Errormessage: %s\n", $mysqli->error);
		} else {
			if( $_POST['query'] != '' )
				echo '<p class="error">Query Error: '. $mysqli->error .'</p>';
		}

		// Query the database table for records
		if( $result = $mysqli->query( 'SELECT * FROM ' . $table ) ) {

			// Print out table columns
			echo '<table class="pure-table"><thead><tr>';
			while ( $fieldinfo = mysqli_fetch_field($result) ) {
				echo '<th>' . $fieldinfo->name . '</th>';
			}
			echo '</tr></thead><tbody>';

			while( $row_items = $result->fetch_row() ) {
				echo '<tr>';

				foreach( $row_items as $item ) {
					echo '<td>' . $item . '</td>';
				}

				echo '</tr>';
			}

			echo '</tbody></table>';

			$result->close();
	    } else {

// Make sure the query executed correctly without errors
	    	if( $_POST['table'] != '' )
				echo '<p class="error">Error: '. $mysqli->error .'</p>';
	    }
	}
}
?>
		</div>
	</body>
</html>