<?php



/* Database config */

$db_host		= 'localhost';
$db_user		= 'mjhram3g_db';
$db_pass		= 'passInto1';
$db_database	= '3gTestsDB'; 

/* End config */

$link = $con = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_host,  $db_user,  $db_pass)) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

((bool)mysqli_query($link, "USE " . $db_database));
//echo("Error description1: " . mysqli_error($con));
mysqli_query($GLOBALS["___mysqli_ston"], "SET names UTF8");
//echo("Error description2: " . mysqli_error($con));
?>