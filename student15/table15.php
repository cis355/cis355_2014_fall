<?php
// Working Database Table in LWIP
// Name: Caleb Miller
// Course, semester: CIS 355, Fall 2014
// Instructor: George Corser
// Date finished: 09/22/2014
// Program description: This program allows a user to insert, update, and
//                      delete records from table15 in the LWIP database using
//                      a form. The user can also drop, create, and list the
//                      table.

require 'camera.php';
require 'location.php';

// Database information
$hostname  = "localhost";
$username  = "user01";
$password  = "cis355lwip";
$dbname    = "lwip";
$usertable = "table15";

$cameras;	   // Points to list of Camera objects
$message;	   // Contains error or success message

session_start();  // Must be called before data is sent

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

/** MAIN LOGIC **/

// Connect to the database, initialize PHP Data Object (PDO)
try {
  $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
  $message = <<< END
  <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
END;
  file_put_contents('PDOTable15Errors.txt', $e->getMessage(), FILE_APPEND);
  exit();
}

// Get all locations from location table, save in SESSION variable
$_SESSION['locations'] = selectLocations($dbh);

if ($operation == "insert") {
  insertRecord($dbh);
}
elseif ($operation == "update") {
  updateRecord($dbh);
}
elseif ($operation == "delete") {
  deleteRecord($dbh);
}
elseif ($operation == "list") {
  $cameras = selectTable($dbh);
}
elseif ($operation == "create") {
  createTable($dbh);
}
elseif ($operation == "drop") {
  dropTable($dbh);
}
elseif ($viewFlag > 0) {
  // Show item view
  showItemView($dbh, $viewFlag);
}
elseif ($updateFlag > 0) {
  // Show update form
  showUpdateForm($dbh, $updateFlag);
}
elseif ($deleteFlag > 0) {
  deleteRecord($dbh, $deleteFlag);
}
else {
  $operation = "none";
}

// Get table from database if not done already
if (operation <> null && operation <> "list") {
  $cameras = selectTable($dbh);
}

// Save table and message information in session variable
$_SESSION['cameras'] = $cameras;
$_SESSION['message'] = $message;

// Close database connection
$dbh = null;

// Redirect user to confirmation page
header("Location: table15_confirmation.php");
exit();

/**
 * PURPOSE: Displays the update form HTML with pre-populated information from a
 *          selected record.
 * PRE:     The PDO object must be initialized.
 * POST:    The HTML update form is written to the page.
 *
 * @param var $dbh contains an initialized PDO object
 * @param var $camera_id specifies the row to select
 */
function showUpdateForm($dbh, $camera_id = 0) {
  // Get record to update from database
  $camera = selectRecord($dbh, $camera_id);
  
  // Save record information in session variable
  $_SESSION['camera'] = $camera;
  $_SESSION['message'] = $message;
  
  // Redirect to update form
  header("Location: table15_update_form.php");
  exit();
}

/**
 * PURPOSE: Displays the update form HTML with pre-populated information from a
 *          selected record.
 * PRE:     The PDO object must be initialized.
 * POST:    The HTML update form is written to the page.
 *
 * @param var $dbh contains an initialized PDO object
 * @param var $camera_id specifies the row to select
 */
function showItemView($dbh, $camera_id = 0) {
  // Get record to update from database
  $camera = selectRecord($dbh, $camera_id);
  
  // Save record information in session variable
  $_SESSION['camera'] = $camera;
  $_SESSION['message'] = $message;
  
  // Redirect to update form
  header("Location: table15_view_form.php");
  exit();
}

/**
 * PURPOSE: Updates a record in the table with the data entered from the form.
 * PRE:     The PDO object must be initialized.
 * POST:    The row has been updated.
 *
 * @param var $dbh contains an initialized PDO object
 */
function updateRecord($dbh) {
  global $usertable, $message, $camera_id, $user_id, $location_id, $name,
         $description, $make, $model, $camera_condition,
         $camera_type, $price;

  $sql = <<< END
  UPDATE $usertable SET name = ?,
                        description = ?,
                        make = ?,
                        model = ?,
                        camera_condition = ?,
                        camera_type = ?,
                        price = ?,
                        user_id = ?,
                        location_id = ?
                    WHERE camera_id = ?
END;

  $query = $dbh->prepare($sql);
  $query->bindParam(1, $name, PDO::PARAM_STR);
  $query->bindParam(2, $description, PDO::PARAM_STR);
  $query->bindParam(3, $make, PDO::PARAM_STR);
  $query->bindParam(4, $model, PDO::PARAM_STR);
  $query->bindParam(5, $camera_condition, PDO::PARAM_STR);
  $query->bindParam(6, $camera_type, PDO::PARAM_STR);
  $query->bindParam(7, strval($price), PDO::PARAM_STR);
  $query->bindParam(8, $user_id, PDO::PARAM_INT);
  $query->bindParam(9, $location_id, PDO::PARAM_INT);
  $query->bindParam(10, $camera_id, PDO::PARAM_INT);

  try {
    $query->execute();
  }
  catch(PDOException $e) {
    $message = <<< END
    <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
    <p>UPDATE failed, table is empty or does not exist!</p>
END;
    return false;
  }

  // Statement has successfully executed
  return true;
}

/**
 * PURPOSE: Inserts a record into the table.
 * PRE:     The PDO object must be initialized.
 * POST:    A new record has been inserted.
 *
 * @param var $dbh contains an initialized PDO object
 */
