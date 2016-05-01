<?php
session_start();
unset($_SESSION["user"]); 
header("Location: ../student02/GarageSale.php");
?>