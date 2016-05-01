<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable and connect to database
	$usertable = "tpm_shopping_cart";
	$db = dbConnect($usertable);
	
	// Assign $userId from SESSION
	$userId = $_SESSION['userId'];
	
	// Execute query
	$sql = "SELECT * FROM $usertable WHERE cust_id = $userId";
	$result = $db->query($sql);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!-- Render HTML header -->
<?php showHeader(); ?>

<!-- Body -->
<body style="padding-top:80px;" background="fabric_of_squares_gray.png">

	<!-- Render Navbar -->
	<?php showNavbar(); ?>
	
    <!-- Page Content -->
    <div class="container">
	
		<!-- New Bootstrap row -->
		<div class="row">
		
			<!-- New Bootstrap column, spans 3 columns of 12 -->
			<div class="col-md-3">
				<!-- Shipping Header -->
				<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px;'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Shipping Information</h3>
					</div>
					<div class='panel-body'>
						
						<!-- Display user information from database -->
						<p><strong>Deliver To:</strong> <?php echo $_SESSION['user']." ".$_SESSION['last']; ?></p>
						<p><strong>Credit Card:</strong> <?php echo $_SESSION['card']; ?></p>
						<hr>
						<p><strong>Address:</strong> <?php echo $_SESSION['address']; ?></p>
						<p><strong>City:</strong> <?php echo $_SESSION['city']; ?></p>
						<p><strong>State:</strong> <?php echo $_SESSION['state']; ?></p>
						<p><strong>Zip-Code:</strong> <?php echo $_SESSION['zip']; ?></p>
						
					</div>
				</div>
			</div>
			
			<!-- New Bootstrap column, spans 7 columns of 12 -->
			<div class="col-md-7">
			
				<?php
					// Total cost
					$total = 0;
					// Used to store multiple prices
					$prices = array();
					
					// Render HTML table
					echo "
						<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px;'>
							<div class='panel-heading'>
								<h3 class='panel-title'>Order</h3>
							</div>
							<div class='panel-body'>
								<table class='table' cellpadding=\"5\" cellspacing=\"0\">
									<thead>
										<th></th>
										<th><strong>Manufacturer</strong></th>
										<th><strong>Model</strong></th>
										<th><strong>Color</strong></th>
										<th><strong>Price</strong></th>
										<th><strong>Quantity</strong></th>
										<th></th>
									</thead>
									<tbody>";
					
					// While $row contains values
					while ($row = $result->fetch_array())
					{
						// Display item data
						echo "
							<tr>
								<td><img src=".$row[7]."></td>
								<td>".$row[3]."</td>
								<td>".$row[4]."</td>
								<td>".$row[5]."</td>
								<td>".$row[6]."</td>
								<td>".$row[8]."</td>";
								
								// Store item price
								array_push($prices, $row[8] * $row[6]);
								
								// Accumulate total cost
								$total += $row[8] * $row[6];
							"</tr>";
					}
					echo "</tbody></table></div></div>";
				?>
			</div>
			
			<!-- New Bootstrap column, spans 2 of 12 columns -->
			<div class="col-md-2">
				<!-- Cost Header -->
				<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px;'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Total Cost</h3>
					</div>
					<div class='panel-body'>
						<?php
							// Output total cost
							for ($i = 0; $i < count($prices); $i++)
							{
								if ($i + 1 == count($prices))
								{
									echo'<p>+ $'.number_format($prices[$i], 2, '.', ',').'</p>';
								}
								else 
								{
									echo'<p> &nbsp &nbsp$'.number_format($prices[$i], 2, '.', ',').'</p>';
								}
							}
							echo'<hr><p><strong>Total: </strong>$'.number_format($total, 2, '.', ',').'</p>';
							echo'<br><a href="confirm.php"><button style="width:100%;" name="confirm" class="btn btn-default">Process Order</button></a>';
						?>
					</div>
			</div>
		</div>
	</div>
	
	<!-- Render HTML footer -->
	<?php showFooter(); ?>

</body>
</html>