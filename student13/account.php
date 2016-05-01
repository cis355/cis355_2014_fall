<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable & connect to database
	$usertable = "tpm_customers";
	$db = dbConnect($usertable);
	
	// SQL query to select a customer from customers table
	$sql = "SELECT * FROM $usertable WHERE cust_id = ".$_SESSION['userId']."";
	
	// Execute query
	$result = $db->query($sql);
	
	// Store query results in $row array
	$row = $result->fetch_array();
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!-- Render HTML header -->
<?php showHeader(); ?>

<!-- Body -->
<body style="padding-top:80px;" background="fabric_of_squares_gray.png">

    <?php showNavbar(); ?>

    <!-- Page Content -->
    <div class="container">
		
		<!-- New Bootstrap row -->
		<div class="row">
			
			<!-- New column that spans 3 columns. Bootstrap's grid system has 12 columns in a row -->
			<div class="col-md-3">
				
				<!-- Account heading -->
				<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px;'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Account</h3>
					</div>
					<div class='panel-body'>
						
						<!-- Update and Delete account buttons -->
						<p><a href="updateAccount.php"><strong><h6><u>Edit Account</u></h6></strong></a></p>
						<p><a href="#" data-toggle="modal" data-target="#basicModal">
						<strong><h6><u>Delete Account</u></h6></strong></a></p><br>
						
						<!-- Code for content in Bootstrap's modal, which is called via jQuery -->
						<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove">
									</span></button>
									<h4 class="modal-title" id="myModalLabel">Delete Account</h4>
									</div>
									<div class="modal-body">
										<h4>Are you sure that you'd like to delete your account?</h4>
										<p>If so, click yes and your account will be permanently deleted.</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<a href="deleteAccount.php"><button type="button" class="btn btn-primary">Yes</button></a>
								</div>
							</div>
						  </div>
						</div>
						<!-- Display user information from database -->
						<p><strong>User ID: </strong> <?php echo $row[0]; ?></p>
						<p><strong>Username: </strong> <?php echo $row[1]; ?></p>
						<p><strong>First Name: </strong> <?php echo $row[3]; ?></p>
						<p><strong>Last Name: </strong> <?php echo $row[4]; ?></p>
					
					</div>
				</div>
			</div>
			
			<!-- New Bootstrap column, spans 5 columns of 12 -->
			<div class="col-md-5">
				
				<!-- Shipping information -->
				<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px; height:180px;'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Shipping</h3>
					</div>
					<div class='panel-body'>
						<!-- Display user shipping information -->
						<p><strong>Address: </strong> <?php echo $row[8]; ?></p>
						<p><strong>City: </strong> <?php echo $row[9]; ?></p>
						<p><strong>State: </strong> <?php echo $row[10]; ?></p>
						<p><strong>Zip-Code: </strong> <?php echo $row[11]; ?></p>
					
					</div>
				</div>
			</div>			
			
			<!-- New Bootstrap column, spans 4 columns of 12 -->
			<div class="col-md-4">
				
				<!-- Payment information -->
				<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px; height:180px;'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Payment</h3>
					</div>
					<div class='panel-body'>
						
						<!-- Display user payment information -->
						<p><strong>Credit Card: </strong> <?php echo $row[5]; ?></p>
						<!-- PHP utilized only show last 4 digits of credit card number -->
						<p><strong>Credit Card Number: </strong> <?php for ($i = 0; $i < strlen($row[6]) - 5; $i++) { echo '*'; } echo substr($row[6], count($row[6]) - 5); ?></p>
						<p><strong>Security Code: </strong> <?php for ($i = 0; $i < strlen($row[7]); $i++) { echo '*'; } ?></p>
					
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Render HTML footer -->
	<?php showFooter(); ?>
	
	<!-- Launches Bootstrap Modal -->
	<script>$('#basicModal').modal(show);</script>

</body>
</html>