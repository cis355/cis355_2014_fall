<?php
ini_set("session.cookie_domain", ".cis355.com");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Zach Metiva - Entry Form</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script>
		
		function selectLoc()
		{
			var selection = document.getElementById("selection");
			var index = selection.options[selection.selectedIndex].value;
			
			document.getElementById("lid").value = index;
		}
	
	</script>
	</head>
<body>
<br>
<div class="col-md-4">
	<form name='basic' method='POST' action='table14.php' onSubmit='return validate();'>
		<table class="table table-condensed" style="border: 1px solid #dddddd; border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; color: white; background-color:#333333;"><h2>Musical Instrument Form</h2></td></tr>
			<tr><td>Type: </td><td><input type='edit' name='type' value='' size='20'></td></tr>
			<tr><td>Brand: </td><td><input type='edit' name='brand' value='' size='20'></td></tr>
			<tr><td>Model: </td><td><input type='edit' name='model' value='' size='30'></td></tr>
			<tr><td>Color: </td><td><input type='edit' name='color' value='' size='20'></td></tr>
			<tr><td>String/Wind: </td><td><input type='edit' name='strWind' value='' size='30'></td></tr>
			<tr><td>Price: </td><td><input type='edit' name='price' value='' size='20'></td></tr>
			<tr><td>Description: </td><td><textarea style="resize: none;" name="descript" cols="40" rows="3"></textarea></td></tr>
			<tr><td style="vertical-align: middle;">Location: </td><td><select id="selection" class="form-control" onchange="selectLoc();">
					<?php
						$hostname="localhost";
						$username="user01";
						$password="cis355lwip";
						$dbname="lwip";
						
						$mysqli = new mysqli($hostname, $username, $password, $dbname);
						
						// Init statement
						$stmt = $mysqli->stmt_init();
						
						// Set Select query
						$sql = "SELECT * FROM locations";
						
						// Init location variable
						$dbId = "";
						$location = "";
						
						
						// If the statement was prepared
						if($stmt = $mysqli->prepare($sql))
						{
							// Execute statement
							if($stmt->execute())
							{
								// Bind query result
								$stmt->bind_result($dbId, $location);
								
								// Fetch the statement
								while ($stmt->fetch())
								{
									// Output the locations
									echo "<option value='" . $dbId ."'>" . $location . "</option>";
								}
							}
						}
						
						$mysqli->close();
					?>
					</select></td><td><input type="hidden" id="lid" value="1" name="locId"></td></tr>
			<tr><td><input type="submit" name="submit" class="btn btn-success" value="Add Entry"></td>
				<td style="text-align: right;"><input type="reset" class="btn btn-danger" value="Reset Form"></td></tr>
		</table>
		
		<a href="table14.php" class="btn btn-primary">Display Database</a>
	</form>
</div>

</body>
</html>
