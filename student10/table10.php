<?php
/************************************************************************/
/*                                                                      */ 
/* Programmer Name:  David Godi                                         */
/* Course Title:     CS316    Section 01                                */
/* Assignment No.:   Lesson 2 Due Date: #                               */
/* Instructor: Dr.:  George Corser                                      */
/*                                                                      */
/* Thanks:                                                              */
/*   Kevin Stachowski for the basic structure design using MySQLi       */
/*	 methods in a database                                              */                         
/* ==================================================================== */
/*                                                                      */
/* Program Definition:                                                  */
/*   This file will demonstrate a working data base for update, ad, and */
/*   delete, search functions                                           */
/*                                                                      */
/* ==================================================================== */
/*                                                                      */
/* Indentifier Dictionary:                                              */
/* $choice = $_POST["submit_choice"]  action the user requests          */
/*                             example: update, delete, add, view record*/                  
/* $ID = $_POST["ID"]          id selected to delete or update          */
/* $id = $_POST["id"]          primary key                              */
/* $name = $_POST["name"]      name in database                         */
/* $brand = $_POST["brand"]    products brand name                      */
/* $model = $_POST["model"]    type of model                            */
/* $year = $_POST["year"]      year item was made                       */
/* $cost = $_POST["cost"]      cost of item                             */
/* $desript = $_POST["desript"]item desription 
/*                                                                      */
/* ==================================================================== */

ini_set("session.cookie_domain", ".cis355.com");
session_start();

 /**
 * This will go through each $_POST item and create a variable of the same name.
 */
foreach($_POST as $key=>$value)
{
  $$key=$value;  // creats var of the var name from $_POST
}
 
if(isset($loc))
  $_SESSION["location"] = $loc;
/** 
 * global collection var for DB and table.
 */
   $hostname="localhost";
   $username="user01";
   $password="cis355lwip";
   $dbname="lwip";
   $usertable="table10";
   $locations = "locations";
   $users = "users";

/* Create a new mysqli object, this opens the connection. */
$mysqli = new mysqli($hostname, $username, $password, $dbname);

/* Make sure the connection is good, pass the mysqli object to the function. */
checkConnect($mysqli);

/*  if successful connection */ 
if($mysqli)
  main($mysqli);

/**
 * Purpose: This is the driver function for the application.
 * Pre:     none
 * Post:    Form data has been processed.
 */
function main($mysqli)
{ 
  /* Create the table if needed, pass the mysqli object to the function. */
  createTable($mysqli);
  
  /* process database information */
  processFormData($mysqli);    
	
  $mysqli->close();	 
}

/**
 * Purpose: This will use the MySQLi methods, inserting, updating and selecting a record with bound parameters.
 *          This function used the global collection to access global vars, 
 * Pre:     There should be some post data that needs to be processed.
 * Post:    Form data has been inserted and displayed to the user.
 */
function processFormData($mysqli)
{	
  global $submit_choice, $name;
  $mes = "";
  if($submit_choice == "add")
  {
    if(empty($name)) 
      showForm($mysqli, "Error... cannot add empty record to database.");
	else
	  addRecord($mysqli);
  }
  // update record in database when the follwing conditon are met
  else if ($submit_choice == "update") 
  { 
    if($name == "")
	  showForm($mysqli, $mes);
	else
	  update($mysqli);   
  }
  
  // delete record from database when the following conditon are met
  else if ($submit_choice == "delete")
  { 
    if(deleteRecords($mysqli))
	{
	  header("Location: " . $_SERVER['REQUEST_URI']); // redirect  
	  displayRecords($mysqli, "Record has been removed");
	}
    else
	{
	  header("Location: " . $_SERVER['REQUEST_URI']); // redirect  
      displayRecords($mysqli, "Record has not been removed");
	}
  }
  
  else if ($submit_choice == "view")
	 viewRecord($mysqli);
  else
	displayRecords($mysqli, " "); 

   
}

