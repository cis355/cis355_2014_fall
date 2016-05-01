<?php
ini_set("session.cookie_domain", ".cis355.com");
session_start();

ini_set('display_errors', 1);
error_reporting(e_all);

if($_GET['loggingOut'] == "true")
 {
  $_SESSION['user'] = "";
  $_SESSION['password'] = "";
  $_SESSION['id'] = "";
 }


$error = "";

if(isset($_POST['loginSubmit']))
 {

  $error = '<div class="alert alert-danger" role="alert">
			<b>Login Error:</b> Please enter a valid username and password.</div>';

  $hostname="localhost";
  $username="student";
  $password="learn";
  $dbname="lesson01";
  $usertable="LDC_UserTable";

  $mysqli = new mysqli($hostname, $username, $password, $dbname);

  $email				= $_POST['email'];
  $password				= $_POST['password'];

  $temp = Submit($mysqli, $email, $password);

	if($temp == true)
	 {
	  header("Location: //cis355.com/student07/Setup.php");
	 }

 }
 
# ---------- Submit -----------------------------------------------------
function Submit($mysqli, $email, $password)
{
 $result = $mysqli->stmt_init();

 $sql = "SELECT * FROM LDC_UserTable WHERE email = ? AND password = ?";
 
	if($result = $mysqli->prepare($sql))
	 {
	    $result->bind_param('ss', $email, $password);
		if($result->execute())
		 {
		  $result->bind_result($id, $email, $password);
		  
		  if($result->fetch())
		   {
		    $_SESSION['id'] = $id;
		    $_SESSION['user'] = $email;
		    $_SESSION['password'] = $password;
   	        return true;
		   }
	     }
	 }
 return false;	 
} 
?>

<html> 
	 <head>
	 <title>Golf_Course_Score.php</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>	
     	 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <style>
    body {
        padding-top: 70px;
    }
    </style>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>


 </head>
 <body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="//www.cis355.com/student07/Golf_Course_Score.php" class = "navbar-brand">Golf Course Score Login</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
		<div class="col-md-4" style="margin-top: 40px;">
		<br>
		<div class="panel panel-default" style="box-shadow: 2px 2px 7px #888888;">
			<div class="panel-heading"><b>Login</b></div>
				<div class="panel-body">
				

				 <form method="POST" action="login.php">
					<input type="text" size="10" name="email" class="form-control" value="" placeholder="Email">
					<input type="password" size="10" name="password" style="margin-top: 5px;" class="form-control" placeholder="Password"><br>
				<?php	
					echo $error;
				?>	
					<button type="submit" name="loginSubmit" style="width: 100%;" class="btn btn-success">Submit</button>
				</form>

				
				</div>
		</div>
		<a href="Golf_Course_Score.php" style="text-decoration: none;">
		<span class="glyphicon glyphicon-arrow-left" style="padding-right:3px;">
		</span> Back to Home</a>
		
		</div>
		Email: admin	Password: password
		</div>
	</div>
 </body>
 </html>