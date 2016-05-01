<?php
	// Include the functions file
	include "functions.php";
	
	// Create a array to store the assignment headings
	$headings = array();
	
	// Load the assignments page
	LoadPage("Assignments","assignments");
	
	// Connect to the DB
	$mysqli = ConnectToDB("ezlearn");
	
	// Get the headings from the assignment table
	$result = GetRecords($mysqli, "DISTINCT heading", "zmm_assignments");
	
	// Push the headings into the array
	while($row = $result->fetch_row())
	{
		array_push($headings, $row[0]);
	}
	
	// Close the database
	$mysqli->close();
	
?>
<style>
.datepicker{z-index:1151 !important;}
</style>
<script>
	$(document).ready(function($) {
		$('#dp3').datepicker();
	});
</script>
<div class="col-lg-9">
	<?php
		
		// Loop through the headings array
		for($i = 0; $i < count($headings); $i++)
		{
			// Connect to the database
			$mysqli2 = ConnectToDB("ezlearn");
			
			// Get all of the assignments from the headings
			$result2 = GetRecordsWhere($mysqli2, "assignment_id, title, dateDue", "zmm_assignments", "heading", "'" . $headings[$i] ."'");
			echo '<h3><b>' . $headings[$i] . '</b></h3><table class="table table-bordered table-striped">
				<tr><th>Title</th><th>Due Date</th><th></th></tr>';
				
			// Display each assignment under its respective heading
			while($rows = $result2->fetch_row())
			{
				echo '<tr><td style="vertical-align: middle"><a href="viewAssignment.php?id=' .$rows[0] . '" >' . $rows[1] . '</a></td><td style="vertical-align: middle; width:100px;">' . $rows[2] . '</td><td style="vertical-align: middle; width: 150px;">';
				if($_SESSION['admin'] == 0)
				{
					echo "<a role='menuitem' tabindex='-1' href='#' data-toggle='modal' data-target='#submitModal' onclick='SetTitle(" . $rows[0] . ", \"" . $rows[1] . "\", \"" . $headings[$i] . "\"); SubmitAssignment();' style='text-decoration: none;'><button class='btn btn-success btn-block' href='submitAssignment.php'>Submit</button></a>";
				}
				
				else
				{
					echo "<a href='viewSubmissions.php?id=" . $rows[0] . "&name=" . $rows[1] . "' style='text-decoration: none;'><button class='btn btn-success btn-block' href='viewSubmissions.php'>View Submissions</button></a>";
				}
				echo '<tr>';
			}
			echo '</table><br>';
			
			// Close the database
			$mysqli2->close();
		}
		
	?>
</div>
<div class="col-lg-3">
<?php
	// If the user is an admin
	if($_SESSION['admin'] == 1)
	{
		// Show the Site Settings button
		echo'<div class="dropdown" style="width:100%;">
		<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> Site Settings
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;" data-toggle="modal" data-target="#basicModal" onclick="AddAssignment();"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add Assignment</a></li>
			<li role="presentation" class="divider"></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="quizGen.php"><span class="glyphicon glyphicon-pencil" style= "padding-right: 9px;"> </span> Create a Quiz</a></li>
		</ul>
	</div>
</div>';
	}
?>

<div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="ClearTitle()">X</button>
            <h4 class="modal-title" id="mySubmitModalLabel"></h4>
            </div>
			<form method="POST" action="submitAssignment.php" enctype="multipart/form-data">
            <div class="modal-body">
				<b>Upload File: </b><input type="file" name="fileUpload"><hr>
				<b>Comments:</b>
				<textarea id="ck2"></textarea><br/>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="headingSubmit" name="headingSubmit" value="">
				<input type="hidden" id="titleSubmit" name="titleSubmit" value="">
				<input type="hidden" id="messageSubmit" name="messageSubmit" value="">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
			</form>
        </div>
    </div>
</div>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            <h4 class="modal-title" id="myModalLabel">Add an Assignment</h4>
            </div>
            <div class="modal-body">
				<form method="POST" action="insertAssignment.php">
                <table style="width:100%">
					<input type="hidden" id="formId" name="formId" value="">
					<tr><td style="width: 100px">Heading: </td><td style="padding-left: 15px;"> <input id="formHeading" name="formHeading" class="form-control" type="text"></td></tr>
					<tr><td style="width: 100px">Title: </td><td style="padding-left: 15px;"> <input id="title" name="title" class="form-control" type="text"></td></tr>
					<tr><td style="width: 100px">Points: </td><td style="padding-left: 15px;"> <input id="formPoints" name="formPoints" class="form-control" type="text"></td></tr>
					<tr><td style="width: 100px">Due Date: </td>
					<td style="padding-left: 15px;">
					<div class="input-append date" id="dp3" data-date="" data-date-format="mm/dd/yyyy">
						<table><tr><td><input class="span2" size="16" type="text" value="" name="formDue"></td><td style="padding-left: 5px;"><span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span></td></tr></table>
					</div>
					</td></tr>
					<tr><td colspan="2" style="width: 100px"><br/>Text: <br><br/></td></tr>
					<tr><td colspan="2" style="padding-left: 15px;"> <textarea id="ck" style="width: 100%;"></textarea><br/></td></tr>
				</table>
				</div>
				<div class="modal-footer">
				<input type="hidden" id="formPosted" name="formPosted" value="">
                <input type="hidden" id="formText" name="formText" value="">
                <input type="hidden" id="formTitle" name="formTitle" value="">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="insertAssignment.php"><button type="submit" class="btn btn-success" onclick="PopAssignment();">Save changes</button></a>

                
				</form>
        </div>
            </div>

    </div>
</div>

<script type="text/javascript">
	// Get the date
	var d = new Date();
	document.getElementById("formPosted").value = d.getMonth()+1 + "/" + d.getDate() + "/" + d.getFullYear();

	// Show the add assignment pop up
	function AddAssignment()
	{
		CKEDITOR.replace( 'ck');

		$.fn.modal.Constructor.prototype.enforceFocus = function () {
			modal_this = this
			$(document).on('focusin.modal', function (e) {
				if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
				&&
				!$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
					modal_this.$element.focus()
				}
			})
		};
		$('#basicModal').modal(options);
	}
	
	// Show the submit dialog for the students
	function SubmitAssignment(id, title, heading)
	{
		CKEDITOR.replace( 'ck2');

		$.fn.modal.Constructor.prototype.enforceFocus = function () {
			modal_this = this
			$(document).on('focusin.modal', function (e) {
				if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
				// add whatever conditions you need here:
				&&
				!$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
					modal_this.$element.focus()
				}
			})
		};
		//document.getElementById('titleSubmit').value = title;
		$('#submitModal').modal(options);
		
	}
	function PopAssignment()
	{
		document.getElementById("formText").value = CKEDITOR.instances['ck'].getData();
		document.getElementById("formTitle").value = document.getElementById("title").value;
	}
	function SetTitle(id, title, heading)
	{
		document.getElementById('mySubmitModalLabel').innerHTML = heading + " - " + title;
		document.getElementById('headingSubmit').value = heading;
		document.getElementById('titleSubmit').value = title;
	}
	function ClearTitle()
	{
		document.getElementById('mySubmitModalLabel').innerHTML = "";
		document.getElementById('headingSubmit').value = "";
		document.getElementById('titleSubmit').value = "";
	}
	
</script>
<?php

	UnloadPage();
	
?>
