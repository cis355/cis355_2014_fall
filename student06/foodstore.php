<?php

function has_item( $food, $menu ) {
	if( is_array( $menu[$food] ) )
		return true;
	else
		return false;
}

	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food=$_GET['food'];
		$foodArray = array('tuna' => array(
			'tuna Casserole',
			'tuna Sandwich'
		)
		,'bacon' => array(
			'BTL Sandwich',
			
		)
		,'beef'=>array(
			'Philly Cheese Steak',
			'Beef Sandwich'
		)
		,'chicken','ham');

		if( has_item( $food, $foodArray ) ) {
			echo "We do have ".$food;
			
			foreach( $foodArray[$food] as $menuitem )
				echo '<item>'.$menuitem.'</item>';
		
		} elseif ($food==''){
			echo "Enter food";
		} else {
			echo	"Sorry, we do not have ".$food;
		}

	echo '</response>';
?>