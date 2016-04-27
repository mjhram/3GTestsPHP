<?php
define('INCLUDE_CHECK',true);

require 'connect.php';

session_name('aTTS');
session_start();
$sSql = "SELECT * FROM ooklaTests WHERE `Date`='" . $_POST['Date'] . "' AND `ConnType`='" . $_POST['ConnType'] .
		"' AND `Lat`='". $_POST['Lat'] ."' AND `Lon`='". $_POST['Lon'] ."' AND `Download`='". $_POST['Download'] .
		"' AND `Upload`='". $_POST['Upload'] ."' AND `Latency`='". $_POST['Latency'] ."' AND `ServerName`='". $_POST['ServerName'] .
		"' AND `InternalIp`='". $_POST['InternalIp'] ."' AND `ExternalIp`='". $_POST['ExternalIp']."'";
$rs_result = mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
$doInsert = true;
if($rs_result) {
	$total_records = mysqli_num_rows($rs_result);
	if($total_records > 0) {
		$doInsert = false;
	}
} 
if($doInsert) {
	$a = mysqli_query($GLOBALS["___mysqli_ston"], "START TRANSACTION");
	$b="INSERT INTO ooklaTests(`Date`,`ConnType`,`Lat`,`Lon`,`Download`,`Upload`,`Latency`,`ServerName`,`InternalIp`,`ExternalIp`) ".
     		" VALUES('{$_POST['Date']}', '{$_POST['ConnType']}', '{$_POST['Lat']}', '{$_POST['Lon']}', ".
     		"'{$_POST['Download']}', '{$_POST['Upload']}',".
     		" '{$_POST['Latency']}', '{$_POST['ServerName']}', '{$_POST['InternalIp']}', '{$_POST['ExternalIp']}')";
	$a=mysqli_query($GLOBALS["___mysqli_ston"], $b);
	if($a) {
		mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");
	} else {
		mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
		echo 'Could not add. Check your data.\n';
	}
}
?>
