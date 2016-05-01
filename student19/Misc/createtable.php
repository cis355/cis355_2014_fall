<?php


$hostname="localhost";
$username="user01";
$password="cis355lwip";
$dbname="lwip";
$usertable="table19";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli); // program dies if no connection


echo ("CREATE TABLE user19_table (user_id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY ( user_id )";

?>