<?php
//This file is used by index.php
error_reporting(E_ALL ^ E_NOTICE);
define ("site_name",'action.php');

	require '../connect.php';
	require '../functions.php';
	//Start session
	session_name('aTTS');
	session_start();
	
if(!$_SESSION['dev'])	 {
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['usr']) || (trim($_SESSION['id']) == '')) {
		header("location: index.php");
		exit();
	}
}
if($_POST['submit']=='Go') {
	$_SESSION['items_per_page'] = (int) $_POST['sel_perpage'];
	if($_SESSION['dev']) {
		$stemp = "dev={$_SESSION['dev']}";
	}

	$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
	if(!$_SESSION['usr']) {
				$aSql .= "-1,";
	} else {
		$aSql .= "{$_SESSION['id']},";
	}
	$aSql .= "'Go=>items per page={$_SESSION['items_per_page']}', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
	//$_SESSION['msg'][]=$sSql;
	mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
	//echo "location: index.php"."?{$stemp}";
	header("location: index.php"."?{$stemp}");
	exit();
}

if($_GET['action']='del') {
	$sSql="SELECT `trouble tickets`.*, count(tt_tasks.id) as tasks_count FROM `trouble tickets` LEFT JOIN tt_tasks ON tt_tasks.tt_id=`trouble tickets`.id group by `trouble tickets`.id HAVING `trouble tickets`.id={$_GET['tt_id']}";
	$row =mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sSql));
	$result = "Failed";
	if($row) {
		if($row['owner']==$_SESSION['id'] and $row['tasks_count']<=2) {
			mysqli_query($GLOBALS["___mysqli_ston"], "START TRANSACTION");
			$sSql="DELETE FROM `tt_tasks` WHERE tt_id={$_GET['tt_id']}";
			if(mysqli_query($GLOBALS["___mysqli_ston"], $sSql)){
				$sSql="DELETE FROM `trouble tickets` WHERE id={$_GET['tt_id']}";
				if(mysqli_query($GLOBALS["___mysqli_ston"], $sSql)) {
					mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");
					$_SESSION['msg'][] = "Ticket {$_GET[tt_id]} has been deleted successfully";
					$result = "Success"; 
				} else {
					$_SESSION['msg'][] = "Couldnot remove ticket:".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
				}
			}else {
					$_SESSION['msg'][] = "Couldnot remove ticket's tasks:".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
			}
		} else {
			$_SESSION['msg'][] = "You shouldnot delete this ticket";
		}
	} else {
		$_SESSION['msg'][] = "Couldnot find the ticket";
	}

	$msgStr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], implode(";", $_SESSION['msg'])) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$aSql = "INSERT INTO log(user, details, msg, sql, result, ip,url,sitename) VALUES(";
	if(!$_SESSION['usr']) {
				$aSql .= "-1,";
	} else {
		$aSql .= "{$_SESSION['id']},";
	}
	$aSql .= "'Delete Ticket:{$_GET[tt_id]}', '{$msgStr}', '{$sSql}', '{$result}', '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['REQUEST_URI']}','".site_name."')";
	//$_SESSION['msg'][]=$sSql;
	mysqli_query($GLOBALS["___mysqli_ston"], $aSql);	
}
header("Location:index.php");
?>