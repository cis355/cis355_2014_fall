<?php
	include "functions.php";
	
	session_start();
	
	if($_SESSION['admin'] == 0)
	{
		header("Location: assignments.php");
		exit;
	}
	
	LoadPage($_GET['name'] . " Submissions", "assignments");
?>
<div class="col-lg-9">
	<h4><b>Students</b></h4>

	<table class="table table-bordered table-striped">

		<tr><th style="width: 50px;">Photo</th><th style="width: 60%;">Name</th><th>Username</th></tr>

		<?php

			$mysqli = ConnectToDb("ezlearn");

			$result = GetRecordsWhere($mysqli, "*","zmm_students","admin","0 ORDER BY lname");

			

			while($row = $result->fetch_row())

			{

				echo "<tr><td style='vertical-align: middle'><img src='DATA/classlist/" . $row[3] . "/pic.png' style='width: 50px; height: 50px;'></td><td style='vertical-align: middle'><a href='viewUploads.php?stuId=" . $row[0] . "&assignId=" . $_GET['id'] ."'>" . $row[2] . ", " . $row[1] . "</a></td><td style='vertical-align: middle'>" . $row[3] . "</td></tr>";

			}
		?>
</div>


<div class="col-lg-3">
</div>
<?php
	UnloadPage();
?>