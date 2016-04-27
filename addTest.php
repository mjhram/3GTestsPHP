<?php
define('INCLUDE_CHECK',true);

require 'connect.php';

session_name('aTTS');
session_start();
$a = mysqli_query($GLOBALS["___mysqli_ston"], "START TRANSACTION");
$b="INSERT INTO 3gTests(`deviceId`,`imsi`, `phoneNumber`, `imei`,`netOperator`, `netName`, `netType`, `netClass`,`phoneType`,`mobileState`, `cid`, `cid_3g`, `rnc`, `lac`, `rssi`,".
		" `lon`, `lat`, `minTxRate`, `maxTxRate`, `minRxRate`, `maxRxRate`, `Brand`, `Manufacturer`, `Model`, `Product`".
		",`deviceId2`,`imsi2`, `phoneNum2`,`netOperator2`, `netName2`, `netType2`, `netClass2`, `SignalStrengths`".
		",`nei`,`tmp`) ".
     		//"VALUES('imsi', 'phoneNumber', 'imei','netOperator', 'netName', '1',       'netClass','1',         '1',                  '1', '1', '1', '1', '1')";
     		" VALUES('{$_POST['deviceId']}', '{$_POST['imsi']}', '{$_POST['phoneNumber']}', '{$_POST['imei']}', '{$_POST['netOperator']}', '{$_POST['netName']}',".
     		" '{$_POST['netType']}', '{$_POST['netClass']}', '{$_POST['phoneType']}', '{$_POST['mobileState']}', '{$_POST['cid']}', '{$_POST['cid_3g']}', '{$_POST['rnc']}', '{$_POST['lac']}', '{$_POST['rssi']}',".
     		" '{$_POST['lon']}', '{$_POST['lat']}', '{$_POST['minTxRate']}', '{$_POST['maxTxRate']}', '{$_POST['minRxRate']}', '{$_POST['maxRxRate']}', '{$_POST['brand']}', '{$_POST['manuf']}', '{$_POST['model']}', '{$_POST['product']}',".
     		" '{$_POST['deviceId2']}', '{$_POST['imsi2']}', '{$_POST['phoneNum2']}', '{$_POST['netOperator2']}', '{$_POST['netName2']}', '{$_POST['netType2']}', '{$_POST['netClass2']}', '{$_POST['SignalStrengths']}'".
     		", '{$_POST['nei']}', '{$_POST['tmp']}')";
	$a=mysqli_query($GLOBALS["___mysqli_ston"], $b);
if($a) {
		mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");
	} else {
		mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
		echo 'Could not add. Check your data.\n';
	}
?>
