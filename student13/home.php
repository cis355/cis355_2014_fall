<?php
	// Start session & include functions.php
	session_start();
	include 'functions.php';
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!-- Show header information -->
<?php showHeader(); ?>

<!-- Body -->
<body style="padding-top:80px;" background="fabric_of_squares_gray.png">

	<!-- Render the Navbar -->
	<?php showNavbar(); ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Heading Row -->
        <div class="row">
            <div class="col-md-8">
                <img class="img-responsive img-rounded" src="guitar-shop.jpg" alt="">
            </div>
            <!-- /.col-md-8 -->
            <div class="col-md-4">
                <h3>Welcome to <span class="label label-primary">Guitar Shop 13</span></h3><br>
                <p>
					In need of an electric guitar? Maybe an acoustic guitar is more your style. A bass guitar, perhaps?
					Regardless, we here at Guitar Shop 13 can provide all of the above and more! Simply create an account and explore
					our selection of fine stringed instruments!
				</p><br>
				<?php
					// If the user isn't logged in, display option to sign up
					if (!$_SESSION['user'] || !$_SESSION['userId'])
					{
						echo'<a class="btn btn-default btn-lg" href="signUp.php">Sign Up!</a>';
					}
				?>
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
        <hr>

        <!-- Call to Action Well -->
        <div class="row">
            <div class="col-lg-12">
                <div class="well text-center">
                    <b><u>Perhaps you'd rather call?</u></b> No problem. Give us a call and let our experienced sales staff 
					help you choose the instrument that is right for you!<br><br>
					<strong> Phone: 1-800-555-5555 </strong>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-4">
				<div class="panel panel-primary" style="box-shadow: 2px 2px 7px #888888;">
					<div class="panel-heading">
						<h3 class="panel-title">Acoustic Guitars</h3>
					</div>
						<div class="panel-body">
							<img src="acoustic.jpg" class="img-responsive center-block"><br>
							<p>If you're looking for an acoustic guitar, you've come to the right place! Please take a look
							   at our fine selection of acoustic guitars and we'll get you set up in no time!
							</p>
							<a class="btn btn-default" href="guitarInv.php?type=acoustic">Browse</a>
						</div>
				</div>
			</div>
            <!-- /.col-md-4 -->
            <div class="col-md-4">
				<div class="panel panel-primary" style="box-shadow: 2px 2px 7px #888888;">
					<div class="panel-heading">
						<h3 class="panel-title">Electric Guitars</h3>
					</div>
						<div class="panel-body">
							<img src="electric.jpg" class="img-responsive center-block"><br>
							<p>
								Interested in something louder than an acoustic guitar? No problem; we've got you covered. 
								Check out our outstanding collection of electric guitars today!
							</p>
							<a class="btn btn-default" href="guitarInv.php?type=guitars">Browse</a>
						</div>
				</div>
			</div>
            <!-- /.col-md-4 -->
            <div class="col-md-4">
				<div class="panel panel-primary" style="box-shadow: 2px 2px 7px #888888;">
					<div class="panel-heading">
						<h3 class="panel-title">Bass Guitars</h3>
					</div>
						<div class="panel-body">
							<img src="bass.jpg" class="img-responsive center-block"><br>
							<p>
								Perhaps you are more interested in producing pitches of a lower frequency.
								Why not take a look at our selection of bass guitars today?
							</p>
							<a class="btn btn-default" href="guitarInv.php?type=basses">Browse</a>
						</div>
				</div>
			</div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
	</div>
	
	<!-- Render HTML footer -->
	<?php showFooter(); ?>

</body>
</html>