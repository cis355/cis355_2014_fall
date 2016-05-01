<?php
session_start();
?>
<html>
<body>

<?php
// Echo session variables that were set on previous page
echo "Favorite color is " . $_SESSION["favcolor"] . ".<br>";
echo "Favorite animal is " . $_SESSION["favanimal"] . ".<br><br>";
?>

<?php
echo "all session variables for session: <br>";
print_r($_SESSION);
?>



</body>
</html>
