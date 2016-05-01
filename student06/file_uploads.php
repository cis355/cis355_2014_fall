<html>
<body>
	<form method="post" enctype="multipart/form-data">
		<table width="350" border="0" cellpadding="1"
		cellspacing="1" class="box">
		<tr>
			<td>please select a file</td></tr>
			<tr>
				<td>
					<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
					<input name="userfile" type="file" id="userfile"> 
				</td>
				<td width="80">
					<input name="upload" type="submit" class="box" id="upload" value=" Upload ">
				</td>
			</tr>
			</table>
		</form>
		<br>
		<a href="http://cis355.com/student01/file_download.php">Download</a><br>
		<a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
	</body>
	</html>
<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database
-->
<?php
// Sets PHP configuration settings to allow file uploads
ini_set( 'file-uploads', true );
// Display all errors
error_reporting( e_all );
// Checks to see if the user pressed the submit button and uploaded a file (that is greater than 0 bytes)
if( isset( $_POST['upload'] ) && $_FILES['userfile']['size'] > 0 )
{
	// The name of the file that was uploaded
	$fileName = $_FILES['userfile']['name'];
	
	// The name of the file that was uploaded, but this references the temporary version of the file
	// on the server. The name of the temporary file will be different than the actual name of the file
	// uploaded by the user.
	$tmpName  = $_FILES['userfile']['tmp_name'];
	
	// The size of the uploaded file in bytes
	$fileSize = $_FILES['userfile']['size'];

	// The MIME type of the file (ex: text/css, video/jpeg, application/pdf)
	$fileType = $_FILES['userfile']['type'];

	// If the server has the Magic Quotes feature enabled, the server will automatically escape special characters.
	// Sometimes developers do not want their applications to escape any characters, especially if you plan on outputting
	// the string to the page later on. If Magic Quotes is enabled, strip any backslashes (escape characters) from the string.
	$fileType=( get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
		$_FILES['userfile']['type']) : mysql_real_escape_string(
		stripslashes ( $_FILES['userfile'] ) ) );
	
	// Open the temporary file placed on the server. It is read only.
	$fp      = fopen( $tmpName, 'r' );

	// Reads the temporary file into a variable
	$content = fread( $fp, filesize( $tmpName ) );

	// Escapes special characters in the uploaded file's content
	$content = addslashes( $content );

	// Outputs meta data of the uploaded file (to the screen so the user can see it)
	echo 'filename: ' . $fileName . '<br>';  // file name
	echo 'filesize: ' . $fileSize . '<br>';  // file size
	echo 'filetype: ' . $fileType . '<br>';  // file type

	// Closes the temporary file that was being read it
	fclose( $fp );


	if( ! get_magic_quotes_gpc() )
	{
		$fileName = addslashes( $fileName );
	}

	// Connects to the database (or fails if the connection was unsuccessful)
	$con = mysql_connect( 'localhost', 'student', 'learn' ) or die( mysql_error() );
	// Since we are connected to MySQL, this piece of code selects the database to use. (Returns TRUE if the database exists)
	$db = mysql_select_db( 'lesson01', $con );
	
	if( $db ) {
		// This is the SQL query we are building that will be executed later on
		$query = "INSERT INTO nmc_upload (name, size, type, content ) " .
		"VALUES ('$fileName', '$fileSize', '$fileType', '$content')";

		// Execute the query (or show an error message if the query fails)
		mysql_query( $query ) or die( 'Error, query failed' ); 

		// Close the connection to MySQL
		mysql_close();

		// Outputs the name of the file that was uploaded to the server. This tells the user that their file
		// was sucessfully inserted into the database.
		echo '<br>File $fileName uploaded<br>';
	} else { 
		// Tells the user that their uploaded file did not get inserted into the database.
		echo 'file upload failed: ' . mysql_error(); 
	}
} 
?>