<?php
/**
 * Purpose: Test the "MySQLi" methods for working with a MySQL database.
 *              - connect via objects
 *              - bind parameters
 *              - get results via object properties
 *          It will show examples of functions and variable scope.
 *              - global vs local vars
 *              - calling functions and parameters
 *          It will show examples of PHPDoc compatible commenting.
 *              - multi-line comments
 *              - auto complete (works in Netbeans, not in Notepad++)
 *
 * Author:  Kevin Stachowski
 * Date:    9/14/2014
 * Notes:   This builds on items covered in lesson02.php for CIS355 Fall 14
 *
 * External resources: This application depends on access to a mysql DB.
 *                     Although the DB connection and table names are defined
 *                     as global vars, there is code that depends on the table
 *                     having certain field names.
 */

/**
 * This will go through each $_POST item and create a variable of the same name.
 * For example if there is a $_POST["name"], it will create the var $name that
 * contains the vaule in $_POST["name"]. In a foreach the collection var comes 
 * first, then vars used to iterate across each child.
 */
foreach ($_POST as $key => $value) {
  /**
   * The $key is the named index in $_POST, $value is that indexes value.
   * Adding another $ to $key turns it into a variable variable name.
   * You can also manually create and assign each one:
   *   $name = $_POST["name"];
   *   $descript = $_POST["descript"];
   */
  $$key = $value;
}

/* Now that we have any post vars that were sent, we can check if we should
   load the html form or process post data. */


/* If there is posted data (if $name has a value), show the data and start
 * the main function. If not show the html form.
 * Notice the ! means not.
 */
if (!$name) {
  // Go back to form
  header('Location: lesson02.html');
}
else {
  // HEREDOC produces a multi-line string
  $string = <<< END
  <p>The following information was submitted from the form:</p>
  <table width="450" border="0"><td style="BORDER: #C3E9C1 3px solid;"><table border="0" width="100%" cellpadding="5" cellspacing="0">
    <tr>
      <td width="35%">name</td><td>$name</td>
    </tr>
    <tr>
      <td width="35%">descrip</td><td>$descrip</td>
    </tr>
    <tr>
      <td width="35%">make</td><td>$make</td>
    </tr>
    <tr>
      <td width="35%">model</td><td>$model</td>
    </tr>
    <tr>
      <td width="35%">price</td><td>$price</td>
    </tr>
    <tr>
      <td width="35%">cond</td><td>$cond</td>
    </tr>
    <tr>
      <td width="35%">title</td><td>$title</td>
    </tr>
    <tr>
      <td width="35%">user_id</td><td>$user_id</td>
    </tr>
	<tr>
      <td width="35%">zipcode</td><td>$zipcode</td>
    </tr>
  </table>
END;

  echo $string;
  
  /** These vars will be part of the global collection.
   *  These will need to be changed to your respective DB and table.
   */
  $hostname  = "localhost";
  $username  = "student";
  $password  = "learn";
  $dbname    = "lesson01";
  $usertable = "table15";
  
  /* Main entry point for processing.*/
  main();
}

/**
 * Purpose: This is the driver function for the application.
 * Pre:     This is called only if there is post data.
 * Post:    Form data has been processed.
 */
function main()
{
  echo "<br><br><br>Begin processing of form data...<br>";
  processFormData();
  echo "Done!<br>";
  showFormBtn();
}

/**
 * Purpose: This will use the MySQLi methods, inserting, updating and selecting
 *          a record with bound parameters. This function used the global
 *          collection to access global vars, they could also be passed into 
 *          the function via parameters.
 * Pre:     There should be some post data that needs to be processed.
 * Post:    Form data has been inserted and displayed to the user.
 */
function processFormData()
{
  /** 
   * Pass in global vars to local scope.
   * Without this it will create new instances in memory for the connection
   * string vars.
   * If the vars are null, the mysqli object will attempt to use the default 
   * use set in the php.ini file.
   */
  global $hostname, $username, $password, $dbname;
  
  /* Create a new mysqli object, this opens the connection. */
  $mysqli = new mysqli($hostname, $username, $password, $dbname);
  
  /* Make sure the connection is good, pass the mysqli object to the function. */
  checkConnect($mysqli);
  
  /* Create the table if needed, pass the mysqli object to the function. */
  createTable($mysqli);
  
  /* Insert a record to the MySQL table, pass the mysqli object to the function. */
  insertRecord($mysqli);
  
  /* Update a record on the MySQL table, pass the mysqli object to the function. */
  //updateRecord($mysqli);    // This is function is just a template
  
  /* Select records from the Mysql table, pass the mysqli object to the function. */
  selectRecords($mysqli);
  
  /* Close connection, call close method of the mysqli object */
  $mysqli->close();
}

/**
 * Purpose: This function will check the MySQLi connection and display errors, 
            if there are any.
 * Pre:     The MySQLi object must be initialized
 * Post:    the connection hase been confirmed.
 *
 * @param   var   $mysqli  contains an initialized MySQLi object.
 */
