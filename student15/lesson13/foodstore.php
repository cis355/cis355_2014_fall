<?php
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
		$food = $_GET['food'];
		$foodArray = array('tuna','bacon','beef','chicken','ham');
    $entreeArray = array('chicken marsala');
    $appetizerArray = array('chicken tenders');
    
    //extract($foodArray);  // This is reflection?
    
    if (in_array($food, $foodArray)) {
       // Loop through food array
      foreach ($foodArray as $item){
        if ($food == $item) {
          echo "<message>We do have $item</message>";
        }
      }
    
      // Loop through entrée array
      foreach ($entreeArray as $entree){
        if (strpos($entree, $food) !== false) {
          echo "<entree>$entree</entree>";
        }
      }
      
      // Loop through appetizer array
      foreach ($appetizerArray as $appetizer){
        if (strpos($appetizer, $food) !== false) {
          echo "<appetizer>$appetizer</appetizer>";
        }
      }
    } elseif ($food == '') {
			echo "<message>Enter food</message>";
		} else {
			echo	"<message>Sorry, we do not have $food</message>";
		}

	echo '</response>';
?>