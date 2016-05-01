<?php
	// Include the functions file
	include "functions.php";
	
	// Load the page
	LoadPage("Add Announcement", "home");
?>
				<div class="col-lg-12">
				<form action="postAnnouncement.php" method="POST">
					<b>Announcement Title: </b><input id="title" type="text" class="form-control"><br/>
					<textarea id="ck" style="width: 100%;"></textarea>
					<script>
					
						// Create a ckEditor from the textarea
						CKEDITOR.replace( 'ck', { height: '300px', resize: 'none' });
						
						// Set the announcement text to a hidden form
						function SetAnnouncementText()
						{
							document.getElementById("annText").value = CKEDITOR.instances['ck'].getData();
							document.getElementById("annTitle").value = document.getElementById("title").value;
						}
						
					</script>
					<input type="hidden" id="annTitle" name="aTitle" value="">
					<input type="hidden" id="annText" name="aText" value="">
					<input type="hidden" id="annDate" name="aDate" value="">
					<br/><a href="postAnnouncement.php"><button type="submit" name="post" class="btn btn-success" style="width: 150px; float: right;" onclick="SetAnnouncementText();"><span class="glyphicon glyphicon-plus-sign"></span> Post</button><br/><br/>
					</form>
				</div>
				<script>
				
				// Get and set the date
				var d = new Date();
				document.getElementById("annDate").value = d.getMonth()+1 + "/" + d.getDate() + "/" + d.getFullYear();
				
				</script>
<?php
	UnloadPage();
?>