function checkConnect($mysqli)
{
  
  /* Check connection */
  if ($mysqli->connect_errno) {
    die('Unable to connect to database [' . $mysqli->connect_error . ']');
    exit();
  }
  echo "Connected!<br>";
}

/**
 * Purpose: This function will use the form post data to insert a new record into the MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The form data has been added to the table.
 *
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function insertRecord($mysqli)
{
  /* Vars from the post data that we will use to bind */
  global $name, $descrip, $make, $model, $price, $cond, $title, $user_id,
         $zipcode, $usertable;
         
  $insertStatement = <<< END
  INSERT INTO $usertable (name, descrip, make, model, price, 
                          cond, title, user_id, zipcode)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
END;
  
  /* Initialise the statement. */
  $stmt = $mysqli->stmt_init();
  /* Notice the two ? in values, these will be bound parameters */
  if ($stmt = $mysqli->prepare($insertStatement)) {
    /* Bind parameters. Types: s = string, i = integer, 
       d = double,  b = blob, etc... */
    $stmt->bind_param('sssssssss', $name, $descrip, $make, $model, $price, 
                      $cond, $title, $user_id, $zipcode);
    /* Execute prepared statement */
    $stmt->execute();
    /* Close statement */
    $stmt->close();
    echo "Inserted!<br>";
  }
}

/**
 * Purpose: This function will update some data on a MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The table data has been updated
 *
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function updateRecord($mysqli)
{
  /* var from the post data that we will use to bind */
  global $name, $usertable;
  
  /* Notice the ?, this will be a bound parameter. */
  $sql = "UPDATE $usertable SET descrip='updated!' WHERE name=?";
  if ($stmt = $mysqli->prepare($sql)) {
    /* Bind parameters. Types: s = string, i = integer, d = double,
       b = blob, etc... */
    $stmt->bind_param('s', $name);
    
    /* Execute prepared statement */
    $stmt->execute();
    /* Call the close method for statement object */
    $stmt->close();
  }
  echo "Updated!<br>";
}

/**
 * Purpose: This function will select data from the MySQL table.
 * Pre:     The MySQLi object must be connected.
 * Post:    The MySQL data is displayed to the user.
 *
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function selectRecords($mysqli)
{
  global $usertable;
  
  /* Test select via object */
  if ($stmt = $mysqli->prepare("SELECT * FROM $usertable")) {
    /* Execute prepared statement */
    $stmt->execute();
    
    /* Bind result vars */
    $stmt->bind_result($id, $name, $descrip, $make, $model, $price,
                       $cond, $title, $user_id, $zipcode);
    
    /* Read while fetch returns data */
    $html = <<< END
    <br> Data selected from the MySQL table.
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Make</th>
        <th>Model</th>
        <th>Price</th>
        <th>Condition</th>
        <th>Title</th>
        <th>User ID</th>
		<th>Zip Code</th>
      </tr>
END;
    echo $html;
    while ($stmt->fetch()) {
      /* Work with bound result vars */
      $html = <<< END
      <tr>
        <td>$id</td>
        <td>$name</td>
        <td>$descrip</td>
        <td>$make</td>
        <td>$model</td>
        <td>$price</td>
        <td>$cond</td>
        <td>$title</td>
        <td>$user_id</td>
		<td>$zipcode</td>
      </tr>
END;
      echo $html;
    }
    echo "</table>";
    $stmt->close();
  }
}

/**
 * Purpose: This will show the html form button to the user.
 *          Even though its calling the same page since there is no post vars 
            it will load the html from for the user.
 * Pre:     It should be called at the end of data processing.
 * Post:    The button is displayed to the user.
 */
function showFormBtn()
{
  $html = <<< END
  <br>
  <form>
    <button onclick="history.go(-1);">Back To Form</button>
  </form>
END;
  echo $html;
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
  
  /* Test select via object */
  if ($result = $mysqli->query("SELECT id FROM $usertable LIMIT 1")) {
    /* Fetch results as object
       (since there is only 1 row, I don't need a while loop here). */
    $row = $result->fetch_object();
    
    /* The fields in the results come back as properties of the fetched object.
     * Since I selected the "id", the row has a property called "id".
     */
    $id = $row->id;
    $result->close();
  }
  
  /* If nothing in $id */
  if (!$id) {
    $sql = "CREATE TABLE $usertable (";
    $sql .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
    $sql .= "name VARCHAR(20), ";
    $sql .= "descrip VARCHAR(30), ";
    $sql .= "make VARCHAR(20), ";
    $sql .= "model VARCHAR(30), ";
    $sql .= "price VARCHAR(20), ";
    $sql .= "cond VARCHAR(20), ";
    $sql .= "title VARCHAR(30), ";
    $sql .= "user_id VARCHAR(25), ";
	$sql .= "zipcode VARCHAR(5)";
    $sql .= ");";
    
    if ($stmt = $mysqli->prepare($sql)) {
      /* Execute prepared statement */
      $stmt->execute();
      echo "Created table.<br>";
    }
  }
  else {
    echo "Table already exists.<br>";
  }
  
}
?>
