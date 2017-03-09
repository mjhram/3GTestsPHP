<?php
require '../connect.php';
require '../functions.php';

session_name('aTTS');
session_start();

define ("site_name",'export.php');

//function export() 
{
  $file_name = '3gtests.csv';
  $file_path = "../files_db/" . $file_name; 

 	$fileh = fopen ($file_path, "wb");
	if(!$fileh){
		$_SESSION['msg'][]= "Error: couldnot create {$fileh}";
		return;
	}
	if(!$_SESSION['usr'] && !$_GET['dev']) {
		//header("Location:login_register.php");
		exit;
	}
	////////////////// log
	$aSql = "INSERT INTO log(user, details, ip,url,sitename) VALUES(";
	if(!$_SESSION['usr']) {
				$aSql .= "-1,";
	} else {
		$aSql .= "{$_SESSION['id']},";
	}
	$aSql .= "'Site Access', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
	mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
	/////////////////// 
	//query data:
	$sSql = "SELECT 3gTests.*, tbl_lac.region FROM 3gTests LEFT JOIN tbl_lac ON 3gTests.lac = tbl_lac.lac ";
	if($_GET['dev']) {
		$sSql .= " WHERE deviceId = '" . $_GET['dev'] . "' ";
	} else {
		$sSql .= " WHERE deviceId<>'355355055584832' OR deviceId<>'353415067009434'";
	}
	$sSql .= " ORDER BY  `3gTests`.`No` DESC ";
	$export = mysqli_query($GLOBALS["___mysqli_ston"], $sSql) or die ( "Sql error : " . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
 	$fields = (($___mysqli_tmp = mysqli_num_fields( $export )) ? $___mysqli_tmp : false);
	//write headers:
	$data = "No, DateTime, phone No, IMSI, Network, Type, Mobile State, Wifi SSID, Cell, LAC, Region, RNC, Signal Strength, Min Rate, Max Rate\n";
	$len = strlen($data);
	$l = fwrite($fileh, $data, $len);

	$i = 0;
	while( $row = mysqli_fetch_assoc( $export ) )
	{
	    $i = $i + 1;
	    $line = '';
	    $line .= $i .", ";
	    if(strcmp($row['time'],'0000-00-00 00:00:00')==0) {
		} else {
			$schedule_date = new DateTime($row['time'], new DateTimeZone('America/New_York') );
			$schedule_date->setTimeZone(new DateTimeZone('Asia/Baghdad'));
			$triggerOn =  $schedule_date->format('Y-m-d H:i');
		}
	    $line .= $triggerOn .", ";
	    $line .= $row['phoneNumber'] .", ";
	    $line .= $row['imsi'] .", ";
	    $line .= $row['netName'] .", ";
		$networkType = array("UNKNOWN", "GPRS", "EDGE", "UMTS", "CDMA", 
			"EVDO_0", "EVDO_A", "1xRTT", "HSDPA", "HSUPA", "HSPA", 
			"IDEN", "EVDO_B", "LTE", "EHRPD", "HSPAP", "GSM");
	    $line .= $networkType[$row['netType']] .", ";
	    $line .= $row['mobileState'] .", ";
		$line .= $row['wifissid'] .", ";
	    	$tmp = str_pad($row['cid_3g'], 5, "0", STR_PAD_LEFT);
	    $line .= $tmp .", ";
	    	$tmp = str_pad($row['lac'], 5, "0", STR_PAD_LEFT);
	    $line .= $tmp .", ";
	    $line .= $row['region'] .", ";
	    $line .= $row['rnc'] .", ";
	    if(strcmp($row['rssi'],"99")==0) {
	    	$line .= "NA" .", ";
	    } else {
	    	$line .= $row['rssi'] .", ";
	    }
	    $line .= $row['minRxRate'] .", ";
	    $line .= $row['maxRxRate'] ."\n";   
		$len = strlen($line);
		$l = fwrite($fileh, $line, $len);
	}
	
	fclose($fileh);
	header("Location:download.php?file={$file_path}");
}