<?php
	include "functions.php";
	
	// Get the assignment information
	$heading = $_POST["formHeading"];
	$title = $_POST["formTitle"];
	$text = $_POST["formText"];
	$dateDue = $_POST["formDue"];
	$datePosted = $_POST["formPosted"];
	$points = $_POST["formPoints"];
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	$mysqli2 = ConnectToDb("ezlearn");
	
	// Insert the assignement
	InsertAssignment($mysqli, $heading, $title, $text, $dateDue, $datePosted, $points);
	
	// Close the DB
	$mysqli->close();
	
	// Get each student id and assignment id
	$result = GetRecords($mysqli2, "COUNT(s.student_id), s.student_id, a.assignment_id", "zmm_students s, zmm_assignments a WHERE a.heading = '" . $heading . "' AND a.title = '" . $title . "'");
	
	// Get the first row of data
	$row = $result->fetch_row();
	
	// Create a query
	$sql = "INSERT INTO zmm_grades VALUES ";
	
	// Insert the assignment into the grades table
	for($i = 0; $i < $row[0]; $i++)
	{	
		echo $row[0] . " " . $row[1] . " " . $row[2]; 
		$sql .= "(" . $row[1] . ", " . $row[2] . ", NULL)";
		if($i != ($row[0]-1))
		{
			$sql .= ", ";
		}
	}
	
	// Run the query
	echo $mysqli2->query($sql);
	
	// Close the DB
	$mysqli2->close();
	
	// Relocate
	header("Location: assignments.php");
	
?>
