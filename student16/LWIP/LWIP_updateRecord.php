<?php
/**
 * Purpose: This file will;
 *          Show the data in the tables used in the LWIP project
 *
 * Author:  Kevin Stachowski
 * Date:    09/21/2014
 * Update:  10/11/2014
 * Notes:   These tables will be used by the LWIP project
 * 
 * External resources: This application depends on access to a mysql DB.
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
	/* bring vars from $[_GET] into local scope */
    global $table, $id, $update;
    
	/* add the headers to the top of the html page. */
    headers();
    
	/* Create a new mysqli object, this opens the connection. */
    $mysqli = getMySQLi();
    
    if($update != "true")
    {
        /* Show the form populated with data from the record*/
        showForm($mysqli);
    }
    elseif($table && $id)
    {
        /* update the record with the form data */
        updateData($mysqli);
    }
    else
    {
        echo "<br>There was no Table, ID or form data sent in.<br>";
    }
    
    /* Close connection, call close method of the mysqli object */
    $mysqli->close();
	
	/* add the footers to the bottom of the html page. */
    footer();
}

/**
 * Purpose: This function will update data from the table
 * Pre:     The MySQLi object must be connected.
 * Post:    The data is updated or feedback is given on why it didn't.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function updateData($mysqli)
{
	/* bring vars into local scope */
    global $table, $id, $title, $user, $description, $comment, $price, $kjstacho_LWIP_Items_id, $kjstacho_LWIP_Locations_id, $zip, $zipDescription, $email, $password;
    
    if(isset($password))
        $password = encrypt($password);
	
	/* set update sql for matching table definition */
    if($table == itemsTable)
    {
        $sql = "update $table set kjstacho_LWIP_Locations_id=?, kjstacho_LWIP_Users_id=?, title=?, description=?, price=? where $table"."_id=?";
    }
    elseif($table == commentsTable)
    {
        $sql = "update $table set kjstacho_LWIP_Items_id=?, kjstacho_LWIP_Users_id=?, comment=? where $table"."_id=?";
    }
    elseif($table == locationsTable)
    {
        $sql = "update $table set zip=?, zipDescription=? where $table"."_id=?";
    }
    elseif($table == usersTable)
    {
        $sql = "update $table set kjstacho_LWIP_Locations_id=?, email=?,password=? where $table"."_id=?";
    }
    
	/* Prepare the statement */
    if($stmt = $mysqli->prepare($sql))
    {
		/* bind params to matching table definition */
        if($table == itemsTable)
            $stmt->bind_param('iissdi', $kjstacho_LWIP_Locations_id, $_SESSION["userID"], $title, $description, $price, $id);
        elseif($table == commentsTable)
            $stmt->bind_param('iisi', $kjstacho_LWIP_Items_id, $_SESSION["userID"], $comment, $id);
        elseif($table == locationsTable)
            $stmt->bind_param('ssi', $zip, $zipDescription, $id);
        elseif($table == usersTable)
            $stmt->bind_param('issi', $kjstacho_LWIP_Locations_id, $email, $password, $id);
        
		/* execute the sql */
        if($stmt->execute())
        {
            echo "Data has been updated on $table.<br>";
            $stmt->close();
            //call java script redirect (from LWIP_Scripts.js)
            echo "<script>goToShowTables();</script>";
        }
        elseif(errorsOn)
            echo "Insert execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    elseif(errorsOn)
        echo "Insert Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/**
 * Purpose: This will generate the html form for the user. 
 * Pre:     It should be called when there is no form post data.
 * Post:    The form string needs to be displayed to the user.
 */
function showForm($mysqli)
{
	/* bring vars into local scope */
    global $table,$id,$enabled;
	
    $sql = "select * from $table where $table"."_id=$id";
    /* Prepare the statement */
    $stmt = executeSQL($mysqli,$sql);
    
    if($table == locationsTable)
    {
        $stmt->bind_result($id, $zip, $zipDescription, $insertDate);
        $stmt->fetch();

        echo "<form name='updateLocation' method='Post' action='LWIP_updateRecord.php' '>
            <table class='table table-bordered'>
            <tr><td>Zip</td><td><input type='edit' name='zip' value='$zip' size=20></td></tr>
            <tr><td>ZipDescription</td><td><input type='edit' name='zipDescription' value='$zipDescription' size=30></td></tr>";
    }
    if($table == usersTable)
    {
        $stmt->bind_result($id, $kjstacho_LWIP_Locations_id, $email, $password, $insertDate);
        $stmt->fetch();

        echo "<form name='updateUser' method='Post' action='LWIP_updateRecord.php' '>
            <table class='table table-bordered'>
            <tr><td>Email</td><td><input type='edit' name='email' value='$email' size=20></td></tr>
            <tr><td>Password</td><td><input type='edit' name='password' value='$password' size=30></td></tr>
            <tr><td>Default Location</td><td><select id='kjstacho_LWIP_Locations_id' name='kjstacho_LWIP_Locations_id'>";
        $mysqli2 = getMySQLi();
        if($result = $mysqli2->query("select * from locations"))
        {
            /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
            while($row = $result->fetch_object())
            {
                /* display option for the select */
				if($row->location_id == $kjstacho_LWIP_Locations_id)
				{
					echo "<option value=".$row->location_id." selected>".$row->name."</option>";
				}
				else
				{
					echo "<option value=".$row->location_id.">".$row->name."</option>";
				}
            }
            $result->close();
        }
        else
        {
            echo "Create Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            exit();
        }

        echo "</select></td></tr>";
    }
    elseif($table == itemsTable)
    {
        $stmt->bind_result($id, $kjstacho_LWIP_Users_id, $kjstacho_LWIP_Locations_id, $title, $description, $price, $insertDate);
        $stmt->fetch();
        $mysqli2 = getMySQLi();
        
        echo "<form name='updateEntry' method='Post' action='LWIP_updateRecord.php' '>
            <table class='table table-bordered'>
            <tr><td>Title</td><td><input name='title' value='$title' size=20></td></tr>
            <tr><td>Description</td><td><input name='description' value='$description' size=30></td></tr>
            <tr><td>Price</td><td><input name='price' value='$price' size=30></td></tr>
            <tr><td>Location</td><td><select name='".locationsTable."_id'>";
            /* fetch the data for the options */
            if($items = $mysqli2->query("select * from locations"))
            {
                /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
                while($row = $items->fetch_object())
                {
					/* display the options */
                    echo "<option value=".$row->location_id;
					
					/* select the option if it matches bound id */
                    if($row->location_id == $kjstacho_LWIP_Locations_id)
                    {
                        echo " selected";
                    }
                    echo " class='".$kjstacho_LWIP_Locations_id." ".$row->location_id."'>".$row->name."</option>";
                }
                $mysqli2->close();
            }
            else
            {
                echo "Create Prepare failed: (" . $mysqli2->errno . ") " . $mysqli2->error;
                exit();
            }
            echo "</select></td></tr>";
    }
    
    elseif($table == commentsTable)
    {
        $stmt->bind_result($id, $kjstacho_LWIP_Items_id, $kjstacho_LWIP_Users_id, $comment, $insertDate);
        $stmt->fetch();
        $mysqli2 = getMySQLi();
        
        echo "<form name='updateComment' method='Post' action='LWIP_updateRecord.php' '>
            <table class='table table-bordered'>
            <tr><td>Comment</td><td><input name='comment' value='$comment' size=20></td></tr>
            <tr><td>Item</td><td><select name='".itemsTable."_id'>";
            /* fetch the data for the options */
            if($items = $mysqli2->query("select * from ".itemsTable))
            {
                /* fetch results as object (since there is only 1 row, i dont need a while loop here). */
                while($row = $items->fetch_object())
                {
					/* display the options */
                    echo "<option value=".$row->kjstacho_LWIP_Items_id;
					
					/* select the option if it matches bound id */
                    if($row->kjstacho_LWIP_Items_id == $kjstacho_LWIP_Items_id)
                    {
                        echo " selected";
                    }
                    echo ">".$row->title." - ".$row->description."</option>";
                }
                $mysqli2->close();
            }
            else
            {
                echo "Create Prepare failed: (" . $mysqli2->errno . ") " . $mysqli2->error;
                exit();
            }
            echo "</select></td></tr>";
    }
    
	/* add the table and id to hidden elements */    
    echo"<tr><td align=center><input class='btn btn-lg-primary' type='reset' name='Reset' value='Reset'></td><td align=center><input class='btn btn-lg-primary' type=submit name='Submit' value='Submit'></td></tr>
        </table>
        <input type=hidden name=table value='$table' />
        <input type=hidden name=id value='$id' />
        <input type=hidden name=update value='true' />
        </form>";
		
	if($enabled == 'false'){
		echo "<script>disableInput();</script>";
	}
}