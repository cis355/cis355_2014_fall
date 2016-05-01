<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');
		
		//extract($foodArray); same as foreach
		foreach($foodArray as $key=>$value)
		{
		  $$key = $value;
		}
		
		$tuna = array('tuna casserole', 'tuna sandwich');

		if (in_array($food,$foodArray)) {
			echo "We do have ".$food;

			tunaType($food);
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo "Sorry, we do not have ".$food;
		}

	echo '</response>';
	
	function tunaType($food)
	{
	  echo '<item></item>';
	}
?>