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
 */

//start the session, this must happen !!!!!! before anything has been output!!!!!!
session_start();

//create a session var and assign that index a value.
$_SESSION["userID"] = "Kevin";

// set a time you would like the session to expire.
$_SESSION["expire"] = time() + 10;

//display all vars
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

// link to test page 2
// this link goes to a page that will display the vaule we assigned above
echo "<a href=sessionTest2.php>test session directly to page 2</a>";

// link to test page 3
// this page is just an intermediary to show that we can navigate
// to multiple pages and the session will still persist
echo "<br><a href=sessionTest3.php>test session through page 3</a>";

