<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');
		
		//extract($foodArray); //does the same as below
		
		/* foreach($foodArray as $key=>$value)
		{
			$$key = $value;
		}
 */
		$tuna =('tuna casserole','tuna sandwich','tuna salad', 'tuna melt');
		
		/* $tuna = array('tuna casserole','tuna sandwich','tuna salad', 'tuna melt');
		$bacon = array('bacon melt','BLT','bacon cheese burger','bacon salad');
		$beef = array('beef soup','stake','pot roast','angus burger','beef melt');
		$chicken = array('chicken salad','chicken tenders','chicken marsala','chicken breast');
		$ham = array('christmas ham','ham salad','ham sandwich','ham melt','ham and cheese'); */
		
		if (in_array($food,$foodArray)) {
			echo "We do have ".$food;
			if($$food)
			{
				echo '<item>'.$$food.'</item>';
			}
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo	"Sorry, we do not have ".$food;
		}

	echo '</response>';
?>