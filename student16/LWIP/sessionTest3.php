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

session_start();

//display all vars
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

//display the current time
echo "current time: ".time()."<br><br>";

// check to see if the session should expire.
// boy if this was a webservice it would be a good use case for ajax!
if($_SESSION["expire"] < time())
    session_unset();

// link to page 2...
echo "<a href=sessionTest2.php>to page 2</a>";