function addRecord($mysqli)
{	
  if(insertRecord($mysqli))  
	{  
	  header("Location: " . $_SERVER['REQUEST_URI']); // redirect  
	  displayHTMLHead();
	  displayRecords($mysqli,"A new record has been created" );
	}
    else
	{
	  header("Location: " . $_SERVER['REQUEST_URI']); // redirect  
	  displayHTMLHead();
	  displayRecords($mysqli,"error... new record can not be created" );
	}	  
}

function update($mysqli)
{
  if(updateRecord($mysqli))
  {
	header("Location: " . $_SERVER['REQUEST_URI']); // redirect  
	displayRecords($mysqli, "Record has been updated."); 
  }
  else
  {
	header("Location: " . $_SERVER['REQUEST_URI']); // redirect  
	displayRecords($mysqli, "Record was not updated.");  
  }
}

/**
 * Purpose: This function will check the MySQLi connection and display errors, if there are any.
 * Pre:     The MySQLi object must be initialized
 * Post:    the connection hase been confirmed.
 * 
 * @param   var   $mysqli  contains an initialized MySQLi object.
 */
function checkConnect($mysqli)
{ 
    /* check connection */
    if ($mysqli->connect_errno) {
        die('Unable to connect to database [' . $mysqli->connect_error. ']');
        exit();
    }
}

/**
 * Purpose: This function will check if the table exists, and create it if not.
 * Pre:     The MySQLi object must be connected.
 * Post:    The table is verified there or created.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function createTable($mysqli)
{
   global $usertable;
	$result = $mysqli->query("select id from $usertable limit 1"); 
	
    /* test select via object */
    if(empty($result))
    {
        $sql = "CREATE TABLE $usertable (";
        $sql .= "id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), ";
        $sql .= "name VARCHAR(20),";
		$sql .= "brand VARCHAR(15),";
		$sql .= "model VARCHAR(15),";
		$sql .= "year SMALLINT,";
		$sql .= "cost DECIMAL(10,2),";
		$sql .= "descript VARCHAR(30),";
		$sql .= "location_id INT,";
		$sql .= "user_id INT,";
		$sql .= "FOREIGN KEY (location_id) REFERENCES locations (location_id),";
		$sql .= "FOREIGN KEY (user_id) REFERENCES users (user_id)";
        $sql .= ")";

        if($stmt = $mysqli->prepare($sql))
        {
            /* execute prepared statement */
            $stmt->execute();
        }
    }  
}

/**
 * Purpose: if MySQL table is not empty return : else false.
 * Pre:     The MySQLi object must be connected.
 * Post:    returns true MySQL table is not empty : else false.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function isEmpty($mysqli)
{
	global $usertable;
	$result = $mysqli->query("select id from $usertable limit 1"); 
	if($result)
	  return false;
}

/**
 * Purpose: if name is in database return true: else false
 * Pre:     The MySQLi object must be connected.
 * Post:    name is returned if found database
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function inDatabase($mysqli, $line)
{
    global $usertable;
	$valid = false;
	
	if($result = $mysqli->query("SELECT * FROM $usertable"))
    {
       while($row = $result->fetch_row())
	   {
		  if ( strcasecmp( $row[1], $line ) == 0 )
		  {
			$valid = true;
			break;
		  }
       } 
	}

	return $valid;
}

/**
 * Purpose: if record in MySQL table is updated return true: else false
 * Pre:     The MySQLi object must be connected. record can't be empty
 * Post:    The table data has been updated, must have a current record
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 * note:  use form post data to upodate record into the MySQL table
 */
function updateRecord($mysqli)
{
    /* var from the post data that we will use to bind */
    global $name, $brand, $model, $descript, $year, $cost, $usertable;
    $valid = false;
	
    /* Notice the ?, this will be a bound parameter. */
    $sql = "UPDATE $usertable SET brand = ?, model = ?, year = ?, cost = ?, descript = ? WHERE name = ?";
    if($stmt = $mysqli->prepare($sql))
    {
       /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
       $result = $stmt->bind_param('ssidss', $brand, $model, $year, $cost, $descript, $name);
       if($result)
	   {
		  $stmt->execute();
		  $valid = true;
	   }
	$stmt->close();
         
    }
	return $valid;
}

/**
 * Purpose: if record is inserted return true : return false This function will table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The form data has been added to the table.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 * note:  use form post data to insert a new record into the MySQL
 */
