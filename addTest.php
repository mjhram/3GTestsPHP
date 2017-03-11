<?php
define('INCLUDE_CHECK',true);
define ("site_name",'addTest.php');

require 'connect.php';

// Starting the session
session_name('aTTS');
session_start();

if(isset($_POST['latency'])){
	$latency = $_POST['latency'];
} else {
	$latency = "-1";
}
$a = mysqli_query($GLOBALS["___mysqli_ston"], "START TRANSACTION");
$b="INSERT INTO 3gTests(`deviceId`,`imsi`, `phoneNumber`, `imei`,`netOperator`, `netName`, `netType`, `netClass`,`phoneType`,`mobileState`, `wifissid`, `cid`, `cid_3g`, `rnc`, `lac`, `rssi`,".
		" `lon`, `lat`, `minTxRate`, `maxTxRate`, `avTxRate`, `minRxRate`, `maxRxRate`, `avRxRate`, `Brand`, `Manufacturer`, `Model`, `Product`".
		",`deviceId2`,`imsi2`, `phoneNum2`,`netOperator2`, `netName2`, `netType2`, `netClass2`, `SignalStrengths`".
		",`latency`,`nei`,`tmp`) ".
     		//"VALUES('imsi', 'phoneNumber', 'imei','netOperator', 'netName', '1',       'netClass','1',         '1',                  '1', '1', '1', '1', '1')";
     		" VALUES('{$_POST['deviceId']}', '{$_POST['imsi']}', '{$_POST['phoneNumber']}', '{$_POST['imei']}', '{$_POST['netOperator']}', '{$_POST['netName']}',".
     		" '{$_POST['netType']}', '{$_POST['netClass']}', '{$_POST['phoneType']}', '{$_POST['mobileState']}', '{$_POST['wifissid']}', '{$_POST['cid']}', '{$_POST['cid_3g']}', '{$_POST['rnc']}', '{$_POST['lac']}', '{$_POST['rssi']}',".
     		" '{$_POST['lon']}', '{$_POST['lat']}', '{$_POST['minTxRate']}', '{$_POST['maxTxRate']}', '{$_POST['avTxRate']}', '{$_POST['minRxRate']}', '{$_POST['maxRxRate']}', '{$_POST['avRxRate']}',  '{$_POST['brand']}', '{$_POST['manuf']}', '{$_POST['model']}', '{$_POST['product']}',".
     		" '{$_POST['deviceId2']}', '{$_POST['imsi2']}', '{$_POST['phoneNum2']}', '{$_POST['netOperator2']}', '{$_POST['netName2']}', '{$_POST['netType2']}', '{$_POST['netClass2']}', '{$_POST['SignalStrengths']}'".
     		", '{$latency}', '{$_POST['nei']}', '{$_POST['tmp']}')";
	//echo $b; 
	$a=mysqli_query($GLOBALS["___mysqli_ston"], $b);
if($a) {
	mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");
	$res = "success";
} else {
	mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
	echo 'Could not add. Check your data.\n';
	echo $b;
	$res = "failed";
}
{
	$aSql = "INSERT INTO log(user, details, result, ip,url,sitename, params) VALUES(";
	if(!$_SESSION['usr']) {
		$aSql .= "-1,";
	} else {
		$aSql .= "{$_SESSION['id']},";
	}
	$pst = implode(",",$_POST);
	$aSql .= "'addTest', '{$res}', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."', '{$pst}')";
	mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
}
?>
