<?php
/**
 * Purpose: This file will;
 *          be called as a webservice via ajax requests
 *
 * Author:  Kevin Stachowski
 * Date:    10/10/2014
 * Update:  10/10/2014
 * Notes:   These tables will be used by the LWIP project
 * 
 * External resources: This application depends on access to a mysql DB.
 */

require_once("LWIP_function.php");

if(isset($out))
	test();
else
	main();
	
function test()
{
	$mysqli = getMySQLi();
	echo "mysql object = " . json_encode($mysqli);
}
function main()
{
    global $service;
    
    $mysqli = getMySQLi();
    
    if($service == "login")
        login($mysqli);
    elseif($service == "create")
        insertLogin($mysqli);
}

/**
 * Purpose: This will check the users login form data. 
 * Pre:     It should be called when there is form post data.
 * Post:    User has been authenticated.
 */
function login($mysqli)
{
    global $email, $password;
	
    $encryptedPwd = encrypt($password);

    $sql = "select ".usersTable."_id ,email from ".usersTable." where email=? and password=?";
    if($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param('ss', $email, $encryptedPwd);
		$stmt->bind_result($id, $dbEmail);
        if($stmt->execute())
        {
            $stmt->fetch();
            if(($email == $dbEmail) and strlen($email) > 0)
            {
		echo "success:$id,$dbEmail";
            }
            else
                echo "Email or password incorrect!";
            $stmt->close();
        }
        elseif(errorsOn)
            echo "Insert execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    elseif(errorsOn)
        echo "Insert Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/**
 * Purpose: This function will select data from the table
 * Pre:     The MySQLi object must be connected.
 * Post:    The data is displayed or feedback is given on why it didn't.
 * 
 * @param   var   $mysqli  contains a connected MySQLi object.
 */
function insertLogin($mysqli)
{
    global $email, $password, $location;

    $encryptedPwd = encrypt($password);
	
    $sql = "insert into ".usersTable." (".locationsTable."_id,email,password) VALUES(?,?,?)";
    if($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param('iss', $location, $email, $encryptedPwd);
        if($stmt->execute())
        {
            echo "success:$id,$email";
            $stmt->close();
        }
        elseif(errorsOn)
            echo "Insert execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    elseif(errorsOn)
        echo "Insert Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
