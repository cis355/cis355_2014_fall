<?php
	include "functions.php";
	
	// Connect to the DB
	$mysqli = ConnectToDB("ezlearn");
	
	// Get the student and assignment information
	$result = GetRecordsWhere($mysqli, "s.fname, s.lname, s.username, a.heading, a.title", "zmm_students s, zmm_assignments a", "s.student_id", $_GET['stuId'] . " AND a.assignment_id = " . $_GET['assignId']);

	// fetch the row
	$row = $result->fetch_row();
	
	// Load the page witht the assignment title
	LoadPage($row[4] . " Submissions", "assignments");
	
	// Close the DB
	$mysqli->close();
?>
<div class="col-lg-9">
	<h4><?php echo $row[0] . " " . $row[1] . "'s "; ?>Files</h4>
	<ul>
	<?php
		// Go into the students submited files folder
		$dir = "DATA/assignments/" . $row[3] . "/" . $row[4] . "/" . $row[2];
		$files = scandir($dir);
		
		// Display no files if not submitted
		if(count($files) < 3)
		{
			echo "No files have been submitted";
		}
		
		// Otherwise, display the files
		else
		{
			for($i = 2; $i < count($files); $i++)
			{
				echo "<li><a href='" . $dir ."/". $files[$i] .  "'>$files[$i]</a></li>";
			}
		}
	?>
	
	</ul>
</div>
<div class="col-lg-3">
</div>

<?php
	UnloadPage();
?>
