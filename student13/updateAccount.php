<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable and connect to database
	$usertable = "tpm_customers";
	$db = dbConnect($usertable);
	
	// Initialize credit card brand array
	$brands = array("Visa", "MasterCard", "American Express", "Discover");
	
	// Initialize array with state abbreviations
	$us_state = array('AL', 'AK', 'AZ', 'AR', 'CA', 
					  'CO', 'CT', 'DE', 'DC', 'FL', 
					  'GA', 'HI', 'ID', 'IL', 'IN', 
					  'IA', 'KS', 'KY', 'LA', 'ME', 
					  'MD', 'MA', 'MI', 'MN', 'MS', 
					  'MO', 'MT', 'NE', 'NV', 'NH', 
					  'NJ', 'NM', 'NY', 'NC', 'ND', 
					  'OH', 'OK', 'OR', 'PA', 'RI', 
					  'SC', 'SD', 'TN', 'TX', 'UT', 
					  'VT', 'VA', 'WA', 'WV', 'WI', 
					  'WY');
	
	// If $_POST[] (works like a sticky form)
	if (isset($_POST['updateSubmit']))
	{
		// Assign variables
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstName = $_POST['first_name'];
		$lastName = $_POST['last_name'];
		$creditCard = $_POST['brand'];
		$creditCardNum = $_POST['credit_card_number'];
		$security = $_POST['security_code'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zipCode = $_POST['zip_code'];
		$phoneNum = $_POST['phone_number'];
		
		// Create query
		$sql = "UPDATE $usertable SET username = '$username', password = '$password', first_name = '$firstName',
				last_name = '$lastName', credit_card = '$creditCard', credit_card_num = '$creditCardNum', security_code = '$security',
				address = '$address', city = '$city', state = '$state', zip_code = $zipCode, phone_number = '$phoneNum' WHERE cust_id = ".$_SESSION['userId']."";

		// Execute query
		$result = mysqli_query($db,$sql)or die(mysqli_error($db));
		
		// If valid query, redirect
		if ($result)
		{
			header("Location: account.php");
			exit();
		}
	}
	else
	{	
		// Create query to get customer info from database
		$sql = "SELECT * FROM $usertable WHERE cust_id = ".$_SESSION['userId']."";
		
		// Execute query
		$result = mysqli_query($db,$sql)or die(mysqli_error($db));
		
		// Populate array
		$row = $result->fetch_array();
	}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!-- Render HTML header -->
<?php showHeader(); ?>

<!-- Body -->
<body style="padding-top:70px;" background="fabric_of_squares_gray.png">

	<!-- Render Navbar -->
	<?php showNavbar(); ?>
	
	<center>
	<div class="container">
		
		<!-- New Bootstrap row -->
		<div class="row">
			
			<!-- Empty column used for spacing -->
			<div class="col-md-2"></div>
			
			<!-- New Bootstrap column -->
			<div class="col-md-8"> <br>
				<!-- Display customer information for editing -->
				<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
					<div class="panel-heading"><b>Edit Account</b></div>
					<div class="panel-body">
						<form id="updateForm" method="POST" action="updateAccount.php">
							<table class="table borderless">
								<thead>
									<td style="vertical-align: middle; width:200px;"><b>Email Address: </b></td>
									<td><input id="username" type="text" name="username" class="form-control" value="<?php echo $row[1]; ?>"></td><td></td>
								</thead>
								<tr>
									<td style="vertical-align: middle;"><b>Password: </b></td>
									<td><input type="password" id="password" name="password" class="form-control" value="<?php echo $row[2]; ?>"></td><td></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Retype Password: </b></td>
									<td><input type="password" id="retype" name="retype" class="form-control" value="<?php echo $row[2]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>First Name: </b></td>
									<td><input id="first_name" name="first_name" class="form-control" value="<?php echo $row[3]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Last Name: </b></td>
									<td><input id="last_name" name="last_name" class="form-control" value="<?php echo $row[4]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Credit Card Brand: </b></td>
									<td>
										<select name="brand">
										<?php
											// Display and determine which value the user previously entered
											for ($i = 0; $i < count($brands); $i++)
											{
												// Find pre-entered value and select it
												if ($brands[$i] == $row[5])
												{
													echo '<option selected="selected">'.$brands[$i].'</option>';
												}
												// Display remainder
												else
												{
													echo '<option>'.$brands[$i].'</option>';
												}
											}
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Credit Card Number: </b></td>
									<td><input type="password" id="credit_card_number" name="credit_card_number" class="form-control" value="<?php echo $row[6]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Security Code: </b></td>
									<td><input type="password" id="security_code" name="security_code" class="form-control" value="<?php echo $row[7]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Address: </b></td>
									<td><input id="address" name="address" class="form-control" value="<?php echo $row[8]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>City: </b></td>
									<td><input id="city" name="city" class="form-control" value="<?php echo $row[9]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>State: </b></td>
									<td>
										<select name="state">
										<?php
											// Display and determine which value was previously entered
											for ($i = 0; $i < count($us_state); $i++)
											{
												// If value user entered, select
												if ($us_state[$i] == $row[10])
												{
													echo '<option selected="selected" value='.$us_state[$i].'>'.$us_state[$i].'</option>';
												}
												// Display remainder
												else
												{
													echo '<option value='.$us_state[$i].'>'.$us_state[$i].'</option>';
												}
											}
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Zip Code: </b></td>
									<td><input id="zip_code" name="zip_code" class="form-control" value="<?php echo $row[11]; ?>"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Phone Number: </b></td>
									<td><input id="phone_number" name="phone_number" class="form-control" value="<?php echo $row[12]; ?>"></td>
								</tr>								
								<tr>
									<td colspan="3"><br><input id="submitButton" type="submit" name="updateSubmit" class="form-control btn btn-default" value="Submit"></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Empty column used for spacing -->
		<div class="col-md-2"></div>
		
	</div>
	</center>	
	<!-- /.container -->

	<!-- Render HTML footer -->
	<?php showFooter(); ?>
	
	<!-- jQuery Validate -->
    <script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js'></script>

	
	<!-- Validation Function -->
	<script>
	
		$("document").ready(function() {
			
			// Validate updateform
			$('#updateForm').validate({
				
				// Create validation rules
				rules: 
				{
					username: "required",
					password: "required",
					retype: { required: true, equalTo: "#password" },
					first_name: "required",
					last_name: "required",
					credit_card: "required",
					credit_card_number: { required: true, minlength: 13, maxlength: 19 },
					security_code: { required: true, minlength: 3, maxlength: 4 },
					address: "required",
					city: "required",
					state: "required",
					zip_code: "required",
					phone_number: "required"
				},
				// Create messages displayed if rules violated
				messages: 
				{
					username: "Please enter a username",
					password: "Please enter a password",
					retype: { required: "Please re-type password", equalTo: "Passwords do not match" },
					first_name: "Please enter your first name",
					last_name: "Please enter your last name",
					credit_card:  "Please enter your brand of credit card",
					credit_card_number: { 
						required: "Please enter your credit card number", 
						minlength: "Credit card must be 13 - 19 digits", 
						maxlength: "Credit card must be 13 - 19 digits" 
					},
					security_code: {
						required: "Please enter your security code",
						minlength: "Security code is 3 - 4 digits",
						maxlength: "Security code is 3 - 4 digits"
					},
					address: "Please enter your address",
					city: "Please enter the city in which you reside",
					state: "Please enter the state in which you reside",
					zip_code: "Please enter your zip code",
					phone_number: "Please enter your phone number"
				}
			});
		});

	</script>
	
	<!-- Overrides the default jQuery CSS -->
	<style>
		label.error 
		{
			color: #FB3A3A; <!-- Red font-->
		}
	</style>
	
</body>
</html>