<?php

// ----- Set connection varaibles
$hostname= "localhost";
$username= "student";
$password= "learn";
$dbname=   "lesson01";
$usertable="table01a";

// ----- Connect
$con=mysqli_connect($hostname,$username,$password,$dbname);

// ----- Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// ----- Prepare SQL
$sql = "CREATE TABLE $usertable (";
$sql .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
$sql .= "user_id INT, ";
$sql .= "location_id INT, ";
$sql .= "ins_co VARCHAR(60), ";
$sql .= "ins_agency VARCHAR(60), ";
$sql .= "ins_agency_phone VARCHAR(10), ";
$sql .= "ins_agency_www VARCHAR(60), ";
$sql .= "amount_paid INT, ";
$sql .= "amount_per VARCHAR(10), ";
$sql .= "address VARCHAR(60), ";
$sql .= "city VARCHAR(60), ";
$sql .= "state VARCHAR(2), ";
$sql .= "zip_code VARCHAR(10), ";
$sql .= "year_built INT, ";
$sql .= "exterior VARCHAR(10), ";
$sql .= "interior VARCHAR(10), ";
$sql .= "structure_type VARCHAR(10), ";
$sql .= "weather_risk VARCHAR(10), ";
$sql .= "fire_risk VARCHAR(10), ";
$sql .= "owner_smokes INT, ";
$sql .= "owner_credit INT, ";
$sql .= "owner_claims INT";
$sql .= ");";

// Execute query
if (mysqli_query($con,$sql)) {
  echo "Table persons created successfully";
} else {
  echo "Error creating table: <br>" . mysqli_error($con)."<br>";
  echo "SQL was...<br>".$sql."<br>";
}

echo "<p><a href='table01a_insert.html'>Go to INSERT</a></p>";

?>