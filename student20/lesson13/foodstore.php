<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');

		extract($foodArray);
		foreach($foodArray as $key=>$value)
		{
			$$key = $value;
		}
		$tuna = array('tuna casserole', 'tuna sandwich', 'tuna salad');
		$beef = array('steak dinner','beef brisket','hamburger');
		$chicken = array('chicken salad','chicken tenders','grilled chicken','chicken marsala','barbecue chicken');
		$ham = array('ham sandwich','pulled pork','pork chops');
		
		if (in_array($food,$foodArray)) {
			echo "We do have ".$food;
			
			
			
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo	"Sorry, we do not have ".$food;
		}

	echo '</response>';
?>