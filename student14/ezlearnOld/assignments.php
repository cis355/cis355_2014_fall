<?php
	include "functions.php";
	
	$headings = array();
	
	LoadPage("Assignments","assignments");
	
	$mysqli = ConnectToDB("ezlearn");
	
	$result = GetRecords($mysqli, "DISTINCT heading", "assignments");
	
	while($row = $result->fetch_row())
	{
		array_push($headings, $row[0]);
	}
	
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
		
		for($i = 0; $i < count($headings); $i++)
		{
			$mysqli2 = ConnectToDB("ezlearn");
			$result2 = GetRecordsWhere($mysqli2, "assignment_id, title, dateDue", "assignments", "heading", "'" . $headings[$i] ."'");
			echo '<h3><b>' . $headings[$i] . '</b></h3><table class="table table-bordered table-striped">
				<tr><th>Title</th><th>Due Date</th></tr>';
			while($rows = $result2->fetch_row())
			{
				echo '<tr><td><a href="viewAssignment.php?id=' .$rows[0] . '" >' . $rows[1] . '</a></td><td style="width:100px;">' . $rows[2] . '</td></tr>';
			}
			echo '</table><br>';
			$mysqli2->close();
		}
		
	?>
</div>
<div class="col-lg-3">
	<div class="dropdown" style="width:100%;">
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
					<tr><td style="width: 100px">Due Date: </td>
					<td style="padding-left: 15px;">
					<div class="input-append date" id="dp3" data-date="" data-date-format="mm/dd/yyyy">
						<table><tr><td><input class="span2" size="16" type="text" value="" name="formDue"></td><td style="padding-left: 5px;"><span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span></td></tr></table>
					</div>
					</td></tr>
					<tr><td colspan="2" style="width: 100px"><br/>Text: <br><br/></td></tr>
					<tr><td colspan="2" style="padding-left: 15px;"> <textarea id="ck" style="width: 100%;"></textarea><br/>
					<script>
					CKEDITOR.replace( 'ck', { height: '300px', resize: 'none' });
					
					</script></td></tr>
				</table>
				</div>
				<div class="modal-footer">
				<input type="hidden" id="formPosted" name="formPosted" value="">
                <input type="hidden" id="formText" name="formText" value="">
                <input type="hidden" id="formTitle" name="formTitle" value="">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="insertAssignment.php"><button type="submit" class="btn btn-success" onclick="AddAssignment();">Save changes</button></a>

                
				</form>
        </div>
            </div>

    </div>
</div>

<script type="text/javascript">

	var d = new Date();
	document.getElementById("formPosted").value = d.getMonth()+1 + "/" + d.getDate() + "/" + d.getFullYear();

	function AddAssignment()
	{
		$('#basicModal').modal(options);
	}
	
	function AddAssignment()
	{
		document.getElementById("formText").value = CKEDITOR.instances['ck'].getData();
		document.getElementById("formTitle").value = document.getElementById("title").value;
	}
	
</script>
<?php

	UnloadPage();
	
?>
