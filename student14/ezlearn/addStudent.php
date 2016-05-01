<?php
	// Include the functions file
	include "functions.php";
	
	// Load the page
	LoadPage("Add to Classlist", "classlist");
?>

<div class="col-lg-9">
	<div class="panel panel-primary" >
		<div class="panel-heading">
			Add a Student
		</div>
		<div class="panel-body">
		<form enctype="multipart/form-data" method="POST" action="postStudent.php">
		
		<table width=100% style="padding: 10px;">
			<tr><td colspan="2"><h3>Student Information</h3><hr></td></tr>
			<tr><td style="vertical-align: middle; width: 15%;">First Name: </td><td><input type="text" name="fname" class="form-control"></td></tr>
			<tr><td colspan="2"><br></td></tr>
			<tr><td>Last Name: </td><td><input type="text" name="lname" class="form-control"></td></tr>
			<tr><td colspan="2"><br><br><h3>Profile Information</h3><hr></td></tr>
			<tr><td>Username: </td><td><input type="text" name="username" class="form-control"></td></tr>
			<tr><td colspan="2"><br></td></tr>
			<tr><td>Password: </td><td><input type="text" name="password" class="form-control"></td></tr>
			<tr><td colspan="2"><br></td></tr>
			<tr><td>Picture: </td><td><input type="file" name="pic"></td></tr>
			<tr><td colspan="2"><br><br><h3>Permissions</h3><hr></td></tr>
			<tr><td>Level: </td><td><select class="form-control" id="admin" name="admin"><option>Student</option><option>Administrator</option></select></td></tr>
		</table>
		<br><br>
		<input type="reset" value="Reset" class="btn btn-danger" style="width: 150px; float: left;"><input type="submit" value="Add Student" class="btn btn-success" style="width: 150px; float: right;">
		</form>
		<script>
		
		// Create the selected index at -1 to force user to choose
		document.getElementById("admin").selectedIndex = -1;
		
		</script>
		</div>
	</div>
</div>

<?php
	UnloadPage();
?>