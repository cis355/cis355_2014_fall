<?php
	// Start session and include functions.php
	session_start();
	include 'functions.php';
	
	// Assign $usertable and connect to database
	$usertable = "tpm_shopping_cart";
	$db = dbConnect($usertable);
	
	// Assign $userId
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
	
	<div class="container">
		
		<!-- New Bootstrap row -->
		<div class="row">
			
			<!-- Empty column used for spacing reasons -->
			<div class="col-md-2"></div>
			
			<!-- New Boostrap column -->
			<div class="col-md-8">
			
				<?php
					// Get number of rows queried
					$numRows = mysqli_num_rows($result);
					
					// 0 rows = empty cart
					if ($numRows == 0)
					{
						echo "<center><h1>Your cart is empty <span class='glyphicon glyphicon-shopping-cart'></span></h1></center>";
					}
					else
					{	
						// Display items in Shopping Cart
						echo "
							<div class='panel panel-default' style='box-shadow: 2px 2px 7px #888888; margin-top: 20px;'>
								<div class='panel-heading'>
									<h3 class='panel-title'>Shopping Cart</h3>
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
										
							// Display item information from database
							while ($row = $result->fetch_array())
							{
								echo "
									<form method='POST' id='genForm' action='deleteCartItem.php'>
										<tr>
											<td><img src=".$row[7]."></td>
											<td>".$row[3]."</td>
											<td>".$row[4]."</td>
											<td>".$row[5]."</td>
											<td>".$row[6]."</td>
											<td>
												<select id='selection".$row[0]."' onchange='updateQuantity(".$row[0].");'>";
												
													for ($i = 1; $i <= 10; $i++)
													{
														if ($i == $row[8])
														{
															echo' <option selected="selected">'.$i.'</option>';
														}
														else
														{
															echo' <option>'.$i.'</option>';
														}
													}
													
												echo "</select>
											</td>
											<td><button class='btn-sm btn-default' type='submit' name='deleteSubmit'>
												<span class='glyphicon glyphicon-trash'></span></button></td>
										</tr>
										<input type='hidden' name='delete' value=".$row[0].">
									</form>";
							}
							echo "</tbody></table></div></div>";
					}
				?>
			
			</div>
			
			<!-- Display checkout options if items in cart -->
			<?php
				if ($numRows > 0)
				{
					echo'
						<div class="col-md-2" style="margin-top:35px;">
							<form method="POST" type="submit" action="checkout.php">
								<button type="submit" style="width:100%;" name="checkoutSubmit" class="btn btn-default">Continue to Checkout</button>
							</form>						
						</div>';
				}
			?>
			
		</div>
	</div>
	
	<!-- Render HTML footer -->
	<?php showFooter(); ?>
	
	<!-- This function updates the item quantity choesn in databse -->
	<script>
	
		function updateQuantity(idNum) 
		{
			// Get selection from listbox
			var e = document.getElementById('selection' + idNum);
			var strUser = e.options[e.selectedIndex].value;
			
			// Create query string with essential information in $_GET
			window.location.assign("updateQuantity.php?quant="+strUser+"&id="+idNum);
		}
	</script>

</body>
</html>
