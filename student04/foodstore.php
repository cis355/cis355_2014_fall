<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');

		extract($foodArray);	//does the same as below
		
		//foreach($foodArray as $key=>$value)
		//{
		//	$$key=$value;
		//}
		
		$tuna = array('tuna casserole','tuna sandwich');
		$chicken = array('chicken chow mein', 'chicken nugz');
		$bacon = array('BLT sandwich','bacon-wrapped shrimp');
		
		if (in_array($food,$foodArray)) {
			echo "We do have ".$food;
			if($$food){
				echo '<item>'.$$food.'</item>';
			}
			
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo	"Sorry, we do not have ".$food;
		}

	echo '</response>';
?>