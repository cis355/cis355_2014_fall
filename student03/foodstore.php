<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');
		
		extract($foodArray);  // same as below
		
//		foreach ($foodArray as $key=>$value)
//		{
//			$$key = $value;
//		}

		$tuna = 'tuna casserole';

		if (in_array($food,$foodArray)) {
			echo "We do have ".$food;
			if($$food)	{
				echo '<item>'.$$food.'</item>';
			}
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo "Sorry, but what the hell is, ".$food, "?";
		}

	echo '</response>';
?>