function insertRecord($dbh) {
  global $usertable, $message, $user_id, $location_id, $name,
         $description, $make, $model, $camera_condition,
         $camera_type, $price;

  $sql = <<< END
  INSERT INTO $usertable (name, description, make, model, camera_condition,
                          camera_type, price, user_id, location_id)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
END;

  $query = $dbh->prepare($sql);
  $query->bindParam(1, $name, PDO::PARAM_STR);
  $query->bindParam(2, $description, PDO::PARAM_STR);
  $query->bindParam(3, $make, PDO::PARAM_STR);
  $query->bindParam(4, $model, PDO::PARAM_STR);
  $query->bindParam(5, $camera_condition, PDO::PARAM_STR);
  $query->bindParam(6, $camera_type, PDO::PARAM_STR);
  $query->bindParam(7, strval($price), PDO::PARAM_STR);
  $query->bindParam(8, $user_id, PDO::PARAM_INT);
  $query->bindParam(9, $location_id, PDO::PARAM_INT);

  try {
    $query->execute();
  }
  catch(PDOException $e) {
    $message = <<< END
    <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
    <p>INSERT failed, table is empty or does not exist!</p>
END;
    return false;
  }

  // Statement has successfully executed
  return true;
}

/**
 * PURPOSE: Selects a record using the row ID passed in the parameter.
 * PRE:     The PDO object must be initialized.
 * POST:    The record has been selected and returned to the caller.
 *
 * @param var $dbh contains an initialized PDO object
 * @param var $camera_id specifies the row to select
 */
function selectRecord($dbh, $camera_id = 0) {
  global $usertable, $message;

  $sql = "SELECT * FROM $usertable WHERE camera_id = ?";

  $query = $dbh->prepare($sql);
  $query->bindParam(1, $camera_id, PDO::PARAM_INT);
  $query->setFetchMode(PDO::FETCH_CLASS, 'Camera');

  try {
    $query->execute();
  }
  catch(PDOException $e) {
    $message = <<< END
    <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
    <p>SELECT failed, table is empty or does not exist!</p>
END;
    return false;
  }

  // Statement has successfully executed
  return $query->fetch();
}

/**
 * PURPOSE: Deletes a record in the table with the data entered from the form.
 * PRE:     The PDO object must be initialized.
 * POST:    The row has been deleted.
 *
 * @param var $dbh contains an initialized PDO object
 */
function deleteRecord($dbh, $camera_id = 0) {
  global $usertable, $message;

  $sql = <<< END
  DELETE FROM $usertable WHERE camera_id = ?
END;

  $query = $dbh->prepare($sql);
  $query->bindParam(1, $camera_id, PDO::PARAM_INT);

  try {
    $query->execute();
  }
  catch(PDOException $e) {
    $message = <<< END
    <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
    <p>DELETE failed, table is empty or does not exist!</p>
END;
    return false;
  }

  // Statement has successfully executed
  return true;
}

/**
 * PURPOSE: Selects the entire table if it exists.
 * PRE:     The PDO object must be initialized.
 * POST:    The table has been created.
 *
 * @param var $dbh contains an initialized PDO object
 */
function selectTable($dbh) {
  global $usertable, $message;
  $sql = "SELECT * FROM $usertable";

  $query = $dbh->prepare($sql);

  // Connect to the database, initialize PHP Data Object (PDO)
  try {
    $query->execute();
  }
  catch(PDOException $e) {
    $message = <<< END
    <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
    <p>SELECT failed, table is empty or does not exist!</p>
END;
    return false;
  }

  // fetchAll() is the PDO method that gets all results rows
  return $query->fetchAll(PDO::FETCH_CLASS, "Camera");
}

/**
 * PURPOSE: Creates the table if it doesn't already exist.
 * PRE:     The PDO object must be initialized.
 * POST:    The table has, or has not, been created.
 *
 * @param var $dbh contains an initialized PDO object
 */
function createTable($dbh) {
  global $usertable;

  $sql = <<< END
    CREATE TABLE IF NOT EXISTS $usertable (camera_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                           name VARCHAR(20),
                                           description VARCHAR(200),
                                           make VARCHAR(20),
                                           model VARCHAR(20),
                                           camera_condition VARCHAR(20),
                                           camera_type VARCHAR(20),
                                           price DECIMAL(10,2),
                                           user_id INT,
                                           location_id INT,
                                           FOREIGN KEY (user_id)
                                             REFERENCES users (user_id)
                                             ON DELETE CASCADE
                                             ON UPDATE CASCADE,
                                           FOREIGN KEY (location_id)
                                             REFERENCES locations (location_id)
                                             ON DELETE CASCADE
                                             ON UPDATE CASCADE
                                          );
END;

  $query = $dbh->prepare($sql);

  // Execute statement and return success
  return $query->execute();
}

/**
 * PURPOSE: Drops the table if it exists.
 * PRE:     The PDO object must be initialized.
 * POST:    The table has been, or has not been, deleted.
 *
 * @param var $dbh contains an initialized PDO object
 */
function dropTable($dbh) {
  global $usertable;

  $sql = <<< END
  DROP TABLE IF EXISTS $usertable
END;

  $query = $dbh->prepare($sql);

  // Execute statement and return success
  return $query->execute();
}

function selectLocations($dbh) {
  $sql = <<< END
  SELECT * FROM locations
END;

  $query = $dbh->prepare($sql);

  // Connect to the database, initialize PHP Data Object (PDO)
  try {
    $query->execute();
  }
  catch(PDOException $e) {
    $message = <<< END
    <p>I'm sorry, Dave. I'm afraid I can't do that.</p>
    <p>SELECT failed, location table is empty or does not exist!</p>
END;
    return false;
  }

  // fetchAll() is the PDO method that gets all results rows
  return $query->fetchAll(PDO::FETCH_CLASS, "Location");
}
?>
