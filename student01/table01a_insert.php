<?php

// ----- Set connection variables
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

// ----- Create variable for each $_POST key
foreach($_POST as $key=>$value)
{
    $$key=$value;
}

// ----- Prepare SQL
//$sql = "CREATE TABLE $usertable (";
$sql = "INSERT INTO $usertable (";
//$sql .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
$sql .= "user_id, ";
$sql .= "location_id, ";
$sql .= "ins_co, ";
$sql .= "ins_agency, ";
$sql .= "ins_agency_phone, ";
$sql .= "ins_agency_www, ";
$sql .= "amount_paid, ";
$sql .= "amount_per, ";
$sql .= "address, ";
$sql .= "city, ";
$sql .= "state, ";
$sql .= "zip_code, ";
$sql .= "year_built, ";
$sql .= "exterior, ";
$sql .= "interior, ";
$sql .= "structure_type, ";
$sql .= "weather_risk, ";
$sql .= "fire_risk, ";
$sql .= "owner_smokes, ";
$sql .= "owner_credit, ";
$sql .= "owner_claims";
$sql .= ") VALUES (";
$sql .= "'$user_id', ";
$sql .= "'$location_id', ";
$sql .= "'$ins_co', ";
$sql .= "'$ins_agency', ";
$sql .= "'$ins_agency_phone', ";
$sql .= "'$ins_agency_www', ";
$sql .= "'$amount_paid', ";
$sql .= "'$amount_per', ";
$sql .= "'$address', ";
$sql .= "'$city', ";
$sql .= "'$state', ";
$sql .= "'$zip_code', ";
$sql .= "'$year_built', ";
$sql .= "'$exterior', ";
$sql .= "'$interior', ";
$sql .= "'$structure_type', ";
$sql .= "'$weather_risk', ";
$sql .= "'$fire_risk', ";
$sql .= "'$owner_smokes', ";
$sql .= "'$owner_credit', ";
$sql .= "'$owner_claims'";
$sql .= ");";

// Execute query
if (mysqli_query($con,$sql)) {
  echo "Table inserted successfully";
} else {
  echo "Error inserting table: <br>" . mysqli_error($con)."<br>";
  echo "SQL was...<br>".$sql."<br>";
}

echo "<p><a href='table01a_list.php'>Go to LIST</a></p>";

?>