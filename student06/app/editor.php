<?php

// Output the header of the webpage
include( 'header.php' );

// Connect to the database
$database = new mysqli( 'localhost', 'user01', 'cis355lwip', 'lwip' );

// Check if there is a connection to the database
if ( $database->connect_errno == 0 ) {

	// Check if the user submitted a request
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

		// Check if the user requested an action to be performed
		if ( isset( $_POST['action'] ) ) {

			// Perform the action
			switch ( $_POST['action'] ) {
				case 'add':
					insert_record( $database, 'table06' );
					break;

				case 'delete':
					if ( delete_record( $database, 'table06' ) )
						display_msg('The record ' . $_POST['book_id'] . ' was sucessfully removed from the table.' );
					else
						display_error( 'ERROR: ' );
					break;

				case 'update':
					update_record( $database, 'table06' );
					break;
			}
		}
	}
	

	// Display the database table
	if( display_table( $database, 'table06' ) == false )
		display_error( 'Unable to retrieve table from the database.' );


// There is no connection to the database
} else {

	// Display an error message to the user
	display_error( $database->connect_error );

}

// Output the footer of the webpage
include( 'footer.php' );


/*=======================================
                Functions
=======================================*/

function display_error( $message ) {

	echo '<div class="error"><p><b>ERROR:</b> ' . $message . '</p></div>';

}

function display_msg( $message ) {

	echo '<div class="status"><p> ' . $message . '</p></div>';

}

function delete_record( $database, $table ) {

	$result = $database->query( 'DELETE FROM ' . $table . ' WHERE id=' . intval( $_POST['book_id'] ) );

	// If the query fails, then the row was not deleted
	if ( $result == false )
		return false;
	else
		return true;

}

function insert_record( $database, $table ) {

	// Extract all submitted field information out of the post superglobal
	extract( $_POST );

    /* Initialise the statement. */
    $stmt = $database->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $database->prepare("INSERT INTO $table (post_author, post_date, book_author, book_title, book_publisher, book_published, book_edition, book_genre, book_isbn, book_price, book_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
    {
		/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
		$stmt->bind_param('isssssissss', $post_author, date( 'Y-m-d', strtotime( $post_date ) ), $book_author, $book_title, $book_publisher, date( 'Y-m-d', strtotime( $book_published ) ), $book_edition, $book_genre, $book_isbn, floatval( $book_price ), $book_description );

		/* execute prepared statement */
		if( ! $stmt->execute() )
			echo "Error!!!!!!";

		/* close statment */
		$stmt->close();
    }
}

function update_record( $database, $table ) {

	//
	extract( $_POST );

    /* Initialise the statement. */
    $stmt = $database->stmt_init();
    /* Notice the two ? in values, these will be bound parameters*/
    if($stmt = $database->prepare( "UPDATE $table SET post_author=?, post_date=?, book_author=?, book_title=?, book_publisher=?, book_published=?, book_edition=?, book_genre=?, book_isbn=?, book_price=?, book_description=? WHERE ID=?" ) )
    {
            /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
            $stmt->bind_param('isssssissssi', $post_author, date( 'Y-m-d', strtotime( $post_date ) ), $book_author, $book_title, $book_publisher, date( 'Y-m-d', strtotime( $book_published ) ), $book_edition, $book_genre, $book_isbn, floatval( $book_price ), $book_description, intval( $book_id ) );

            /* execute prepared statement */
            $stmt->execute();
            /* close statment */
            $stmt->close();
    }
}

function display_table( $database, $table ) {

	// Query the table
	$result = $database->query( 'SELECT * FROM ' . $table );

	// If the query fails, then the table probably doesn't exist
	if ( $result == false )
		return false;

	// Output the table column headings
	echo '<table cellspacing="0"><thead><tr>';

	while ( $field_info = $result->fetch_field() ) {
		echo '<th>' . $field_info->name . '</th>';
	}

	echo '</thead><tbody>';

	// Output the table rows
	while ( $row = $result->fetch_row() ) {
			echo '<tr>';

			foreach( $row as $row_item )
				echo '<td>' . $row_item . '</td>';

			echo '</tr>';
		}

	echo '</tr></tbody></table>';

	return true;

}

?>