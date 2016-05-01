<?php
require 'camera.php';

session_start();  // Must be called before data is retrieved

$cameras = $_SESSION['cameras'];  // Get session variables
$message = $_SESSION['message'];
$user = $_SESSION['user'];
$location = $_SESSION['location'];
$userid = $_SESSION['id'];

$userString = <<< END
	<p>
	  You are logged in as user: $user
    Your location is: $location
	  Your user ID is: $userid
  </p>
END;

/**
 * PURPOSE: Creates a string of HTML code that creates a table, and returns HTML
 *          to caller.
 * PRE:     $cameras variable is pointing to a collection of Camera objects.
 * POST:    A string of HTML table code is returned to the caller.
 *
 * @param  var $cameras a collection of Camera objects
 * @return var $html the HTML code that lists the camera object properties in a
 *                   tabular format
 */
function createTableHTML($cameras) {
  // Start HTML code with heredoc
  $html = <<< END
  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
	<h2>LWIP Camera Entries</h2>
  </div>
  <table class="table">
    <tr>
      <th>Name</th>
      <th>Description</th>
      <th>Model</th>
      <th>Condition</th>
      <th>Type</th>
      <th>Price</th>
      <th>Operations</th>
    </tr>
END;

  foreach ($cameras as $camera) {
    $html .= <<< END
    <tr>
      <td>$camera->name</td>
      <td>$camera->description</td>
      <td>$camera->model</td>
      <td>$camera->camera_condition</td>
      <td>$camera->camera_type</td>
      <td>$camera->price</td>
END;

    // Add view button for viewing full camera entry
    $html .= <<< END
      <td>
        <button type="submit" class="btn btn-primary" name="view" onclick="setView($camera->camera_id)">View</button>
END;

    // Add update and delete buttons if entry matches current user
    if ($camera->user_id == $_SESSION['id']) {
      $html .= <<< END
      <button type="submit" class="btn btn-success" name="update" onclick="setUpdate($camera->camera_id)">Update</button>
      <button type="submit" class="btn btn-danger" name="delete" onclick="setDelete($camera->camera_id)">Delete</button>
END;
	  }
	
    $html .= <<< END
      </td>
    </tr>
END;
  }

    $html .= <<< END
  </table>
END;

  return $html;
}
?>

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

<!-- JQuery Validation plug-in -->
<script src="plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>

<!-- JQuery to initialize JQuery Validation and listen for selection changes -->
<script src="js/table15.js" type="text/javascript"></script>

<script type="text/javascript">
  function validate() {
    var allok = true;
    document.basic.Submit.disabled="disabled";
    return true;
  } // end validate

  function setUpdate(index) {
    document.getElementById('updateFlag').value = index;
  }

  function setDelete(index) {
    document.getElementById('deleteFlag').value = index;
  }

  function setView(index) {
    document.getElementById('viewFlag').value = index;
  }
</script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container-fluid theme-showcase" role="main">
  <form action="table15.php" class="form-horizontal" method="post" role="form">
  <?php
	  if (isset($message)) {
	    echo $message;
	  }
	  echo createTableHTML($cameras);
	?>
    <div class="form-group">
      <?php
      if (isset($_SESSION['user'])) {
        echo <<< END
      <div class="col-sm-2">
        <a class="btn btn-default btn-block" href="table15_entry_form.php" role="button">To Entry Form</a>
      </div>
END;
      }
      ?>
      <div class="col-sm-2">
        <a class="btn btn-default btn-block" href="table15_author.php" role="button">To Author Bio</a>
      </div>
      <input type="hidden" id="updateFlag" name="updateFlag" value="">
      <input type="hidden" id="deleteFlag" name="deleteFlag" value="">
      <input type="hidden" id="viewFlag" name="viewFlag" value="">
    </div>
  </form>
</div>
</body>
</html>