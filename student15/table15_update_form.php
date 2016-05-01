<?php
require 'camera.php';
require 'location.php';

session_start();  // Must be called before data is retrieved

$camera = $_SESSION['camera'];  // Get session variable
$locations = $_SESSION['locations'];

function createLocationOptions($locations) {
  $loc_options = "";

  foreach($locations as $location) {
    $loc_options .= <<< END
      <option value="$location->location_id">$location->name</option>
END;
  }

  return $loc_options;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>LWIP Update Form</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

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

  $( document ).ready(function() {
    $("#location_id").val(<?php echo $camera->location_id; ?>);
  });
</script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container-fluid">
  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
	<h2>LWIP Update Form</h2>
  </div>
  <form action="table15.php" class="form-horizontal" method="post" role="form">
	<div class="form-group" style="display: none">
        <input name="camera_id" type="text" class="form-control" id="camera_id" value="<?php echo $camera->camera_id; ?>">
    </div>
    <div class="form-group">
      <label for="name" class="col-sm-1 control-label">Name</label>
      <div class="col-sm-2">
        <input name="name" type="text" class="required form-control" id="name" value="<?php echo $camera->name; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="description" class="col-sm-1 control-label">Description</label>
      <div class="col-sm-5">
        <textarea name="description" class="form-control" cols="50" rows="4" id="description"><?php echo $camera->description; ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <label for="make" class="col-sm-1 control-label">Make</label>
      <div class="col-sm-2">
        <input name="make" type="text" class="required form-control" id="make" value="<?php echo $camera->make; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="model" class="col-sm-1 control-label">Model</label>
      <div class="col-sm-2">
        <input name="model" type="text" class="required form-control" id="model" value="<?php echo $camera->model; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="camera_condition" class="col-sm-1 control-label">Condition</label>
      <div class="col-sm-2">
        <input name="camera_condition" type="text" class="form-control" id="camera_condition" value="<?php echo $camera->camera_condition; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-1 control-label">Type</label>
      <div class="col-sm-2">
        <input name="camera_type" type="text" class="form-control input-medium" id="camera_type" value="<?php echo $camera->camera_type; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="price" class="col-sm-1 control-label">Price</label>
      <div class="col-sm-2">
        <input name="price" type="text" class="required form-control input-medium" id="price" value="<?php echo $camera->price; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="user_id" class="col-sm-1 control-label">User ID</label>
      <div class="col-sm-2">
        <input name="user_id" type="text" class="required form-control" id="user_id" value="<?php echo $camera->user_id; ?>" readonly>
      </div>
    </div>
    <div class="form-group">
      <label for="location_id" class="col-sm-1 control-label">Location</label>
      <div class="col-sm-3">
        <select name="location_id" class="required form-control" id="location_id">
          <?php echo createLocationOptions($locations); ?>
        </select>
      </div>
    </div>
    <div class="form-group" id="operation_area" style="display: none">
      <div class="col-sm-8">
        <label class="radio-inline"><input type="radio" name="operation" id="update" value="update" checked></label>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-1 col-sm-1">
        <button type="submit" class="btn btn-success" id="submit" name="submit">Update</button>
      </div>
      <div class="col-sm-1">
        <button type="reset" class="btn btn-danger" id="reset" name="reset">Reset</button>
      </div>
    </div>
    <div class="form-group">
      <?php
      if (isset($_SESSION['user'])) {
        echo <<< END
      <div class="col-sm-offset-1 col-sm-2">
        <a class="btn btn-default btn-block" href="table15_entry_form.php" role="button">To Entry Form</a>
      </div>
END;
      }
      ?>
      <div class="col-sm-2">
        <a class="btn btn-default btn-block" href="table15.php" role="button">Back To List</a>
      </div>
      <input type="hidden" id="updateFlag" name="updateFlag" value="">
      <input type="hidden" id="deleteFlag" name="deleteFlag" value="">
    </div>
  </form>
</div>
</body>
</html>