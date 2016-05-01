<?php
	
	function ConnectToDB($database)
	{
		$connect = new mysqli("localhost", "student", "learn", "lesson01");
		
		return $connect;
		
	}
	
	function GetRecords($mysqli, $record, $table)
	{
		$sql = "SELECT $record FROM $table";
		
		$result = $mysqli->query($sql);
		
		if($result)
		{
			return $result;
		}
		
		else 
		{
			return false;
		}
		
	}
	
	function GetRecordsWhere($mysqli, $record, $table, $whereItem , $whereEquals)
	{
		$sqlW = "SELECT " . $record . " FROM " .$table. " WHERE " . $whereItem. " = " . $whereEquals;
		
		$result = $mysqli->query($sqlW);
		
		if($result)
		{
			return $result;
		}
		
		else 
		{
			return false;
		}
		
	}

	function InsertStudent($mysqli, $fname, $lname, $uname, $pass, $admin)
	{
		$sql = "INSERT INTO zmm_students (fname, lname, username, password, admin ) VALUES (? ,?, ?, ?, ?)";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('ssssi', $fname, $lname, $uname, $pass, $admin);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
		
	}
	
	function UpdateStudent($mysqli, $fname, $lname, $username, $password, $admin, $id)
	{
		$sql = "UPDATE zmm_students SET fname = ?, lname = ?, username = ?, password = ?, admin = ? WHERE student_id = ?";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('ssssii', $fname, $lname, $username, $password, $admin, $id);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
		
	}
	
	function DeleteStudent($mysqli, $id)
	{
		$sql = "DELETE FROM zmm_students WHERE student_id = ?";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
	}
	
	function UpdateAnnouncement($mysqli, $title, $text, $id)
	{
		$sql = "UPDATE zmm_announcements SET title = ?, text = ? WHERE announcement_id = ?";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('ssi', $title, $text, $id);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
		
	}
	
	function InsertAnnouncement($mysqli, $title, $text, $date)
	{
		$sql = "INSERT INTO zmm_announcements (title, text, date) VALUES (?, ?, ?)";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('sss', $title, $text, $date);
			if($statement->execute())
			{

			}
			$statement->close();
			
			return true;
		}
		
		else
		{
			return false;
		}
		
	}
		
	function DeleteAnnouncement($mysqli, $id)
	{
		$sql = "DELETE FROM zmm_announcements WHERE announcement_id = ?";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
	}
	
	function InsertAssignment($mysqli, $heading, $title, $text, $dateDue, $datePosted, $points)
	{
		$sql = "INSERT INTO zmm_assignments (heading, title, text, dateDue, datePosted, points) VALUES (?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('sssssi', $heading, $title, $text, $dateDue, $datePosted, $points);
			if($statement->execute())
			$statement->close();
			
			return true;
		}
		
		else
		{
			return false;
		}
	}
	// TODO
	function UpdateAssignment($mysqli, $title, $text, $dateDue, $id)
	{
		$sql = "UPDATE zmm_assignments SET title = ?, text = ?, dateDue = ? WHERE assignment_id = ?";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('sssi', $title, $text, $dateDue, $id);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
		
	}
	
	function DeleteAssignment($mysqli, $id)
	{
		$sql = "DELETE FROM zmm_assignments WHERE assignment_id = ?";
		$statement = $mysqli->stmt_init();
		
		if($statement = $mysqli->prepare($sql))
		{
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->close();
			return true;
		}
		
		else
		{
			return false;
		}
	}

	function LoadPage($title,$active)
	{
		session_start();
		
		if($_SESSION["username"] == "")
		{
			header("Location: login.html");
			
		}
		$home = "";
		$classlist = "";
		$assignments = "";
		$discussions = "";
		$resources = "";
		$grades = "";
		
		switch($active)
		{
			case "classlist": $classlist = "active"; break;
			case "assignments": $assignments = "active"; break;
			case "discussions": $discussions = "active"; break;
			case "resources": $resources = "active"; break;
			case "home": $home = "active"; break;
			case "grades": $grades = "active"; break;
		}
		
		echo '<html lang="en">

				<head>

					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta name="description" content="">
					<meta name="author" content="">

					<title>' . $title . ' - Ezlearn</title>

					<!-- Bootstrap Core CSS -->
					<link href="css/bootstrap.min.css" rel="stylesheet">
					
					<link href="css/datepicker.css" rel="stylesheet">

					<!-- MetisMenu CSS -->
					<link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

					<!-- Timeline CSS -->
					<link href="css/plugins/timeline.css" rel="stylesheet">

					<!-- Custom CSS -->
					<link href="css/sb-admin-2.css" rel="stylesheet">

					<!-- Morris Charts CSS -->
					<link href="css/plugins/morris.css" rel="stylesheet">
					
					<link href="fileinput.min.css" rel="stylesheet">

					<!-- Custom Fonts -->
					<link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
			
					<script src="js/fileinput.min.js"></script>

					<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
					<!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
					<!--[if lt IE 9]>
						<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
						<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
					<![endif]-->
					
					<script src="ckeditor/ckeditor.js"></script>
					<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
					
					<script src="js/bootstrap-datepicker.js"></script>
				</head>

				<body>

					<div id="wrapper">

						<!-- Navigation -->
						<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="index.html">EzLearn</a>
							</div>
							<!-- /.navbar-header -->

							<ul class="nav navbar-top-links navbar-right">
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">
										<i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
									</a>
									<ul class="dropdown-menu dropdown-messages">
										<li>
											<a href="#">
												<div>
													<strong>John Smith</strong>
													<span class="pull-right text-muted">
														<em>Yesterday</em>
													</span>
												</div>
												<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
											</a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="#">
												<div>
													<strong>John Smith</strong>
													<span class="pull-right text-muted">
														<em>Yesterday</em>
													</span>
												</div>
												<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
											</a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="#">
												<div>
													<strong>John Smith</strong>
													<span class="pull-right text-muted">
														<em>Yesterday</em>
													</span>
												</div>
												<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
											</a>
										</li>
										<li class="divider"></li>
										<li>
											<a class="text-center" href="#">
												<strong>Read All Messages</strong>
												<i class="fa fa-angle-right"></i>
											</a>
										</li>
									</ul>
									<!-- /.dropdown-messages -->
								</li>
				 
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">
										<i class="fa fa-user fa-fw"></i> '. $_SESSION['fname'] . ' ' . $_SESSION['lname'] .' <i class="fa fa-caret-down"></i>
									</a>
									<ul class="dropdown-menu dropdown-user">
										<li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
										</li>
										<li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
										</li>
										<li class="divider"></li>
										<li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
										</li>
									</ul>
									<!-- /.dropdown-user -->
								</li>
								<!-- /.dropdown -->
							</ul>
							<!-- /.navbar-top-links -->

							<div class="navbar-default sidebar" role="navigation">
								<div class="sidebar-nav navbar-collapse">
									<ul class="nav" id="side-menu">
										<li>
											
											<center><h3>CIS 355</h3></center>
										</li>
										<li class="active">
											<a href="home.php" class="' .$home . '"><i class="fa fa-home fa-fw"></i> Home</a>
										</li>
										<li>
											<a href="assignments.php" class="' . $assignments . '"><i class="fa fa-bar-chart-o fa-fw"></i> Assignments</a>
											
										</li>
										<li>
											<a href="resources.php" class="'. $resources . '"><i class="fa fa-book fa-fw"></i> Resources</a>
										</li>
										<li>
											<a href="discussions.php" class = "' . $discussions . '"><i class="fa fa-edit fa-fw"></i> Discussions</a>
										</li>
										<li>
											<a href="grades.php" class="' . $grades . '"><i class="fa fa-list-alt fa-fw"></i> Grades</a>
										</li>
										<li>
											<a href="classlist.php" class= "'. $classlist .'"><i class="fa fa-user fa-fw"></i> Classlist</a>
										</li>
									   
									</ul>
								</div>
								<!-- /.sidebar-collapse -->
							</div>
							<!-- /.navbar-static-side -->
						</nav>

						<div id="page-wrapper">
							<div class="row">
								<div class="col-lg-12" >
									<h1 class="page-header" >'. $title . '</h1>
								</div>';
	}
	
	function UnloadPage()
	{
		echo ' </div>
			</div>

		</div>
		<!-- /#wrapper -->

		<!-- jQuery -->
		<script src="js/jquery.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="js/plugins/metisMenu/metisMenu.min.js"></script>

		<!-- Flot Charts JavaScript -->
		<script src="js/plugins/flot/excanvas.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.js"></script>
		<script src="js/plugins/flot/jquery.flot.pie.js"></script>
		<script src="js/plugins/flot/jquery.flot.resize.js"></script>
		<script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
		<script src="js/plugins/flot/flot-data.js"></script>

		<!-- Custom Theme JavaScript -->
		<script src="js/sb-admin-2.js"></script>

	</body>

	</html>';
	}
	
