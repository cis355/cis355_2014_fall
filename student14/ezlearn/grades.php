<?php
	// Include the functions file
	include "functions.php";
	
	// Load the page
	LoadPage("Grades", "grades");
	
	// Create an array of headings
	$headings = array();
	
	// Connect to the database
	$mysqli = ConnectToDB("ezlearn");
	
	// Get the headings from the assignments
	$result = GetRecords($mysqli, "DISTINCT heading", "zmm_assignments");
	
	// Push each assignment heading into the array
	while($row = $result->fetch_row())
	{
		array_push($headings, $row[0]);
	}
	
	// Close the DB
	$mysqli->close();

?>

<div class="col-lg-9">
	<?php
		// If the user is an admin
		if($_SESSION['admin'] == 1)
		{
			// Loop thru each assignment and display it for grading
			for($i = 0; $i < count($headings); $i++)
			{
				$mysqli2 = ConnectToDB("ezlearn");
				$result2 = GetRecordsWhere($mysqli2, "assignment_id, title, dateDue, points", "zmm_assignments", "heading", "'" . $headings[$i] ."'");
				echo '<h3><b>' . $headings[$i] . '</b></h3><table class="table table-bordered table-striped">
					<tr><th>Title</th><th>Due Date</th></tr>';
				while($rows = $result2->fetch_row())
				{
					echo '<tr><td><a href="gradeAssignment.php?id=' .$rows[0] . '&h=' . $headings[$i] . '&t=' . $rows[1] .'&pts=' . $rows[3] . '" >' . $rows[1] . '</a></td><td style="width:100px;">' . $rows[2] . '</td></tr>';
				}
				echo '</table><br>';
				$mysqli2->close();
			}
		}
		// Otherwise, if the user is a student
		else
		{
			$totalPossible = 0;
			$totalScore = 0;
			
			// Loop thru the headings
			for($i = 0; $i < count($headings); $i++)
			{
				// Connect to the DB
				$mysqli2 = ConnectToDB("ezlearn");
				
				// Get the students grades from each assignment
				$result2 = GetRecordsWhere($mysqli2, "a.assignment_id, a.title, a.dateDue, a.points, g.score", "zmm_assignments a, zmm_grades g", "g.student_id", $_SESSION['student_id'] . " AND g.assignment_id = a.assignment_id AND a.heading = '" . $headings[$i] ."'");
				
				echo '<h3><b>' . $headings[$i] . '</b></h3><table class="table table-bordered table-striped">
					<tr><th>Title</th><th>Score</th><th>Grade</th></tr>';
				
				// Output each assignemtn and the coressponding grade
				while($rows = $result2->fetch_row())
				{
					echo '<tr><td><a href="viewAssignment.php?id='. $rows[0] . '">' . $rows[1] . '</a></td><td style="width: 100px;">' . $rows[4] . ' / ' . $rows[3] . '</td><td style="width: 50px;">';
					echo getScore((int)$rows[4] / (int)$rows[3]);
					echo '</td></tr>';
					$totalPossible += $rows[3];
					$totalScore += $rows[4];
				}
				echo '</table><br>';
				
				// Close the DB
				$mysqli2->close();
			}
			$s = $totalScore / $totalPossible;
			echo '<table class="table table-bordered"><tr><th colspan=2>Grade Summary</th></tr><tr><td>Total Score: </td><td style="width: 100px;"><center>' . $totalScore . ' / ' . $totalPossible . '</center></td></tr><tr><td>Grade: </td><td>' . getScore($s) . '</td></tr></table>';
		}
	?>
</div>


<?php
	// Output the students grade based on 
	function getScore($scre)
	{
		// A
		if ($scre <= 1 && $scre >= 0.93)
		{
			return '<center><span style="color: green"><b>A</b></span></center>';
		}
		// A-
		if ($scre < 0.93 && $scre >= 0.90)
		{
			return '<center><span style="color: green"><b>A-</b></span></center>';
		}
		// B+
		if ($scre < 0.90 && $scre >= 0.86)
		{
			return '<center><span style="color: blue"><b>B+</b></span></center>';
		}
		// B
		if ($scre < 0.86 && $scre >= 0.83)
		{
			return '<center><span style="color: blue"><b>B</b></span></center>';
		}
		// B-
		if ($scre < 0.83 && $scre >= 0.80)
		{
			return '<center><span style="color: blue"><b>B-</b></span></center>';
		}
		// C+
		if ($scre < 0.80 && $scre >= 0.76)
		{
			return '<center><span style="color: black"><b>C+</b></span></center>';
		}
		// C
		if ($scre < 0.76 && $scre >= 0.73)
		{
			return '<center><span style="color: black"><b>C</b></span></center>';
		}
		// C-
		if ($scre < 0.73 && $scre >= 0.70)
		{
			return '<center><span style="color: black"><b>C-</b></span></center>';
		}
		// D+
		if ($scre < 0.70 && $scre >= 0.66)
		{
			return '<center><span style="color: yellow"><b>D+</b></span></center>';
		}
		// D
		if ($scre < 0.66 && $scre >= 0.63)
		{
			return '<center><span style="color: yellow"><b>D</b></span></center>';
		}
		// D-
		if ($scre < 0.63 && $scre >= 0.60)
		{
			return '<center><span style="color: yellow"><b>D-</b></span></center>';
		}
		// F
		if ($scre < 0.60 && $scre >= 0.00)
		{
			return '<center><span style="color: red"><b>F</b></span></center>';
		}
		
		
	}
	UnloadPage();
?>
