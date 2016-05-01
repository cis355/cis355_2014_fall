<?php
	include "functions.php";
	
	LoadPage("Resources","resources");
	
	$cwd = getcwd();
	
	if(!is_dir("DATA/resources"))
	{
		mkdir("DATA/resources");
	}
	
?>
<script>
	
</script>
<div class="col-lg-9">
	<?php
		$directories = scandir("DATA/resources");
		$root = getcwd();

		for($i = 2; $i <= count($directories); $i++)
		{
			chdir("DATA/resources");
			if(is_dir($directories[$i]))
			{
				echo "<h3><b>" . $directories[$i] . "</b></h3>";
				$files = scandir($directories[$i]);
				chdir($directories[$i]);
				
				echo "<table style='width: 100%'>";
				for($j = 2; $j < count($files); $j++)
				{
					if(!is_dir($files[$j]))
					{
						echo "<tr><td style='padding-bottom: 5px; padding-left: 10px; width:75%;'><span class='glyphicon glyphicon-paperclip' style='padding-right: 5px;'> </span> <a href='DATA/resources/" . $directories[$i] . "/" . $files[$j] . "'>" . $files[$j] . "</a></td><td style='padding-bottom: 5px'><div class='dropdown'><button class='btn btn-warning dropdown-toggle' type='button' style='width: 55px;' id='drop". $directories[$i] . $j ."' data-toggle='dropdown'><span class='glyphicon glyphicon-cog'></span> <span class='caret'></span></button>";
						echo '<ul class="dropdown-menu" role="menu" aria-labelledby="drop' . $directories[$i] . $j .'">
			<li id="editButton" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#basicModal" onclick="EditResource(\'' . $directories[$i] . '\', \'' . $files[$j] . '\');"><span class="glyphicon glyphicon-wrench" style= "padding-right: 9px;"> </span> Edit</a></li>
			<li id="deleteButton" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" onclick="DeleteResource(\'' . $directories[$i] . '\', \'' . $files[$j] . '\');"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete</a></li>
		</ul></div></td></tr>';
					}
				}
				echo"</table><br/>";
			}
			chdir($root);
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
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#basicModal" onclick="AddResource();"><span class="glyphicon glyphicon-plus" style= "padding-right: 9px;"> </span> Add Resources</a></li>
			<li id="deleteButton" class="disabled" role="presentation"><a id="deleteLink" role="menuitem" tabindex="-1" href="#" onclick="ShowValidation();"><span class="glyphicon glyphicon-minus" style= "padding-right: 9px;"> </span> Delete Resources</a></li>
		</ul>
	</div>
</div>
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            <h4 class="modal-title" id="myModalLabel">Add a Resource</h4>
            </div>
            <div class="modal-body">
				<form id='form' method="POST" enctype="multipart/form-data" action="addResource.php">
                <table style="width:100%">
					<input type="hidden" id="formId" name="formId" value="">
					<tr><td style="width: 100px">Heading: </td><td style="padding-left: 15px; padding-bottom: 10px;"> <input id="heading" name="heading" class="form-control" type="text"></td></tr>
					<tr><td style="width: 100px">Title: </td><td style="padding-left: 15px; padding-bottom: 10px;"> <input id="title" name="title" class="form-control" type="text"></td></tr>
					<tr><td style="width: 100px" id='selectCol'>Select File: </td><td style="padding-left: 15px; padding-bottom: 10px;"><input type="file" name="uploadedFile" id="uploadedFile" /></td></tr>
				</table>
            </div>
            <div class="modal-footer">
				<button type="submit" id="upload" name="upload" class="btn btn-primary" style="width:150px; float: right;">Upload</button>
				</form>
			</div>
    </div>
  </div>
</div>
<script>
	function AddResource()
	{
		document.getElementById('uploadedFile').style.display = 'inherit';
		document.getElementById('selectCol').style.display = 'inherit';
		document.getElementById('heading').value = "";
		document.getElementById('title').value = "";
		document.getElementById('upload').innerHTML = "Upload";
		$('#basicModal').modal(options);
	}
	
	function EditResource(directory, file)
	{
		document.getElementById('uploadedFile').style.display = 'none';
		document.getElementById('selectCol').style.display = 'none';
		document.getElementById('heading').value = directory;
		document.getElementById('title').value = file.slice(0, file.lastIndexOf("."));
		document.getElementById('upload').innerHTML = "Modify";
		document.getElementById('form').action = 'editResource.php?dir=' + directory + '&f=' + file;
		$('#basicModal').modal(options);
	}
	
	function DeleteResource(directory, file)
	{
		if(confirm("Are you sure you want to delete the resource: " + file + "?"))
		{
			location.href="deleteResource.php?dir=" + directory + "&f=" + file;
		}
	
	}
</script>
<?php
	UnloadPage();
?>