<!--
This page is used to test server side functionality using PHP

Author: Kevin Stachowski
Date: 08/28/2014
-->

<?php
/**
* This class is used to test functions from an external class and file.
*
* 08/28/2014
*/
class CIS355
{
    public function sayHello() {
        return "Hello!";
    }
}

/**
* This class is used hold data about the inventory.
*
* 08/28/2014 
*/
class  Inventory
{
	public static $A;
	public static $B;
}
?>