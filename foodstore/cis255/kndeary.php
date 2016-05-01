<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');

		if (in_array($food,$foodArray)) {
			echo "We do have ".$food;
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo	"Sorry, we do not have ".$food;
		}

	echo '</response>';
?>