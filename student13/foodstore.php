<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodKey = array('tuna', 'bacon', 'beef', 'chicken', 'ham');
		$foodArray = array(array('tuna', 'tuna sandwich', 'tuna casserole'),
						   array('bacon', 'bacon-lettuce-tomato', 'bacon roll'),
						   array('beef', 'roast beef', 'beef wellington'),
						   array('chicken', 'fried chicken', 'grilled chicken'),
						   array('ham', 'ham sandwich', 'baked ham'));
		
		if ($food == $foodKey[0]) 
		{
			//echo "We do have ".$food;
			printFoods(0);
		} 
		elseif($food == $foodKey[1])
		{
			printFoods(1);
		}
		elseif($food == $foodKey[2])
		{
			printFoods(2);
		}
		elseif($food == $foodKey[3])
		{
			printFoods(3);
		}
		elseif($food == $foodKey[4])
		{
			printFoods(4);
		}
		elseif ($food=='')
		{
			echo "Enter food";
		} 
		else 
		{
			echo	"Sorry, we do not have ".$food;
		}

	echo '</response>';
	
	function printFoods($id)
	{
		global $foodArray;
		
		for ($i = 0; $i < 3; $i++)
		{
			echo "We do have ".$foodArray[$id][$i]."\n";
		}
	}
?>