function insertRecord($mysqli)
{
    /* vars from the post data that we will use to bind */
    global $name, $brand, $model, $descript, $year, $cost, $location, $usertable;
	$tmpUserID = getUserID($mysqli);

    $stmt = $mysqli->stmt_init();
	$sql = "INSERT INTO $usertable (name ,brand, model, year, cost, descript, location_id, user_id ) 
	        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    /* Notice the two ? in values, these will be bound parameters*/
    if ($stmt = $mysqli->prepare($sql))
    {	
      /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob, etc... */
      $result = $stmt->bind_param('sssidsii', $name, $brand, $model, $year, $cost, $descript, $location, $tmpUserID );

	  /* execute prepared statement */
	  if($result)
	   {
		  $stmt->execute();
		  $valid = true;
	   }
  
	  /* close statment */
      $stmt->close();
    }		   
}

/**
 * Purpose: if record is deleted in MySQL table return true: else retrn false.
 * Pre:     The MySQLi object must be connected.
 * Post:    The table data has been updated, record must be in table
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 * note: call getName to receive $name for deleting the right record
 */
function deleteRecords($mysqli)
{
  global $name, $usertable, $ID;
  
  $vailid = false;
  $name = getName($mysqli);
  $sql = "DELETE * FROM $usertable WHERE id = ?";
 
  if($stmt = $mysqli->prepare($sql))
  {
      // Bind params
      $stmt->bind_param('i', $ID);
		
	  /* Executes query */
	  $stmt->execute();
       echo"prpare";
	  if($stmt->fetch())
	  {
	    echo"";
	  }
	 $valid = true;  
  }
  else
    echo"<br>not prpare";
  
  return $valid;
}

/**
 * Purpose: if id is equal to $ID return name: else return empty string
 * Pre:     The MySQLi object must be connected.
 * Post:    name is returned if found database
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function getName($mysqli)
{
	global $usertable;
    $tmpUserID = getUserID($mysqli);

	  if($result = $mysqli->query("select * from $usertable"))
      {
		while($row = $result->fetch_row())
	    {
		  if ( $row[8] == $tmpUserID  ) 
		  {
			//echo "getName  equal ".$row[0]."<br>";
			return $row[1];
			break;
		  }
	    }
	  }
	  else 		
	    return "";
}

function getUserID($mysqli)
{
	global $users;
	$user = $_SESSION["user"];
	//echo "getUserID".$user."<br>";
	if($result = $mysqli->query("select * from $users"))
    {
		//echo "getUserID  if ".$user."<br>";
      while($row = $result->fetch_row())
		if ( strcasecmp( $row[1], $user ) == 0 )
		{
		  //echo "getUserID  equal ".$row[0]."<br>";
		  return $row[0]; 
		}

	  $result->close();
	}
	else
	echo "not working";
}

function showForm($mysqli, $mes)
{
  global $brand, $model, $descript, $year, $cost, $location, $user_id, $usertable;
  $name = getName($mysqli);
  
  /** 
   * if $choice equals 'update' display data and input fields for updating record 
   * else display data and input fields for adding a record
   */
  if($_POST['submit_choice'] == "update")
  {  
	 $add = "<a href='#'><span class = 'noLink'>Add</span></a>";
	 $update = "<a href='#' onclick='submitNewRecord(2)'>Update</a>";
	 
	 $id = $POST["ID"];
    $brand = $model = $descript=""; $year=0; $cost=0.0; $location=0;	
  
    if($result = $mysqli->query("SELECT * FROM table10"))
    {
	  while($row = $result->fetch_row())
	  { 	
	    if($_POST["ID"] == $row[0])
        {
		 $name = $row[1];
		 $brand = $row[2];
		 $model = $row[3];
		 $year =  $row[4];
		 $cost = $row[5];
		 $descript = $row[6];
		 $location = $row[7];   
	    }	  
	  }
	}
  }
  else
  { 
	 $add = "<a href='#' onclick='submitNewRecord(1)'>Add</a>";
	 $update = "<a href='#'><span class = 'noLink'>Edit</span></a>";
  }
  
  displayHTMLHead();
  ?>
  
  <body bgcolor="#666666">
    <div id = "header"><a href="//www.cis355.com/student14/landing.php">
      <img src="../student14/LWIP_logo.png" width="421" height="38" style="margin: 20px;"></a>
      <h2>Jet Ski</h2>
      <div>
         <form name = "basic" method="POST" action="../student14/login.php">
			<input type="text" size="9" name="username" class="form-control" placeholder="Username">
			<input type="password" size="9" name="password" class="form-control" placeholder="Password">
			<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
		 </form></div></div>
         
    <div id = "main">
      <form id='itemFrm' method='Post' action='table10.php'>  
        <input type='hidden' id= 'submit_choice' name='submit_choice' value=''>
        <div id="banner"><h4 class="requiredFields">please fill out all fields marked with *</h4></div>
  
        <div id = "tableContainer">  
        <table>
          <tr><td><span>*</span>name</td><td><input type='edit' name='name' value='<?php echo $_SESSION["user"]?>' size=30 readonly></td></tr>
          <tr><td><span>*</span>brand</td><td><input type='edit' name='brand' value='<?php echo $brand?>' size=30></td></tr>
	      <tr><td><span>*</span>model</td><td><input type='edit' name='model' value='<?php echo $model?>' size=30></td></tr>
          <tr><td><span>*</span>year</td><td><input type='edit' name='year' value='<?php echo $year?>' size=4 maxlength=4></td></tr>
	      <tr><td><span>*</span>cost</td><td><input type='edit' name='cost' value='<?php echo $cost?>' size=10></td></tr>
	      <tr><td>description</td><td><input type='edit' name='descript' value='<?php echo $descript?>' size=30></td></tr>
          <tr><td>Location</td>
              <td><select name="location"><?php getLocationSelect($mysqli);?></select></td></tr></table></div>
              
        <div id="navbar">
          <ul class="lnavbar">
            <li><a href="javascript: submitNewRecord(0)" >View</a></li>
            <li><?php echo $update; ?></li>
            <li><?php echo $add; ?></li></ul>
          <ul class="rnavbar">
            <li><a href="javascript: resetForm()" >Clear</a></li></ul></div>
            
        <div><h3><?php echo $msg ?><h3></div>
    </div></form></body></html>
  <?php	
}

