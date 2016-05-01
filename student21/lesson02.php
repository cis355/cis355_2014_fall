<?php
# lesson02.php
/**Billy Wright - LookwhatIpaid Project**/

/**Present the information that was entered by the user**/
print "<p>The following information was submitted from the form:<p><table width=\"375\" border=\"0\"><tr
><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">Year</td><td>".$_REQUEST["Year"]."</td></tr>
<tr><td width=\"35%\">Make</td><td>".$_REQUEST["Make"]."</td></tr>
<tr><td width=\"35%\">Model</td><td>".$_REQUEST["Model"]."</td></tr>
<tr><td width=\"35%\">ListPrice</td><td>".$_REQUEST["ListPrice"]."</td></tr>
<tr><td width=\"35%\">DateListed</td><td>".$_REQUEST["DateListed"]."</td></tr>
<tr><td width=\"35%\">PriceSold</td><td>".$_REQUEST["PriceSold"]."</td></tr>
<tr><td width=\"35%\">DateSold</td><td>".$_REQUEST["DateSold"]."</td></tr>

</table></td></tr></table>
";

/**Login information for the "lesson1" database at cis355.com**/
$hostname="localhost";
$username="student";
$password="learn";

$dbname="lesson01";
$usertable="Junk";
$yourfield = "name";

/**Make connection to the database, or display an error "Cannot connect"**/
$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

/**If database table "$usertable" does not exist, create it.**/
$val = mysql_query('select 1 from $usertable');
if($val == FALSE){


$sql = "CREATE TABLE IF NOT EXISTS $usertable (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `Year` year(4) NOT NULL,
  `Make` varchar(20) NOT NULL,
  `Model` varchar(10) NOT NULL,
  `ListPrice` float NOT NULL,
  `DateListed` date NOT NULL,
  `PriceSold` float NOT NULL,
  `DateSold` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3" ;

/**Get the result of the query and put it into the "result" variable**/
/**If nothing in result, display error to the user**/
  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}

echo "Table created\n";

/**Variables to hold values for the table "Cycles"**/
$valYR = $_POST["Year"];
$valMK = $_POST["Make"];
$valMO = $_POST["Model"];
$valLP = $_POST["ListPrice"];
$valDL = $_POST["DateListed"];
$valPS = $_POST["PriceSold"];
$valDS = $_POST["DateSold"];


/**Put data into the "Cycles" table. (lesson1 database)**/
$stmt = $mysqli->stmt_init();


if($stmt = mysqli->prepare( "INSERT INTO $usertable ( `Year`, `Make`, 'Model', 'ListPrice', 'DateListed', 'PriceSold', 'DateSold') VALUES  (? , ?, ?, ?, ?, ?, ? )"){

$stmt->bind_param('sssfsfs', $valYR, $valMK, $valMO, $valLP, $valDL, $valPS, $valDS);
$stmt->execute();
$stmt->close();
}
echo "Inserted";


//VALUES ( $valYR, $valMK, $valMO, $valLP, $valDL, $valPS, $valDS )";

/**Check to see if the data was entered successfully (query)**/
/*$result = mysql_query ("$sql");
if(!($result)) {
   echo "<BR><font color=red>error: record not inserted</font>";
} else {
   echo "<br><font color=green>record successfully inserted</font>";
}*/

?>
<!-- lesson02.html -->
<!-- built using http://www.phpform.info -->

<!DOCTYPE html>

<html>


<head>
  <title>LookWhatIPaid: Cycles</title>
</head>

<body>

<style>
.robotext {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
.robolink:link {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
.robolink:hover {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: underline}
.robolink:visited {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
</style>

<script language="Javascript">
function validate(){
var allok = true;
document.basic.Submit.disabled="disabled";
return true;
}
</script>

<!--Display input boxes to get user input-->
<form name="basic" method="Post" action="lesson02.php" onSubmit="return validate();">
<table border="0" cellpadding="5" cellspacing="0">
<tr><td>Year</td><td><input type="text" name="Year" value="" size="20"></td></tr>
<tr><td>Make</td><td><input type="text" name="Make" value="" size="30"></td></tr>
<tr><td>Model</td><td><input type="text" name="Model" value="" size="30"></td></tr>
<tr><td>ListPrice</td><td><input type="text" name="ListPrice" value="" size="30"></td></tr>
<tr><td>DateListed</td><td><input type="text" name="DateListed" value="" size="30"></td></tr>
<tr><td>PriceSold</td><td><input type="text" name="PriceSold" value="" size="30"></td></tr>
<tr><td>DateSold</td><td><input type="text" name="DateSold" value="" size="30"></td></tr>

<!--Reset, and Submit buttons-->
<tr><td align="center"><input type="reset" name="Reset" value="Reset"></td><td align="center"><input type="submit" name="Submit" value="Submit"></td></tr>
<!--<tr><td colspan=2 class=robotext><a href="http://www.phpform.info" class="robolink">HTML/PHP Form Generator</a> from ROBO Design Solutions</td></tr>-->
</table>
</form>

</body>
</html>
