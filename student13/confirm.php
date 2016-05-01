<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable and connect to database
	$usertable = "tpm_shopping_cart";
	$db = dbConnect($usertable);
	
	// Execute query
	$sql = "DELETE FROM $usertable WHERE cust_id = ".$_SESSION['userId']."";
	$result = mysqli_query($db,$sql)or die(mysqli_error($db));
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!-- Render HTML header -->
<?php showHeader(); ?>

<body style="padding-top:80px;" background="fabric_of_squares_gray.png">
	
	<!-- Render Navbar -->
	<?php showNavbar(); ?>
	
    <!-- Page Content -->
    <div class="container">
		
		<!-- New Bootstrap row -->
		<div class="row">
			
			<!-- New Bootstrap column, spans 2 of 12 columns. Used for spacing. -->
			<div class="col-md-2"></div>
			
			<!-- New Bootstrap column, spans 8 of 12 columns -->
			<div class="col-md-8">
				<!-- Thank customer for giving me money -->
				<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px;'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Order Confirmation</h3>
					</div>
					<div class='panel-body'>

						<h5>Thank you for your purchase!</h5><br>
						<p>Your order is currently being processed. This process will take approximately 24 hours.</p>
						<p>Your order will be shipped in approximately 2-3 days.</p>
						<p>All purchases are <strong>final.</strong> Absolutely no returns.</p><br>
						<p>Thanks again for your business!</p>
						
					</div>
					<a href="home.php"><button style="margin-left:670px;" class="btn btn-default">Finish</button></a><br><br>
			
			</div>
			
			<!-- New Bootstrap column, spans 2 of 12 columns. Used for spacing. -->
			<div class="col-md-2"></div>
		
		</div>
	</div>
	
	<!-- Render HTML footer -->
	<?php showFooter(); ?>

</body>
</html>