<?php
	// Include the functions file
	include "functions.php";
	
	// Get the title and heading of the discussion
	$heading = $_GET['h'];
	$title = $_GET['t'];
	
	// If the heading is od, then its the Open Discussion
	if($heading == 'od')
	{
		$tit = "Open Discussion";
	}
	
	// Otherwise use the title of the discussion
	else
	{
		$tit = $heading . " - " . $title;
	}
	
	// Load the page
	LoadPage($tit, "discussions");
?>

<div class="col-lg-9">
<br/>
<br/>
<?php
	
	// Connect to the DB
	$mysqli = ConnectToDb("ezlearn");
	
	// Create the query
	$sql = "SELECT s.fname, s.lname, s.username, d.datePosted, d.message, d.timestamp, s.admin FROM zmm_students s, zmm_discussions d WHERE d.heading = '" . $heading . "' AND d.title = '". $title . "' AND s.student_id = d.student_id ORDER BY d.discussion_id ASC";
	
	// Run the query
	$result = $mysqli->query($sql);
	
	// Create the discussions on the page
	while($row = $result->fetch_row())
	{
			echo '<div class="panel panel-info">
				<div class="panel-heading">
					<table style="width: 100%;"><tr><td style="width: 45px;"><img src="DATA/classlist/'. $row[2] .'/pic.png" style="width: 35px; height: 35px;"></td><td style="padding-top: 5px; font-size: 20px;">' . $row[0] . ' ' . $row[1] . '</td> ';
					echo'</td><td><i style="color: grey; font-size: 13px; padding-top: 10px; float: right;">Posted: ' . $row[3] .'</i></td></tr></table>
				</div>
				<div class="panel-body">
					' . $row[4] .'
				</div>
				<div class="panel-footer">
				<table style="width: 100%"><tr>';
				if($row[6] == 0)
					echo '<td><span class="label label-primary" style="font-size: 10px;">Student</span></td>';
				else echo '<td><span class="label label-danger" style="font-size: 10px;">Administrator</span></td>';
				
				echo '<td><p class="text-right" style="margin-bottom: 0px;font-size: 10px;color: gray;">' . $row[5] . '</p></td></tr></table>
				</div>
			</div>';
	}
?>
<br/>
<a href="#" data-toggle="modal" data-target="#basicModal" onclick="Post();" class="btn-block" style="text-decoration: none"><button type="button" class="btn btn-success btn-block">Post</button></a>
<br/>
</div>
<div class="col-lg-3">
<?php
	// Create Site Settings button for admin
	if($_SESSION['admin'] == 1)
	{
		echo'<div class="dropdown" style="width:100%;">
		<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> Site Settings
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#basicModal" onclick="AddResource();"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add Discussion</a></li>
			<li id="deleteButton" class="disabled" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" onclick="ShowValidation();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete Discussion</a></li>
		</ul>
	</div><br/>';
	}
?>

</div>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            <h4 class="modal-title" id="myModalLabel">Post in Discussion</h4>
            </div>
			<form method="POST" action="postToDiscussion.php">
            <div class="modal-body">
				<textarea id="ck"></textarea><br/>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="heading" name="heading" value="<?php echo $heading; ?>">
				<input type="hidden" id="title" name="title" value="<?php echo $title; ?>">
				<input type="hidden" id="datePosted" name="datePosted" value="">
				<input type="hidden" id="message" name="message" value="">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success" onclick="CreatePost();">Save changes</button>
			</div>
			</form>
        </div>
    </div>
</div>

<script>

	// Get the date
	var d = new Date();
	document.getElementById("datePosted").value = d.getMonth()+1 + "/" + d.getDate() + "/" + d.getFullYear();
	
	// Show the post pop up
	function Post()
	{
		CKEDITOR.replace( 'ck');

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
		$('#basicModal').modal(options);
	}
	
	// Set the hidden element to the post text
	function CreatePost()
	{
		document.getElementById("message").value = CKEDITOR.instances['ck'].getData();
	}
</script>
<?php
	UnloadPage();
?>
