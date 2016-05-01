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
		// Assign usertable and connect to database
		$usertable = "tpm_".$_GET['type'];
		$db = dbConnect($usertable);
		
		// Execute query
		$sql = "SELECT * FROM $usertable";
		$result = $db->query($sql);
		
		// Get label string from query string
		// This determines which inventory page will be rendered
		if ($_GET['type'] == "acoustic")
		{
			$label = "Acoustic";
		}
		else if ($_GET['type'] == "guitars")
		{
			$label = "Electric";
		}
		else 
		{
			$label = "Bass";
		}
		
		// Render Navbar
		showNavbar();
	?>
	
	 <!-- Page Content -->
    <div class="container"><hr><br>
		<!-- New row on Bootstrap's grid -->
		<div class="row">

			<?php
				// Used to format page
				$loopCt = 0; 
				
				// While $row contains values
				while ($row = $result->fetch_array())
				{	
					// Display item's information stored in database
					echo '
					<div class="col-sm-4 col-lg-4 col-md-4">
						<div class="thumbnail" style="box-shadow: 2px 2px 7px #888888;">
							<img src="'.$row[8].'" alt="">
							<div class="caption">
								<h4 class="pull-right">$'./* formats price */ number_format($row[5], 2, '.', ',').'</h4>
								<h4><a href="item.php?id='.$row[0].'&type='.$_GET['type'].'">'.$row[1].' '.$row[3].'</a></h4>
								<ul>
									<li>'.$row[2].'</li>
									<li>'.$row[4].'</li>
									<li>'.$row[6].'</li>
								</ul>
							</div>
						</div>
					</div>';
					
					// Increment loop count
					$loopCt++;
					
					// If there are 3 columns in a row, create a new row
					if ($loopCt % 3 == 0)
					{
						echo '</div>', '<div class="row">';
					}
				}
			?>
			</div>
		</div>
	</div>
    <!-- /.container -->

	<!-- Render HTML footer -->
	<?php showFooter(); ?>

</body>
</html>