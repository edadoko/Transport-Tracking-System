<?php
	$host = "localhost";
	$username = "root";
	$password = "**********";
	$db = "epokabus";

	$con = mysql_connect($host , $username, $password) or die ("Couldn't connect to Host!");
	mysql_select_db($db) or die ("Couldn't select database!");

?>