function viewRecord($mysqli)
{
  $id = $POST["ID"];
  $brand = $model = $descript=""; $year=0; $cost=0.0; $location=0;	
  
  if($result = $mysqli->query("SELECT * FROM table10"))
  {
	while($row = $result->fetch_row())
	{ 	
	  if($_POST["ID"] == $row[0])
      {
		  echo"ID  ".$_SESSION["ID"];
		 $name = $row[1];
		 $brand = $row[2];
		 $model = $row[3];
		 $year =  $row[4];
		 $cost = $row[5];
		 $descript = $row[6];
		 $location = $row[7];   
	  }
	  
	}
	
  }
  else
    echo"<br>no query";
 
 	
    displayHTMLHead();
  ?>
  
  <body bgcolor="#666666">
    <div id = "header"><a href="//www.cis355.com/student14/landing.php">
      <img src="../student14/LWIP_logo.png" width="421" height="38" style="margin: 20px;"></a>
      <h2>Jet Ski</h2>
      <div>
         <form name = "basic" method="POST" action="../student14/login.php">
			<input type="text" size="9" name="username" class="form-control" placeholder="Username">
			<input type="password" size="9" name="password" class="form-control" placeholder="Password">
			<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
		 </form></div></div>
         
    <div id = "main">
      <form id='itemFrm' method='Post' action='table10.php'>  
        <input type='hidden' id= 'submit_choice' name='submit_choice' value=''>
        <div id="banner"><h4 style="color:white; padding-left: 40px; padding-top:20px; padding-bottom:20px;">View</h4></div>
  
        <div id = "tableContainer">  
        <table>
          <tr><td>brand</td><td><input type='edit' name='name' value='<?php echo $name?>' size=30 readonly></td></tr>
          <tr><td>brand</td><td><input type='edit' name='brand' value='<?php echo $brand?>' size=30 readonly></td></tr>
	      <tr><td>model</td><td><input type='edit' name='model' value='<?php echo $model?>' size=30 readonly></td></tr>
          <tr><td>year</td><td><input type='edit' name='year' value='<?php echo $year?>' size=4 maxlength=4 readonly></td></tr>
	      <tr><td>cost</td><td><input type='edit' name='cost' value='<?php echo $cost?>' size=10 readonly></td></tr>
	      <tr><td>description</td><td><input type='edit' name='descript' value='<?php echo $descript?>' size=30 readonly></td></tr>
          <tr><td>Location</td><td><?php echo getLocationByID($mysqli, $location);?></td></tr></table></div>
              
        <div id="navbar">
          <ul class="lnavbar">
            <li><a href="table10.php" onClick="submitNewRecord(0)">View</a></li>
            <li><?php echo $update; ?></li>
            <li><?php echo $add; ?></li></ul></div>
            
        <div><h3><?php echo $msg ?><h3></div>
    </div></form></body></html>
  <?php		
}

