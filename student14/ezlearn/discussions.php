<?php
	// Include the functions file
	include "functions.php";
	
	// Load the page
	LoadPage("Discussions", "discussions");
?>
<div class="col-lg-9">
	<a href="discussionBoard.php?h=od&t=od">Click here for Open Discussion</a>
	<hr>
	<?php
		// Create the query
		$sql = "SELECT DISTINCT heading FROM zmm_discussion_base WHERE heading != 'od'";
		
		// Connect to the DB
		$mysqli = ConnectToDb('ezlearn');
		
		// Run the query
		$result = $mysqli->query($sql);
		
		// Fetch the rows from the DB
		while($headings = $result->fetch_row())
		{
			// Connect to the DB
			$mysqli2 = ConnectToDb('ezlearn');
			
			echo "<h3>" . $headings[0] . "</h3>";
			
			// Create the query
			$sql2 = "SELECT title FROM zmm_discussion_base WHERE heading = '" . $headings[0] . "'";
			
			// Run the query
			$result2 = $mysqli2->query($sql2);
			
			// Output each discussion
			while($titles = $result2->fetch_row())
			{
				echo "<a href='discussionBoard.php?h=" . $headings[0] . "&t=". $titles[0]. "' style='padding-left: 15px;'>" . $titles[0] . "</a><br>";
			}
			
			echo "<br/>";
			
			// Close the DB
			$mysqli2->close();
		}
?>
		
</div>
<div class="col-lg-3">
<?php 
	// Create the Site Settings button for admin
	if($_SESSION['admin'] == 1)
	{
		echo'	<div class="dropdown" style="width:100%;">
		<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> Site Settings
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#basicModal" onclick="AddResource();"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add Discussion</a></li>
			<li id="deleteButton" class="disabled" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" onclick="ShowValidation();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete Discussion</a></li>
		</ul>
	</div>';
	}


?>
</div><div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>

            <h4 class="modal-title" id="myModalLabel">Add a Discussion</h4>

            </div>

            <div class="modal-body">

				<form id='form' method="POST" action="addDiscussion.php">

                <table style="width:100%">

					<input type="hidden" id="formId" name="formId" value="">

					<tr><td style="width: 100px">Heading: </td><td style="padding-left: 15px; padding-bottom: 10px;"> <input id="heading" name="heading" class="form-control" type="text"></td></tr>

					<tr><td style="width: 100px">Title: </td><td style="padding-left: 15px; padding-bottom: 10px;"> <input id="title" name="title" class="form-control" type="text"></td></tr>

				</table>

            </div>

            <div class="modal-footer">

				<button type="submit" id="upload" name="upload" class="btn btn-primary" style="width:150px; float: right;">Create</button>

				</form>

			</div>

    </div>

  </div>

</div>




<?php
	UnloadPage();
?>
