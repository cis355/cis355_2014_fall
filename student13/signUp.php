<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable & connect to database
	$usertable = "tpm_customers";
	$db = dbConnect($usertable);
	
	// If $_POST[] (this works like a sticky form)
	if (isset($_POST['signupSubmit']))
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
		$sql = "INSERT INTO $usertable VALUES (NULL, '$username', '$password', '$firstName', 
		'$lastName', '$creditCard', '$creditCardNum', '$security', '$address', '$city', '$state', $zipCode,'$phoneNum')";
		
		// Execute query
		$result = mysqli_query($db,$sql)or die(mysqli_error($db));
		
		// Redirect if query successful
		if ($result)
		{
			header("Location: home.php");
			exit();
		}
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
			
			<!-- New Boostrap column -->
			<div class="col-md-8"> <br>
				<!-- Render sign up form -->
				<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
					<div class="panel-heading"><b>Sign Up</b></div>
					<div class="panel-body">
						<form id="signupForm" method="POST" action="signUp.php">
							<table class="table borderless">
								<thead>
									<td style="vertical-align: middle; width:200px;"><b>Email Address: </b></td>
									<td><input id="username" type="text" name="username" class="form-control" placeholder="Email"></td><td></td>
								</thead>
								<tr>
									<td style="vertical-align: middle;"><b>Password: </b></td>
									<td><input type="password" id="password" name="password" class="form-control" placeholder="Password"></td><td></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Retype Password: </b></td>
									<td><input type="password" id="retype" name="retype" class="form-control" placeholder="Retype Password"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>First Name: </b></td>
									<td><input id="first_name" name="first_name" class="form-control" placeholder="First Name"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Last Name: </b></td>
									<td><input id="last_name" name="last_name" class="form-control" placeholder="Last Name"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Credit Card Brand: </b></td>
									<td>
										<select name="brand">
											<option value="Visa">Visa</option>
											<option value="MasterCard">MasterCard</option>
											<option value="American Express">American Express</option>
											<option value="Discover">Discover</option>
										</select>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Credit Card Number: </b></td>
									<td><input type="password" id="credit_card_number" name="credit_card_number" class="form-control" placeholder="Credit Card Number"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Security Code: </b></td>
									<td><input type="password" id="security_code" name="security_code" class="form-control" style="width:110px;" placeholder="Security Code"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Address: </b></td>
									<td><input id="address" name="address" class="form-control" placeholder="Address"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>City: </b></td>
									<td><input id="city" name="city" class="form-control" placeholder="City"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>State: </b></td>
									<td>
										<!-- List 50 state abbrev. -->
										<select name="state">
											<option value="AL">AL</option>
											<option value="AK">AK</option>
											<option value="AZ">AZ</option>
											<option value="AR">AR</option>
											<option value="CA">CA</option>
											<option value="CO">CO</option>
											<option value="CT">CT</option>
											<option value="DE">DE</option>
											<option value="DC">DC</option>
											<option value="FL">FL</option>
											<option value="GA">GA</option>
											<option value="HI">HI</option>
											<option value="ID">ID</option>
											<option value="IL">IL</option>
											<option value="IN">IN</option>
											<option value="IA">IA</option>
											<option value="KS">KS</option>
											<option value="KY">KY</option>
											<option value="LA">LA</option>
											<option value="ME">ME</option>
											<option value="MD">MD</option>
											<option value="MA">MA</option>
											<option value="MI">MI</option>
											<option value="MN">MN</option>
											<option value="MS">MS</option>
											<option value="MO">MO</option>
											<option value="MT">MT</option>
											<option value="NE">NE</option>
											<option value="NV">NV</option>
											<option value="NH">NH</option>
											<option value="NJ">NJ</option>
											<option value="NM">NM</option>
											<option value="NY">NY</option>
											<option value="NC">NC</option>
											<option value="ND">ND</option>
											<option value="OH">OH</option>
											<option value="OK">OK</option>
											<option value="OR">OR</option>
											<option value="PA">PA</option>
											<option value="RI">RI</option>
											<option value="SC">SC</option>
											<option value="SD">SD</option>
											<option value="TN">TN</option>
											<option value="TX">TX</option>
											<option value="UT">UT</option>
											<option value="VT">VT</option>
											<option value="VA">VA</option>
											<option value="WA">WA</option>
											<option value="WV">WV</option>
											<option value="WI">WI</option>
											<option value="WY">WY</option>
										</select>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Zip Code: </b></td>
									<td><input id="zip_code" name="zip_code" class="form-control"  style="width:100px;" placeholder="Zip Code"></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b>Phone Number: </b></td>
									<td><input id="phone_number" name="phone_number" class="form-control" style="width:200px;" placeholder="Phone Number"></td>
								</tr>								
								<tr>
									<td colspan="3"><br><input id="submitButton" type="submit" name="signupSubmit" class="form-control btn btn-default" value="Submit"></td>
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
	
	<!-- jQuery Validate (great way to validate) -->
    <script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js'></script>

	
	<!-- Validation Function -->
	<script>
	
		$("document").ready(function() {
			
			// Validate sign up form
			$('#signupForm').validate({
				
				// Set up validation rules
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
					zip_code: { required: true, minlength: 5, maxlength: 5 },
					phone_number: { required: true, minlength: 10, maxlength: 10}
				},
				// Set messages to user if rules are violated
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
					zip_code: {
						required: "Please enter your zip code",
						minlength: "Zip code must be 5 digits",
						maxlength: "Zip code must be 5 digits"
					},
					phone_number: {
						required: "Please enter your phone number",
						minlength: "Phone number must be 10 digits",
						maxlength: "Phone number must be 10 digits"
					},
				}
			});
		});

	</script>
	
	<!-- Overrides the default jQuery CSS -->
	<style>
		label.error 
		{
			color: #FB3A3A; <!-- Red validation font-->
		}
	</style>
	
</body>
</html>