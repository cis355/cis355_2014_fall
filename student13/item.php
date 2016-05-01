<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!-- Render HTML header -->
<?php showHeader(); ?>

<!-- Body -->
<body style="padding-top:70px;" background="fabric_of_squares_gray.png">

	<?php
		// Assign $usertable and connect to database
		$usertable = "tpm_".$_GET['type'];
		$db = dbConnect($usertable);
		
		// Assign $index
		$index = $_GET['id'];
		
		// Execute query
		$sql = "SELECT * FROM $usertable WHERE id = '$index'";
		$result = $db->query($sql);
		$row = $result->fetch_array();
		
		// Render Navbar
		showNavbar();
	?>
	
	<!-- Page Content -->
    <div class="container">
		
		<!-- New Boostrap row -->
        <div class="row">
			
			<!-- New Bootstrap column -->
            <div class="col-md-9">
				
				<!-- Renders item image thumbnails and item info from database -->
                <div class="thumbnail" style="box-shadow: 2px 2px 7px #888888;">
                    <img class="img-responsive" src="<?php echo $row[9]; ?>" alt="">
                    <div class="caption-full">
                        <h4 class="pull-right">$<?php echo number_format($row[5], 2, '.', ','); ?></h4>
                        <h4 style="color:red;"><?php echo $row[1]." ".$row[3]; ?></h4>
                        <p><?php echo $row[7]; ?></p>
                    </div>
                </div>
            </div>
			
			<?php
				if ($_SESSION['user'] || $_SESSION['userId'])
				{
					// Render add to cart options
					echo'
						<!-- New Bootstrap column -->
						<div class="col-md-3">
						<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
						  <div class="panel-heading">
							<h3 class="panel-title">Shopping Cart</h3>
						  </div>
						  <div class="panel-body">
							<form id="cart" method="POST" action="addToCart.php?item='/* formulate query string */.$row[0].'&type='.$_GET['type'].'">
								
								<center>
								Quantity:
								<select name="quant">';
									// Render quantity
									for ($i = 1; $i <= 10; $i++)
									{
										echo '<option>'.$i.'</option>';
									}
								echo '</select></center><br>
									  <button type="submit" style="width:100%;" name="cartSubmit" class="btn btn-default">Add to Cart</button>
								
							</form>
						  </div>
						</div>
					</div>';
				}
			?>

        </div>
    </div>
    <!-- /.container -->
	
	<!-- Render HTML footer -->
	<?php showFooter(); ?>

</body>
</html>