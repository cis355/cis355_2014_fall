<?php
/**
 * Purpose: This file will;
 *          show examples of how to work with session variables
 *
 * Author:  Kevin Stachowski
 * Date:    10/11/2014
 * Update:  10/11/2014
 *
 * Notes:   A session variable collection is like $_GET or $_POST
 *          but it will persistent across page navigation
 *          In this file the session is started
 *          Once started we can assing a new index to the collection and give that index a value.
 *          By default the session will persist until the browser is closed.
 * 
 *          Why do I care?
 *          Sessions are typically used for information that is needed on most pages.
 *          You could pass items via hidden input tags, or url GET parameters,
 *          but then you must push all the data on every page they navigate to.
 *          Think info about what user is logged in, or a shopping cart.
 *  */

// start the session, this must happen !!!!!! before anything has been output!!!!!!
// this will allow us to use the session collection
session_start();


//display all vars
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

// you can also access the value via a named index, like $_POST
//echo out the var from the first test page.
echo "Session userID from page 1: ".$_SESSION["userID"];

// this will clear out all indexes / values
// on refresh the var will not exist.
session_unset(); 

echo "<br>sessions unset...<br>";

// display whats in session to verify there is nothing there
// print_r will itterate over the collection and display the index and value
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

//this will set a new index and value
$_SESSION["MyIndex"] = "MyValue";
$_SESSION["MyIndex2"] = "MyValue2";

echo "<br>2 new index added...<br>";

//this will print out the edited session collection
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';


// this will unset a specific index.
unset($_SESSION["MyIndex"]);
echo "<br>unset just MyIndex<br>";

//this will print out the edited session collection
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

// this will destory the session
session_destroy();

echo "<br>sessions destoyed...  "
. "<br>notice the collection still exists untill a refresh<br>";

//this will print out the edited session collection
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

echo"<br>";

