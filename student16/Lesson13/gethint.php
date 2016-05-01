<?php
/**
 * Purpose: This file will;
 *          process an Ajax request.
 *
 * Author:  Kevin Stachowski
 * Date:    10/09/2014
 * Update:  10/09/2014
 * Notes:   Lesson13 test file
 * 
 */
 
 
// Fill up array with foods
$foods[]="Breads";
$foods[]="Dairy products";
$foods[]="Eggs";
$foods[]="Legumes";
$foods[]="Edible plants";
$foods[]="Edible fungi";
$foods[]="Meat";
$foods[]="Edible nuts and seeds";
$foods[]="Cereals";
$foods[]="Seafood";
$foods[]="Staple foods";


// get the food parameter from URL
$q=$_REQUEST["food"]; 
// reset hint
$hint="";

// lookup all hints from array if $q is different from ""
if ($q !== "") {
  // make sure the item is in the right case
  $q=strtolower($q);
  
  // get the length of the food request var
  $len=strlen($q);
  
  // append the main xml tag
  $hint = "<pre><Parent>";
  
  //itterate through each item in the a array
  foreach($foods as $food) 
  {
	//check if the q matches the food
    if (stristr($q, substr($food,0,$len))) 
	{
		//add the food to the xml
        $hint .= "<Child>$food</Child>";
	}
  }
  $hint .= "</Parent></pre>";
}

// Output "no suggestion" if no hint was found
// or output the correct values
echo $hint==="" ? "no suggestion" : $hint;
?> 