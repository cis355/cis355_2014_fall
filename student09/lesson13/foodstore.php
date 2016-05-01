<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');
		
		$tuna = "Tuna Fish Sandwich, Tuna Casserole, and Tuna Yumyum.";
		$bacon = "... who cares.  It's bacon.  Get this.";
		$beef = "Burger and Fries, Steak, and Philly Cheesesteak.";
		$chicken = "Chicken Parm and Roasted Chicken.";
		$ham = "Ham Sandwich.";

		if (in_array($food,$foodArray)) {
			echo "We do have ".$food."!  ";
			switch ($food){
				case 'tuna':
					echo "Menu items include ".$tuna;
					break;
				case 'bacon':
					echo "Menu items include ".$bacon;
					break;
				case 'beef':
					echo "Menu items include ".$beef;
					break;
				case 'chicken':
					echo "Menu items include ".$chicken;
					break;
				case 'ham':
					echo "Menu items include ".$ham;
					break;					
			}
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo "Sorry, we do not have ".$food;
		}


	echo '</response>';
?>