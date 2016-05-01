<?php
include "functions.php";
session_start();
	LoadPage("Home", "home");
	
	function PostAnnouncement()
	{
		$mysqli = ConnectToDb("ezlearn");
		
		if(!empty($_GET))
		{
			$id = $_GET["id"];
				
			$result = GetRecordsWhere($mysqli, "*", "announcements", "announcement_id", $id);
			
		}
		else
		{
			$result = GetRecords($mysqli, "*", "announcements ORDER BY announcement_id DESC LIMIT 1");
		}
		
		$row = $result->fetch_row();
			
		echo "<form method='POST' action='changeAnnouncement.php'><input name='annId' id='annId' type='hidden' value='" . $row[0] . "' ><div id='sw'><h3>" . $row[1] . "</h3><span style='color: grey'>Posted on: " . $row[3] . "</span><br/><br/>" . $row[2] . "</div>";
		echo '<div id="hide" style="display: none;"><b>Announcement Title: </b><input id="title" name="title" type="text" class="form-control" value="' . $row[1] . '" ><br/>
					<textarea id="ck" style="width: 100%;"></textarea><input id="hiddenAnn" type="hidden" value=\'' . $row[2] . '\'><br/>
					<input type="hidden" id="HideMe" name="HideMe" value="" ><input type="submit" class="btn btn-success" style="float: right; width: 150px;" onclick="ChangeText();" value="Modify"></form></div>';
		
		
		
		$mysqli->close();
		
		return $row;
	}
	
?>
				<div class="col-lg-9">
					<?php
					
						$rw = PostAnnouncement();
					?>
					<br/>
		
					<br/><br/><br/>
				</div>
				<div class="col-lg-3">
					<div class="dropdown" style="width:100%;">
						<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
							<span class="glyphicon glyphicon-cog"></span> Site Settings
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
							<li role="presentation"><a role="menuitem" tabindex="-1" href="addAnnouncement.php"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add Announcement</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;" onclick="EditAssignment();"><span class="glyphicon glyphicon-wrench" style= "padding-right: 9px;"> </span> Edit Announcement</a></li>
							<li role="presentation"><a id="delete" role="menuitem" tabindex="-1" href="#" onclick="ShowValidation();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete Announcement</a></li>
							<li role="presentation" class="divider"></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
						</ul>
					</div>
					<br/>
					<div class="panel panel-default">
						<div class="panel-heading">
							Previous Announcements
						</div>
						<div class="panel-body">
						<?php 
							$mysqli = ConnectToDb("ezlearn");
							$result = GetRecords($mysqli, "*", "announcements ORDER BY announcement_id DESC");
							
							while($row = $result->fetch_row())
							{
								echo "<a href='#' onclick='ViewAssignment(" . $row[0] . ");'>" . $row[1] . "</a><br>";
							}
							
							$mysqli->close();
							
						?>
						</div>
					</div>
				</div>
				<script>
				function ViewAssignment(aid)
				{
					var loc = window.location;
					window.location = loc.protocol + '//' + loc.host + loc.pathname + "?id=" + aid;
				
				}
				
				function EditAssignment()
				{
					document.getElementById("sw").style.display = "none";
					document.getElementById("hide").style.display = "inherit";
					CKEDITOR.replace( "ck", { height: "300px" });
					CKEDITOR.instances["ck"].setData(document.getElementById("hiddenAnn").value);
				
				}
				
				function ChangeText()
				{
					document.getElementById("HideMe").value = CKEDITOR.instances["ck"].getData();
				}
				
				function ShowValidation()
				{
					if(confirm("Are you sure you want to delete this announcement?"))
					{
						var path = "deleteAnnouncement.php?id=";
						path += document.getElementById("annId").value;
						document.getElementById("delete").href= path;
					}
					else{
					
					}

				}
				</script>
<?php
	UnloadPage();
?>