/**
 * Purpose: if $loc is in database display location
 * Pre:     The MySQLi object must be connected.
 * Post:    location in database is displayed
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function getLocationByID($mysqli, $loc)
{
  global $locations;
 
  if($result = $mysqli->query("SELECT * FROM $locations"))
    {
       while($row = $result->fetch_row())
	   {		   
		  if ( $row[0] == $loc ) 
		  {
			return $row[1];
			break;
		  }
       } 
	}
  $result->close();
}

function getLocationSelect($mysqli)
{
  global $locations;

  if($result = $mysqli->query("SELECT * FROM $locations"))
  {
    while($row = $result->fetch_row())
	{	
	  echo '<option value="'.$row[0].'">'.$row[1].'</option>';	   
	}
  }
  $result->close();
}

/**
 * Purpose: if user is login This function will return log off link 
 *          else return log in link
 * Pre:     none
 * Post:    returns log off link if user is login
 *          else return log in link
 */
function isLogin()
{
  return ($_SESSION["user"]) ? "<a href='../student14/logout.php'>Log Off</a>" : "<a href='../student14/login.php'>Log In</a>";	
}

/**
 * Purpose: This function will select data from the MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The MySQL data is displayed to the user.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 * @param   var   $mes  displays message if the action was succesful or not
 */
function displayRecords($mysqli, $mes)
{ 
  global $usertable;
  
  displayHTMLHead();
  ?><body>
    <div id = "header"><a href="//www.cis355.com/student14/landing.php">
         <img src="../student14/LWIP_logo.png" width="421" height="38" style="margin: 20px;"></a>
         <h2>Jet Ski</h2>
         <div>
         <form name = "basic" method="POST" action="../student14/login.php">
			<input type="text" size="9" name="username" class="form-control" placeholder="Username">
			<input type="password" size="9" name="password" class="form-control" placeholder="Password">
			<button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
		 </form></div></div>
      
    <div id = "database_main">
	<form id='basic1' method='Post' action='table10.php'>  
      <input type='hidden' id='submit_choice' name='submit_choice' value=''> 
      <input type='hidden' id='ID' name='ID' value=''> 
      <input type='hidden' id='loc' name='loc' value=''> 
      <div id="datbase_header">
        <div class = "selectionBox" id="selectionBoxLeft"><img src="images/arrowDown.gif" width="25" height="25">
           <ul><li>My Account</li>
               <li>Add an Item</li>
               <li><?php echo isLogin()?></li>
               <li><a href='../student14/signup.php'>Sign Up</a></li></ul></div>
        <h4 id="userHeader">logged in:  
          <?php echo displayUser();?>  
        </h4>
        
        <div class = "selectionBox" id="selectionBoxRight"><img src="images/arrowDown.gif" width="25" height="25">  
          <ul><li><a href="#" onclick = "chooseLocation(0) ">All</a></li>	
		      <?php getLocations($mysqli); ?>
          </ul></div>
        <h4 id="locHeader">
        <?php echo displayLocation($mysqli) ?>
        </h4>
       
        <ul id="searchBox"><li><a href="#" onClick=" chooseBySearch()">Go</a></li></ul> 
        <input type="text" size="15" name="search_Box" value="">
        
      </div>
    
      <div id="databaseTable">

      <table>
        <tr><th width='30' class='idHeader'><div>ID</div></th>
            <th style = "width:10%"><div>Name</div></th>
            <th style = "width:10%"><div>Location</div></th>
            <th style = "width:10%"><div>Brand</div></th>
            <th style = "width:10%"><div>Model</div></th>
            <th style = "width:5%"><div>Year</div></th>
            <th style = "width:45%"><div>Cost</div></th><th></th></tr>
            
   <!-- exit html mode --> 
   
   <?php
   
   global $usertable, $search_Box;
   
   // get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM $usertable");
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();
	
	// if records > 0 in mysql table, then populate html table, 
	// else display "no records" message
	
	if( $countvalue > 0 && $_SESSION["location"] != 0 )
	  populateTableByLoc($mysqli, $mes); // populate html table, from mysql table
	else if(strlen($search_Box) != 0 )
	  populateTableBySearch($mysqli);
	else
      populateTable($mysqli, $mes);	
	?>  
 
    </body></html>  
   <?php
}

