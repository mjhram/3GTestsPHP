<?php
define ("site_name",'Activate.php');
//called from activation link in email or login_register.php [for validation without email]

require '../functions.php';
require '../connect.php';

error_reporting(E_ALL ^ E_NOTICE);

session_name('aTTS');
// Starting the session
session_start();

	if(!isset($_GET['usrid']) || !isset($_GET['act'])) {
	$_SESSION['msg'][] = 'Bad activation link.';
	header("Location:login_register.php");
	exit;
	}
	
	$_GET['usrid'] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['usrid']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$sSql = "SELECT managers.*, regions.*, members.id,usr,password, email,actCode,department, `business unit`, section FROM members ";
	$sSql .= "LEFT JOIN managers ON ( managers.region_id = members.region AND managers.dept_id = members.department )LEFT JOIN regions ON members.region=regions.id LEFT JOIN departments ON members.department=departments.id ";
	$sSql .= "WHERE members.id='{$_GET['usrid']}'";
	
	$row = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sSql));
	if(empty($row['email'])) {
		$emailexists = false;
	} else {
		$emailexists = true;
	}
	//if the manager email is nil, use default one:
	if(empty($row['manager_email'])) {
			$row['manager_email'] = "mjhram@yahoo.com";
			$_SESSION['msg'][] = "Default email has been used as manager email.";
	}
	if($row['id'] != $_GET['usrid']) { 
			$_SESSION['msg'][] = 'This usr is not registered or the Activation was expired.';
			$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$aSql .= "'Activation:{$_GET['act']}', '{$msgStr}', 'Failed', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
			header("Location:login_register.php");
			exit;
		}
	if($row['actCode'] != $_GET['act']) {
		$_SESSION['msg'][] =  'Wrong activation code.';
		$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$aSql .= "'Activation:{$_GET['act']}', '{$msgStr}', 'Failed', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
		header("Location:login_register.php");
		exit;
		}
	if($_GET['do'] == 'cancel') {
			$sSql = "DELETE FROM members WHERE id='{$_GET['usrid']}'";
			mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
			$_SESSION['msg'][] = 'Registeration has been canceled.';
			$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$eSql = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $sSql) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql .= "'Activation:{$_GET['act']}=>{$_GET['do']}=>{$eSql}', '{$msgStr}', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
			header("Location:login_register.php");
			exit;
	} else if($_GET['do'] == 'approved'){
		$aCode = substr(md5(microtime().rand(1,100000)),0,6);
		$sSql = "UPDATE members SET approved=1, ActCode='{$aCode}' WHERE id='{$_GET['usrid']}'";
		mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
		$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$eSql = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $sSql) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql .= "'Activation:{$_GET['act']}=>{$_GET['do']}=>{$eSql}', '{$msgStr}', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
		if(!$emailexists) {
				$_SESSION['msg'][] = 'Account has been Approved successfully.<br /> Password has been set to default: 123456';
		} else {
				$_SESSION['msg'][] = 'Email has been Approved successfully.<br /> Notification email will be sent to applicant.';
				$temp= "Dear Mr./Miss ".ucfirst($row['usr']).",<br />";
				$temp .="Kindly your registration for System has been approved. <br />Thanks<br />";
				$temp .= "username: {$row['usr']}<br />";
				$temp .= "password: {$row['password']}<br />";
				$temp .="Region: {$row['region']}<br />";
				$temp .="Department: {$row['business unit']}/{$row['section']}<br />";
				$temp .= "<br /><br /><br />Best Regards, <br />Site Administration";
 				$headers   = array();
 				$headers[] = "MIME-Version: 1.0";
 				$headers[] = "Content-type: text/html; charset=utf-8";
 				$headers[] = "From: noreply.atts@a.com";
 				$headers[] = "CC: mjhram@yahoo.com, {$row['manager_email']}";
 				$headers = implode("\r\n", $headers);
 		
 				$b_temp = send_mail(	'noreply.atts@a.com',
								$row['email'],
								'Internet Test: Registration Approval',
								$temp, $headers);
				$result = "Success";
				if(!$b_temp) {
						$_SESSION['msg'][] = "Couldnot send notification email to {$row['email']}. Please contact the site addministration";
						$result = "Failed";
				}
				$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
				$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
				if(!$_SESSION['usr']) {
							$aSql .= "-1,";
				} else {
					$aSql .= "{$_SESSION['id']},";
				}
				$temp = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $temp) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
				$aSql .= "'Email: Notification=>{$temp}', '{$msgStr}', '{$result}', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
				mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
			}
	} else if($_GET['do'] == 'disapproved') {
		if(!$emailexists) {
				$_SESSION['msg'][] = "Registeration for the user {$row['usr']} has been disapproved.";
		} else {
				$_SESSION['msg'][] = 'Registeration has been disapproved.<br /> Notification email will be sent to applicant.';
				$aCode = substr(md5(microtime().rand(1,100000)),0,6);
				mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE members SET approved=0, ActCode='{$aCode}' WHERE id='".$_GET['usrid']."'");
				$temp= "Dear Mr./Miss ".ucfirst($row['usr']).",<br />";
				$temp .="Kindly your registration for System has been disapproved. <br />Thanks<br />";
				$temp .="Account Name: {$row['usr']}<br />";
				$temp .="Region: {$row['region']}<br />";
				$temp .="Department: {$row['business unit']}/{$row['section']}<br />";
				$temp .= "<br /><br /><br />Best Regards, <br />Site Administration";
 				
 				$headers   = array();
 				$headers[] = "MIME-Version: 1.0";
 				$headers[] = "Content-type: text/html; charset=utf-8";
 				$headers[] = "From: noreply.atts@a.com";
 				$headers[] = "CC: mmm20122002-a@yahoo.com, {$row['manager_email']}";
 				$headers = implode("\r\n", $headers);
 				
 				$b_temp = send_mail(	'noreply.atts@a.com',
								$row['email'],
								'Internet Test: Registration Approval',
								$temp, $headers);
					$result = "Success";
					if(!$b_temp) {
						$_SESSION['msg'][] = "Couldnot send notification email to {$row['email']}. Please contact the site addministration";
						$result = "Failed";
					} 
					$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
					$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
					if(!$_SESSION['usr']) {
								$aSql .= "-1,";
					} else {
						$aSql .= "{$_SESSION['id']},";
					}
					$temp = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $temp) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
					$aSql .= "'Email: Disapproved=>{$temp}', '{$msgStr}', '{$result}', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
					mysqli_query($GLOBALS["___mysqli_ston"], $aSql); 
		}
		$sSql = "DELETE FROM members WHERE id='{$_GET['usrid']}'";
		mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
		
		$result = "Success";
		$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
		if(!$_SESSION['usr']) {
					$aSql .= "-1,";
		} else {
			$aSql .= "{$_SESSION['id']},";
		}
		$eSql = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $sSql) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$aSql .= "'Activation:{$_GET['act']}=>{$_GET['do']}=>{$eSql}', '{$msgStr}', '{$result}', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
		mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
	} else {
			$aCode = substr(md5(microtime().rand(1,100000)),0,6);
			$sSql = "UPDATE members SET Activated=1, ActCode='{$aCode}' WHERE id='{$_GET['usrid']}'";
			mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
			$_SESSION['msg'][] = 'Account has been Validated successfully.<br /> An email will be sent to management for approval.';
			
			$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$eSql = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $sSql) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			$aSql .= "'Activation:{$_GET['act']}=>{$_GET['do']}=>{$sSql}', '{$msgStr}', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
			
			$manager_name=explode('@', $row['manager_email']);
			$temp= "Dear Mr./Miss ".ucfirst($manager_name[0]).",<br />";
			$temp .="Kindly Mr./Miss {$row['usr']} has applied for System.<br />";
			$temp .="Account Name: {$row['usr']}<br />";
			$temp .="Region: {$row['region']}<br />";
			$temp .="Department: {$row['business unit']}/{$row['section']}<br />";
			$temp .="<br />Kindly need your approval:<br />";
			$temp .= "<a href='http://10.5.13.46/ATS/en/Activate.php?act={$aCode}&usrid={$row['id']}&do=approved'>Approved</a> <br />OR<br />";
			$temp .= "<a href='http://10.5.13.46/ATS/en/Activate.php?act={$aCode}&usrid={$row['id']}&do=disapproved'>Disapproved</a>";
			$temp .= "<br /><br /><br />Best Regards, <br />Site Administration";
  		
 			$headers   = array();
 			$headers[] = "MIME-Version: 1.0";
 			$headers[] = "Content-type: text/html; charset=utf-8";
 			$headers[] = "From: noreply.atts@a.com";
 			$headers[] = "CC: mmm20122002-a@yahoo.com";
 			$headers = implode("\r\n", $headers);
 			
 			$b_temp = send_mail(	'noreply.atts@a.com',
						$row['manager_email'],
						'Internet Test: Registration Approval',
						$temp, $headers);
			$result = "Success";
			if(!$b_temp) {
				$_SESSION['msg'][] = "Couldnot send approval email to management. Please contact the site addministration";
				$result = "Failed";
			}
				
					$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
					$aSql = "INSERT INTO log(user, details, msg, result, ip,url,sitename) VALUES(";
					if(!$_SESSION['usr']) {
								$aSql .= "-1,";
					} else {
						$aSql .= "{$_SESSION['id']},";
					}
					$temp = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $temp) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
					$aSql .= "'Email: Application=>{$temp}', '{$msgStr}', '{$result}', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
					mysqli_query($GLOBALS["___mysqli_ston"], $aSql); 
	}
	header("Location:login_register.php");
	
?>