<?php
	include "functions.php";
	LoadPage("Classlist", "classlist");
?>
<div class="col-lg-9">
	<h4><b>Students</b></h4>
	<table class="table table-bordered table-striped">
		<tr><th style="width: 50px;">Photo</th><th style="width: 60%;">Name</th><th>Username</th><th></th></tr>
		<?php
			$mysqli = ConnectToDb("ezlearn");
			$result = GetRecordsWhere($mysqli, "*","students","admin","0 ORDER BY lname");
			
			while($row = $result->fetch_row())
			{
				echo "<tr><td style='vertical-align: middle'><img src='DATA/classlist/" . $row[3] . "/pic.png' style='width: 50px; height: 50px;'></td><td style='vertical-align: middle'><a href='viewStudent?id=" . $row[0] . "'>" . $row[2] . ", " . $row[1] . "</a></td><td style='vertical-align: middle'>" . $row[3] . "</td><td style='vertical-align: middle'><center><input type='radio' id='edit' value='". $row[0] ." ". $row[1] . " " . $row[2] . " " . $row[3] ." " .$row[4] . " " .$row[6] ."' name='edit' onclick='EnableSettings(this.value);' ></center></td></tr>";
			}
			
			echo "</table><br/><h4><b>Administrators</b></h4><table class='table table-bordered table-striped'><tr><th style='width: 50px;'>Photo</th><th style='width: 60%;'>Name</th><th>Username</th></tr>";
			
			$result = GetRecordsWhere($mysqli, "*","students","admin","1");
			
			while($row = $result->fetch_row())
			{
				echo "<tr><td><img src='DATA/classlist/" . $row[3] . "/pic.png' style='width: 50px; height: 50px;'></td><td style='vertical-align: middle'>" . $row[2] . ", " . $row[1] . "</td><td style='vertical-align: middle'>" . $row[3] . "</td></tr>";
			}
			
			
			$mysqli->close();
		?>

	</table>
	<input type="hidden" id="hiddenId" value="">
</div>
<div class="col-lg-3">
	<div class="dropdown" style="width:100%;">
		<button class="btn btn-warning dropdown-toggle" style="width:100%; type="button" id="dropdownMenu1" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> Site Settings
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="width: 100%" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="addStudent.php"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add User</a></li>
			<li id="editButton" class="disabled" role="presentation"><a id="editLink" role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#basicModal" onclick="EditStudent();"><span class="glyphicon glyphicon-wrench" style= "padding-right: 9px;"> </span> Edit User</a></li>
			<li id="deleteButton" class="disabled" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" onclick="ShowValidation();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete User</a></li>
			<li role="presentation" class="divider"></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Edit Profile</a></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#">View Profile</a></li>
		</ul>
	</div>
	<br/>
	<div class="panel panel-default">
		<div class="panel-heading">
			Recent Messages
		</div>
		<div class="panel-body">
		
		</div>
	</div>
</div>
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            <h4 class="modal-title" id="myModalLabel">Edit Student</h4>
            </div>
            <div class="modal-body">
				<form method="POST" action="editStudent.php">
                <table style="width:100%">
					<input type="hidden" id="formId" name="formId" value="">
					<tr><td colspan="3"><b>User Settings</b><br/><br/></td></tr>
					<tr><td style="width: 100px">First Name: </td><td style="padding-left: 15px;"> <input id="formFname" name="formFname" class="form-control" type="text"></td><td rowspan="4"><div style="float: right;"><img id="formPic" src="" style="width: 150px; height: 150px;"><br><br><button class="btn btn-primary" style="width:150px;">Remove Picture</button></div></td></tr>
					<tr><td style="width: 100px">Last Name: </td><td style="padding-left: 15px;"> <input id="formLname" name="formLname" class="form-control" type="text"></td></tr>
					<tr><td colspan="2"><hr/><b>Profile Settings<br><br></td></tr>
					<tr><td style="width: 100px">Username: </td><td style="padding-left: 15px;"> <input id="formUsername" name="formUsername" class="form-control" type="text"></td></tr>
					<tr><td style="width: 100px">Password: </td><td style="padding-left: 15px;"> <input id="formPassword" name="formPassword" class="form-control" type="text"></td></tr>
					<tr><td colspan="2"><hr/><b>Permission Level</b><br><br></td></tr>
					<tr><td style="width: 100px">Level: </td><td style="padding-left: 15px;"> <select id="formPermission" name="formPermission" class="form-control"><option>Student</option><option>Administrator</option></select></td></tr>
				</table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save changes</button>
				</form>
        </div>
    </div>
  </div>
</div>

<script>
	function EnableSettings(val)
	{
		
		document.getElementById("editButton").className = "";
		document.getElementById("deleteButton").className = "";
		document.getElementById("hiddenId").value = val;
		PopulateModal();
	}
	
	function ShowValidation()
	{
		if(confirm("Are you sure you want to delete this student?"))
		{
			var path = "deleteStudent.php?id=";
			var str = document.getElementById("hiddenId").value;
			var array = str.split(" ");
			
			path += array[0];
			path += "&uname=" + array[3];
			document.getElementById("deleteLink").href= path;
		}
		else{
		
		}

	}
	
	function PopulateModal()
	{
		var str = document.getElementById('hiddenId').value;
		var array = str.split(" ");
		
		document.getElementById("formPic").src = "DATA/classlist/" + array[3] + "/pic.png";
		
		document.getElementById("formId").value = array[0];
		document.getElementById("formFname").value = array[1];
		document.getElementById("formLname").value = array[2];
		document.getElementById("formUsername").value = array[3];
		document.getElementById("formPassword").value = array[4];
		document.getElementById("formPermission").selectedIndex = array[5];
		
		
	}
	
	function EditStudent()
	{
		$('#basicModal').modal(options);
	}
	
</script>

<?php
	UnloadPage();
?>