/**
 * Purpose: if $_SESSION["user"] display user:else display 'not signed in'
 * Pre:     none
 * Post:    if $_SESSION["user"] display user:else not signed in
 */
function displayUser()
{
  if($_SESSION["user"]) 
    return $_SESSION["user"];
  else 
    return "not signed in";
}

/**
 * Purpose: if $_SESSION["location"] display user:else display 'Location'
 * Pre:     The MySQLi object must be connected.
 * Post:    if $_SESSION["user"] display user:else not signed in
 */
function displayLocation($mysqli)
{
  if ( $_SESSION["location"] != 0 )
	return getLocation($mysqli); 
  else 
	return "Location";
}

/**
 * Purpose: if $loc is in database display location
 * Pre:     The MySQLi object must be connected.
 * Post:    location in database is displayed
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function getLocation($mysqli)
{
  global $locations, $loc;
 
  if($result = $mysqli->query("SELECT * FROM $locations"))
    {
       while($row = $result->fetch_row())
	   {
		  if ( $row[0] == $loc ) 
		  {
			return $row[1];
			break;
		  }
       } 
	}
  $result->close();
}

/**
 * Purpose: display rows in mysql table only with $_location["location"]
 * Pre:     The MySQLi object must be connected, $_location["location"] has value
 * Post:    display rows in mysql table only with $_location["location"]
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function getLocations($mysqli)
{
  global $locations;

  if($result = $mysqli->query("SELECT * FROM $locations"))
  {
    while($row = $result->fetch_row())
	{		
	  ?> <li><a href="#" onClick=" <?php echo "chooseLocation('$row[0]')" ?>"> <?php echo $row[1] ?> </a></li> <?php	   
	}
  }
  $result->close();
    
}

/**
 * Purpose: display rows in mysql table only with $_POST["search_Box"]
 * Pre:     The MySQLi object must be connected
 * Post:    display rows in mysql table only with $_POST["search_Box"]
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function populateTableBySearch($mysqli)
{
  global $usertable; 
  $search_Box = $_POST['search_Box'];
  $mes = "Search Results";
  if($result = $mysqli->query("SELECT * FROM $usertable WHERE brand LIKE '%$search_Box%' OR model LIKE '%$search_Box%' 
                               OR descript LIKE '%$search_Box%' OR name LIKE '%$search_Box%'"))
  {
	while($row = $result->fetch_row())
	{	
	    echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" .getLocationByID($mysqli, $row[7]). "</td>";	  
		for($i = 2; $i < 6; $i++)
		  echo "<td>" . $row[$i] . "</td>";
		  
		if($_SESSION["id"] == $row[8])
		    showButtons($row[0]); 
		else
		{
		  ?> <td><div id="database_navbar" style="width:200px"><ul>
              <li><a href="#" onClick="<?php echo "submitform('view', '$row[0]' )" ?>">View</a></li></ul></div></td>

		  <?php  
		}
			
		echo "</tr>";


	}
	
	showAdd($mes);	
	$result->close();
  }
  
}

/**
 * Purpose: display rows in mysql table only with $_SESSION["location"]
 * Pre:     The MySQLi object must be connected
 * Post:    display rows in mysql table only with $_SESSION["location"]
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function populateTableByLoc($mysqli, $mes)
{
  global $usertable;
  $mes = "populate Table By Loc";
  if($result = $mysqli->query("SELECT * FROM $usertable"))
  {
	while($row = $result->fetch_row())
	{
	  if($_SESSION["location"] == $row[7])
      {	
	    echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" .getLocationByID($mysqli, $row[7]). "</td>";	  
		for($i = 2; $i < 6; $i++)
		  echo "<td>" . $row[$i] . "</td>";
		 
		if($_SESSION["id"] == $row[8])
		    showButtons($row[0]); 
		else
		{
		  ?> <td><div id="database_navbar" style="width:200px"><ul>
              <li><a href="#" onClick="<?php echo "submitform('view', '$row[0]' )" ?>">View</a></li></ul></div></td>

		  <?php  
		}
			
		echo "</tr>";			
	  }
	}
	
	showAdd($mes);	
  }
  $result->close();
}

/**
 * Purpose: display rows in in mysql table
 * Pre:     The MySQLi object must be connected
 * Post:    display rows in in mysql table
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function populateTable($mysqli, $mes)
{
  global $usertable;
  //$mes = "populate Table";
  if($result = $mysqli->query("SELECT * FROM $usertable"))
  {
	while($row = $result->fetch_row())
	{ 	
	    echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" .getLocationByID($mysqli, $row[7]). "</td>";	  
		for($i = 2; $i < 6; $i++)
		  echo "<td>" . $row[$i] . "</td>";
		  
	    if($_SESSION["id"] == $row[8])
		    showButtons($row[0]);
		else
		{
		  ?> <td><div id="database_navbar" style="width:200px"><ul>
              <li><a href="#" onClick="<?php echo "submitform('view', '$row[0]' )" ?>">View</a></li></ul></div></td>

		  <?php  
		}
		echo "</tr>";	
	  
	}
	
    showAdd($mes);
  }
  $result->close();
}

/**
 * Purpose: if $_SESSION["id"] show add button
 * Pre:     The MySQLi object must be connected
 * Post:    add button is idsplayed if $_SESSION["id"]
 * 
 * @param   var   $mes  contains message
 */
