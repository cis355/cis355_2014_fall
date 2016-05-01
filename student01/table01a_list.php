<?php

// ----- Echo beginning html
echo "<!DOCTYPE html><html><head><title>table01a_list</title></head><body>";
echo "<h1>House Insurance</h1><h2>LIST</h2>";

// ----- Set connection variables
$hostname= "localhost";
$username= "student";
$password= "learn";
$dbname=   "lesson01";
$usertable="table01a";

// ----- Connect
$con=mysqli_connect($hostname,$username,$password,$dbname);

// ----- Check connection
if (mysqli_connect_errno()) 
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
{
    echo "connected<br>";
}

// ----- Prepare SQL
$sql = "SELECT * FROM $usertable";

// ----- Execute SQL
if ($result = mysqli_query($con,$sql)) 
{
	/* begin table of results */
	echo "<br> Data selected from the MySQL table.";
	echo "<table><tr><th>id</th><th>user_id</th><th>location_id</th><th>ins_co</th></tr>";

    /* fetch values and add table rows */
    while ($row = mysqli_fetch_row($result)) 
	{
        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
    }
	
	/* end table of results */
	echo "</table><br>";
}
else
{
    echo "error preparing table of results<br>";
}

echo "<p><a href='table01a_insert.html'>Go to INSERT</a></p>";

// ----- Echo ending html
echo "</body></html>";

?>