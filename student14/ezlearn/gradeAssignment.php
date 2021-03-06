<?php
	// Include the functions file
	include "functions.php";
	
	// Start the sessions
	session_start();
	
	// If user is a student, relocate them
	if($_SESSION['admin'] == 0)
	{
		header("Location: grades.php");
		exit;
	}
	
	// Load the page
	LoadPage($_GET['h'] . " - " . $_GET['t'],"grades");

?>
<div class="col-lg-9">
	<table class="table table-bordered table-striped">

		<tr><th style="width: 50px;">Photo</th><th>Name</th><th>Score</th></tr>
		<form method="POST" action="submitGrade.php">
		
		<?php
			// Set the counter = 0
			$counter = 0;
			
			// Connect to the DB
			$mysqli = ConnectToDb("ezlearn");

			// Get the students in the DB
			$result = GetRecordsWhere($mysqli, "*","zmm_students","admin","0 ORDER BY lname");
			
			// Output each student
			while($row = $result->fetch_row())

			{
				echo "<tr><td style='vertical-align: middle'><img src='DATA/classlist/" . $row[3] . "/pic.png' style='width: 50px; height: 50px;'></td><td style='vertical-align: middle'><a href='viewStudent?id=" . $row[0] . "'>" . $row[2] . ", " . $row[1] . "</a></td><td style='width: 100px;'><input type='text' style='width: 40px;' name='student" . $counter . "'> / " . $_GET['pts'] . "<input type='hidden' name='id" . $counter ."' value='" . $row[0] ."'></td></tr>";
				$counter++;
			}
			
			echo "<input type='hidden' name='counter' value='" . $counter . "'>";
			
			// Close the DB
			$mysqli->close();

		?>
	<input type='hidden' name='assId' value='<?php echo $_GET["id"]; ?>'>
	</table>
	<button type="submit" class='btn btn-success btn-block'>Submit Grades</button>
	</form>
</div>

<?php

	UnloadPage();
	
?>
