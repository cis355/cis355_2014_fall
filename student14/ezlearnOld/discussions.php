<?php
	include "functions.php";
	
	LoadPage("Discussions", "discussions");
	
?>
<div class="col-lg-9">
	<a href="discussionBoard.php">Click here for Open Discussion</a>
	<hr>
	
</div>
<div class="col-lg-3">
	<div class="dropdown" style="width:100%;">
		<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> Site Settings
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#basicModal" onclick="AddResource();"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add Discussion</a></li>
			<li id="deleteButton" class="disabled" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" onclick="ShowValidation();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete Discussion</a></li>
		</ul>
	</div>
</div>




<?php
	UnloadPage();
?>