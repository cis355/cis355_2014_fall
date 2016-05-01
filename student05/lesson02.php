<?php
# lesson02.php
# ----- built using http://www.phpform.info -----

print "<p>The following information was submitted from the form:<p><table width=\"450\" border=\"0\"><tr><td style=\"BORDER: #C3E9C1 3px solid;\"><table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">
<tr><td width=\"35%\">name</td><td>".$_REQUEST["name"]."</td></tr>
<tr><td width=\"35%\">desc1</td><td>".$_REQUEST["desc1"]."</td></tr>
<tr><td width=\"35%\">isbn10</td><td>".$_REQUEST["isbn10"]."</td></tr>
<tr><td width=\"35%\">isbn13</td><td>".$_REQUEST["isbn13"]."</td></tr>
<tr><td width=\"35%\">author</td><td>".$_REQUEST["author"]."</td></tr>
<tr><td width=\"35%\">publisher</td><td>".$_REQUEST["publisher"]."</td></tr>
<tr><td width=\"35%\">year</td><td>".$_REQUEST["year"]."</td></tr>
<tr><td width=\"35%\">pages</td><td>".$_REQUEST["pages"]."</td></tr>
<tr><td width=\"35%\">booktype</td><td>".$_REQUEST["booktype"]."</td></tr>
<tr><td width=\"35%\">covertype</td><td>".$_REQUEST["covertype"]."</td></tr>
<tr><td width=\"35%\">price</td><td>".$_REQUEST["price"]."</td></tr>
<tr><td width=\"35%\">city</td><td>".$_REQUEST["city"]."</td></tr>
</table></td></tr></table>
";

$hostname="localhost";
$username="student";
$password="learn";
$dbname="lesson01";
$usertable="table05cb";
$yourfield = "name";

$con = mysql_connect($hostname,$username, $password) 
  or die ("<html><script language='JavaScript'>alert('Cannot connect.'),history.go(-1)</script></html>");
mysql_select_db($dbname);
echo "successful connection.";

$val = mysql_query('select 1 from `table05cb`');
if($val == FALSE)
{
  $sql = "CREATE TABLE table05cb (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY( id ),";
  $sql .= "name VARCHAR(20),";
  $sql .= "desc1 VARCHAR(30),";
  $sql .= "isbn10 VARCHAR(30),";
  $sql .= "isbn13 VARCHAR(30),";
  $sql .= "author VARCHAR(30),";
  $sql .= "publisher VARCHAR(30),";
  $sql .= "year VARCHAR(30),";
  $sql .= "pages VARCHAR(30),";
  $sql .= "booktype VARCHAR(30),";
  $sql .= "covertype VARCHAR(30),";
  $sql .= "price VARCHAR(30),";
  $sql .= "city VARCHAR(30)";
  $sql .= ")";

  $result = mysql_query ("$sql");
  if(!($result))
     echo "<BR><font color=red>Error; ".mysql_errno()."; error description: </font>".mysql_error();
}
$val1 = $_POST["name"];
$val2 = $_POST["desc1"];
$val3 = $_POST["isbn10"];
$val4 = $_POST["isbn13"];
$val5 = $_POST["author"];
$val6 = $_POST["publisher"];
$val7 = $_POST["year"];
$val8 = $_POST["pages"];
$val9 = $_POST["booktype"];
$val10 = $_POST["covertype"];
$val11 = $_POST["price"];
$val12 = $_POST["city"];
$sql = "INSERT INTO table05cb (`id`, `name`, `desc1`, `isbn10`, `isbn13`, `author`, `publisher`, `year`, `pages`, `booktype`, `covertype`, `price`, `city`) 
       VALUES (NULL, '$val1', '$val2', '$val3', '$val4', '$val5', '$val6', '$val7', '$val8', '$val9', '$val10', '$val11', '$val12')";

$result = mysql_query ("$sql");
if(!($result)) 
{
   echo "<BR><font color=red>error: record not inserted</font>";
} 
else 
{
   echo "<br><font color=green>record successfully inserted</font>";
}
?>