function showAdd($mes)
{
  if($_SESSION["id"])
    {
	  ?></table></div></form></div> <!-- end databaseTable -->
        <div id='addButton'><div><a href="#" onClick=" <?php echo "submitform('add')" ?>">Add</a></div></div> 
        <div><h3 style="float:left;margin: 40px"><?php echo $mes ?></h3></div>
	  <?php 
    }
    else
	{
	  echo "</table></form></div></div>
            <div><h3 style='float:left;margin: 40px'>".$mes."</h3></div>";
	}	
}

/**
 * Purpose: display edit and delete buttons
 * Pre:     The MySQLi object must be connected, $_SESSION["id"] has value
 * Post:    display edit and delete buttons
 */
function showButtons($idNum)
{
  ?><td><div id='database_navbar' style='width:200px'><ul>
            <li><a href="#" onClick="<?php echo "submitform('view', '$idNum' )" ?>">View</a></li>  
            <li><a href="#" onClick=" <?php echo "submitform('update', '$idNum' )" ?>">Edit</a></li> 
            <li><a href="#" onClick=" <?php echo "submitform('delete', '$idNum' )" ?>" >Delete</a></li></ul>
            </div></td> <?php
}

function displayHTMLHead()
{
echo "<!DOCTYPE html><html><head><meta name='author' content='David Godi'>  
  <title>Jet Ski Database</title>
  <link href='css/main.css' rel='stylesheet' type='text/css' />
  <link href='css/pages.css' rel='stylesheet' type='text/css' />
  <script src='scripts/jquery-1.7.2.min.js'></script>
  <script src='scripts/tableFormAction.js'></script>
  <script src='scripts/validate.js'></script></head>";
}

?>