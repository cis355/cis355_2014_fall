<?php
	include "functions.php";
	
	$id = $_GET['id'];
	
	$mysqli = ConnectToDb("ezlearn");
	
	$result = GetRecordsWhere($mysqli, "*", "zmm_assignments", "assignment_id", "'". $id . "'");
	
	$row = $result->fetch_row();
	
	LoadPage($row[2], "assignments");
?>
<script>
	$(document).ready(function($) {
		$('#dp3').datepicker();
	});
</script>
<div class="col-lg-9">
	<?php
		echo "<div id='show'><span style='color: grey;'>Due Date: " . $row[4] . "</span><br/><br/>" . $row[3] . "</div><form method='POST' action='updateAssignment.php'><div id='hide' style='display: none;'>";
		echo 'Title: <input id="title" name="title" class="form-control" type="text" value="'.$row[2]. '"><br/><div class="input-append date" id="dp3" data-date="'.$row[4].'" data-date-format="mm/dd/yyyy">
						<table><tr><td><input class="span2" size="16" type="text" value="'.$row[4].'" name="dateDue"></td><td style="padding-left: 5px;"><span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span></td></tr></table>
					</div><br/>';
		echo "<textarea id='ck'></textarea><br/><button type='submit' class='btn btn-success' style='float: right; width:150px;' onclick='UpdateClick();'>Modify</button><br/></div><br/><input id='hiddenAss' name='hiddenAss' type='hidden' value='" . $row[3] . "'><input type='hidden' id='hiddenId' name='hiddenId' value='".$row[0]."'></form>";
	?>

</div>
<div class="col-lg-3">
<?php
	if($_SESSION['admin'] == 1)
	{
		echo'<div class="dropdown" style="width:100%;">
		<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> Site Settings
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;" onclick="EditAssignment();"><span class="glyphicon glyphicon-wrench" style= "padding-right: 9px;"> </span> Edit Assignment</a></li>
			<li role="presentation"><a id="delete" role="menuitem" tabindex="-1" href="#" onclick="DeleteAssignment();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete Assignment</a></li>
			<li role="presentation" class="divider"></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
		</ul>
	</div>';
	}
?>

</div>
<script>
	function EditAssignment()
	{
		document.getElementById("show").style.display = "none";
		document.getElementById("hide").style.display = "inherit";
		CKEDITOR.replace( "ck", { height: "300px" });
		CKEDITOR.instances["ck"].setData(document.getElementById("hiddenAss").value);
	}
	
	function UpdateClick()
	{
		document.getElementById("hiddenAss").value = CKEDITOR.instances["ck"].getData();
	}
	
	function DeleteAssignment()
	{
		location.href="deleteAssignment.php?id=" + document.getElementById("hiddenId").value;
		
	}


</script>
<?php

UnloadPage();

?>
