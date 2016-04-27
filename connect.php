<?php



/* Database config */

$db_host		= 'localhost';
$db_user		= 'mjhram3g_db';
$db_pass		= 'passInto1';
$db_database	= '3gTestDB'; 

/* End config */


$link = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_host, $db_user, $db_pass)) or die('Unable to establish a DB connection');
((bool)mysqli_query($link, "USE " . $db_database));
mysqli_query($GLOBALS["___mysqli_ston"], "SET names UTF8");
?>