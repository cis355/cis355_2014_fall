<?php
/**
 * The template file for displaying the header
 *
 * @author		Nicholas Chapin <nmchapin@svsu.edu>
 * @copyright	2014 Nicholas Chapin
 * @version		1.0.0
 *
 */
session_start();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Table Editor</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<style type="text/css">
			body {
				background-color: #FBFBFB;
				margin: 0 auto;
				min-width: 480px;
				padding: 0;
				width: 80%;
			}
			table {
				min-width: 350px;
				margin: 25px auto;
			}
			.error {
				background-color: #EB4646;
				border: solid 1px #B82B2B;
				border-radius: 3px;
				box-shadow: inset 0 0 1px #FFF;
				color: #FFF;
				font-size: 14px;
				padding: 10px;
				margin: 5px 0;
			}
			.error p, .status p {
				margin: 0;
				padding: 0;
			}
			.status {
				background-color: #46BAEB;
				border: solid 1px #2B8EB8;
				border-radius: 3px;
				box-shadow: inset 0 0 1px #FFF;
				color: #FFF;
				font-size: 14px;
				padding: 10px;
				margin: 5px 0;
			}
			#tabs {
				list-style: none;
				width: 330px;
				margin: 5px auto;
			}
			#tabs li {
				float: left;
				background-color: #EEE;
				padding: 10px;
				border: solid 1px #DDD;
				margin: 0 4px;
				border-radius: 4px;
			}
			a {
				color: #000;
				text-decoration: none;
			}
			table {
				border: solid 1px #CCC;
				box-shadow: 0 1px 2px #D1D1D1;
				color: #666;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
				text-shadow: 1px 1px 0px #fff;
			}
			table th {
				background: linear-gradient( #EDEDED, #EBEBEB);
				padding: 15px 25px 15px 25px;
				border-bottom: 1px solid #E0E0E0;
			}
			table th:first-child {
				text-align: left;
				padding-left: 20px;
			}
			table tr:first-child th:first-child {
				border-top-left-radius:3px;
			}
			table tr:first-child th:last-child {
				border-top-right-radius:3px;
			}
			table tr {
				padding-left: 20px;
				text-align: center;
			}
			table td:first-child {
				text-align: left;
				padding-left:20px;
				border-left: 0;
			}
			table td {
				background-color: #FAFAFA;
				border-bottom: solid 1px #E0E0E0;
				border-left: solid 1px #E0E0E0;
				padding: 10px;
			}
			table tr:nth-child(even) td {
				background-color: #F6F6F6;
			}
			table tr:last-child td {
				border-bottom: 0;
			}
			table tr:hover td {
				background-color: #F2F2F2;	
			}
			h4 {
				color: #555;
			}
			hr {
				border: 0;
				border-top: solid 1px #CCC;
			}
			form li {
				list-style: none;
				margin: 10px 0;
			}
		</style>
	</head>
	<body>
<?php
	if( isset( $_SESSION['user_id'] ) )
		echo "You are logged in as userid: " . $_SESSION['user_id'];
	else {

		echo "You are not currently logged in.<br>";
		echo 'Adding session variable $_SESSION[\'user_id\'] = \'6\'<br>';
		$_SESSION['user_id'] = 6;
		echo "You are logged in as userid: " . $_SESSION['user_id'];
		echo "<br><br>Refresh the page. The session variable will not need to be created again.";
	}
