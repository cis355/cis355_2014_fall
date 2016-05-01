<?php
	include "functions.php";
	
	if(isset($_POST['post']))
	{
		$assignText = $_POST['aText'];
		$assignTit = $_POST['aTitle'];
		$assignDate = $_POST['aDate'];
		
		$mysqli = ConnectToDb("ezlearn");
		InsertAnnouncement($mysqli, $assignTit, $assignText, $assignDate);
		
		LoadPage("PREVIEW - Home");
		
		$mysqli->close();

		
	}
?>
				<div class="col-lg-9">
					<?php
						// If the Post button was clicked
						if(isset($_POST["post"]))
						{
							$cool =  $_POST["aText"];
							$cool2 = $_POST["aTitle"];
							
							echo "<h3>" . $cool2 . "</h3>";
							echo $cool;
						}

					?>
					<br/><br/><br/>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							Previous Announcements
						</div>
						<div class="panel-body">
						<a href="#">Yeah, sweet shit...</a><br/>
						<a href="#">Yeah, sweet shit...</a><br/>
						</div>
					</div>
				</div>
<?php
	UnloadPage();
?>
