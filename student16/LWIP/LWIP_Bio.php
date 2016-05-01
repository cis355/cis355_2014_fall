<?php
/**
 * Purpose: This file will;
 *          Show the bio and notes page.
 *
 * Author:  Kevin Stachowski
 * Date:    11/11/2014
 * Update:  11/13/2014
 * Notes:   
 * 
 */
require_once("LWIP_function.php");

main();

 /**
 * Purpose: This is the driver function for the application.
 * Pre:     none
 * Post:    page has been processed.
 */
function main()
{
	/* add the headers to the top of the html page. */
    headers();
		
    /* Select and display bio info */
    showInfo();
    
	/* add the footers to the bottom of the html page. */
    footer();
}

/**
 * Purpose: This function will display the bio info to the user
 * Pre:     none
 * Post:    The data is displayed.
 * 
 */
function showInfo()
{
	echo "<hr><h2>Bio and Notes</h2><br>
	<table class='table table-bordered'>
	<tr><th>Bio</th><th>Notes (This Project)</th></tr>
	<tr><td>
	<img class='title-logo img-circle me' src='me.png' alt='me'><b>Author:</b> Kevin Stachowski<br>
	<b>Date:</b> 11/11/2014<br>
	<b>Personal web site:</b> <a href='http://kstachowski.com'>kstachowski.com</a></td>
	<td>LWIP (Look What I Paid) - Game Consoles<br>
		<a href='http://cis355.com/student16/LWIP/LWIP_ShowTables.php'>http:/cis355.com/student16/LWIP/LWIP_ShowTables.php</a></td></tr>
	<tr><td>
	<b>Bio:</b> I'm a software developer with a wide variety of IT backgrounds including help desk,
	Database administration, Systems (disaster recovery and high availability), front and 
	back end web development and project management. My current work focus is on user automation of manual tasks dealing 
	with manipulation and aggregation of disparate systems, typically with C# .Net tools.
	</td><td>
	This website was made to show examples of server side web development. On this project a user 
	add items to a table and then add comments to that item. Once an item has been added the user can
	view any item, or update and delete items created under their user. The site uses 
	things like session variables and AJAX calls to php web services. Form data is dynamically 
	generated from a MySQL DB which consists of normalized relations with proper key mappings.
	Some of the other ideas displayed here are php OOP functions, scope, objects, encryption 
	and php doc style commenting.
	</td></tr></table>";
}


