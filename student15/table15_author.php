<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>LWIP Camera Table</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="css/theme.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Link to Google's jQuery JavaScript library -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container-fluid theme-showcase" role="main">
  <div class="jumbotron">
	  <h2>LWIP Camera Author</h2>
  </div>
  <p>
    Hello, my name is Caleb Miller and I am the creator of Table 15 (cameras) for the Look What I Paid website. I am a senior at SVSU and I am 
    expecting to graduate at the end of the winter 2015 semester. I had no experience with PHP when this class started, but now I feel 
    comfortable working with the language thanks to the in class lessons and my studies outside of the classroom.
  </p>
  <p>
    Table 15 uses a “main” PHP file to execute database queries, and receives/passes data to each of the forms: insert, update, view, and list. 
    The forms themselves contain small amounts of PHP to load header content and PHP classes used to display data. Utilizing a main PHP file 
    eliminates duplicate POST requests since the main file always redirects to a view.
  </p>
  <p>
    Improvements I can make to this table is to following the Model-View-Controller design pattern.  I would define a class to act as a layer to my 
    cameras table. This model will handle retrieving and saving data to the table, and would perform validation. My forms would act as views that 
    would merely show the data to the user. The controller would facilitate interaction between the model and the different views. This is the
    design pattern I am currently using for my individual project, using a PHP framework known as Laravel.
  </p>
  <div class="col-sm-2">
    <a class="btn btn-default btn-block" href="table15_confirmation.php" role="button">Back To List</a>
  </div>
</div>
</body>
</html>