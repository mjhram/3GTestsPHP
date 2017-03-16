<?php
//define ("items_per_page",);
define('BGDTIMEZONE', 'Etc/GMT+3');


define ("showdescmax", 150);
define ("site_name",'index.php');

require '../connect.php';
require '../functions.php';

error_reporting(E_ALL ^ E_NOTICE);
session_name('aTTS');
// Starting the session
session_start();

if(!$_GET['dev']) {
	unset($_SESSION['dev']);
} else {
	$_SESSION['dev'] = $_GET['dev'];
}
if($_GET['dev']) {
	if($_GET['ver'] && strcmp($_GET['ver'],"Feb12") != 0) 
	{
		header("Location:update.php");
	} else if(!$_GET['ver']) {
		header("Location:update.php");
	}
}
if(!$_SESSION['usr'] && !$_GET['dev']) {
	header("Location:login_register.php");
	exit;
}
			
$_SESSION['ar_en']=1;
if(isset($_GET['logoff']))
{
	$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
	if(!$_SESSION['usr']) {
				$aSql .= "-1,";
	} else {
		$aSql .= "{$_SESSION['id']},";
	}
	$aSql .= "'Logoff', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
	mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
	$_SESSION = array();
	session_destroy();
	
	header("Location: index.php");
	exit;
}

if(!$_SESSION['items_per_page'])
	$_SESSION['items_per_page'] = 32;
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Internet Tests: Home</title>

<script type="text/javascript">
 function state_onchange(obj)
 {
 		<?php
 		$v_get = $_GET;
 		?>
 		if(obj.value == "") {
 			window.location.replace("index.php");
		}  else {
			if(obj.value == 10) { //== special case for "open" state=0 [considered empty by php]
				window.location.replace(
				<?php
						if($v_get['act']) {
							unset($v_get['act']);
						}
						if($v_get['criteria']) {
							unset($v_get['criteria']);
						}
						$stemp = http_build_query($v_get, '', '&amp;');
						if(!empty($stemp)) {
							$stemp .= "&";
						}
						$stemp .="act=sort&criteria=".urlencode("state=0");
						echo "'index.php?{$stemp}'";
				?>
				);
			} else if(obj.value == 1){
				window.location.replace(
				<?php
						if($v_get['act']) {
							unset($v_get['act']);
						}
						if($v_get['criteria']) {
							unset($v_get['criteria']);
						}
						$stemp = http_build_query($v_get, '', '&amp;');
						if(!empty($stemp)) {
							$stemp .= "&";
						}
						$stemp .="act=sort&criteria=".urlencode("state=1");
						echo "'index.php?{$stemp}'";
				?>
				);
			} else if(obj.value == 2){
				window.location.replace(
				<?php
						if($v_get['act']) {
							unset($v_get['act']);
						}
						if($v_get['criteria']) {
							unset($v_get['criteria']);
						}
						$stemp = http_build_query($v_get, '', '&amp;');
						if(!empty($stemp)) {
							$stemp .= "&";
						}
						$stemp .="act=sort&criteria=".urlencode("state=2");
						echo "'index.php?{$stemp}'";
				?>
				);
			}
		}
 }
</script>
 
<style type="text/css">
	
	#topframe {
		position:fixed;
		top:0;
		left:0;
		z-index:10;
		background: #DA0C35;
	}
	
	body{
		background: #DA0C35;
	}
	a {
		color:white;
		}
	a.anchor2 {
		text-decoration: none;	
	}
#menu
{
  height: 31px;
  padding-right: 2px;
  margin: 0;
  list-style: none;   
}
	#menu li
{
	float: right;
	display: block;
	width: 100px;
	height: 31px;
}
#menu a
{
  display: block;
  width: 90px;  height: 31px;  
  background-color: inherit;
  text-decoration: none;
  line-height: 31px;
  text-align: center;
}
#main_content {
	position: relative;
	top:180px;
	right: auto;
	width:100%
}
table {
	border-collapse:collapse;
}
th {
	background:#00ffff;	
}
#search1 {
	display: none;
	float: none;
}
label {
	clear: both;
	width: 90px;
	text-align:right;
}
input.bt_Save {
	border-style: none;
	width: 94px;
	height: 24px;
	color: white;
	background: transparent url(../images/bt_register.png) no-repeat 0 0;
}
.field {
	width: 100px;
}
</style>
</head>

<body>
<div >

<?php
$aSql = "INSERT INTO log(user, details, ip,url,sitename) VALUES(";
if(!$_SESSION['usr']) {
			$aSql .= "-1,";
} else {
	$aSql .= "{$_SESSION['id']},";
}
$aSql .= "'Site Access', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
?>

Hello 
		<?php 
		if(!$_SESSION['usr']):
			echo 'Guset';
		?>
			<a href="login_register.php">Login|Register</a>
		<?php 	
		else:
		?>
			 <!--
			 <a href="profile.php">
			 <?php echo($_SESSION['usr'])?>
			 </a>
			 --> | <a href="login_register.php?logoff=logoff">Log off</a>
		<?php
			endif;
		?> 
		<ul id="menu">
			<?php 
			if($_SESSION['usr']) {
				if($_GET['act']=='sort') {
					echo "<li><a href='index.php'>Home</a></li>";
				}
				$stemp = http_build_query($_GET, '', '&amp;');
				//print_r($stemp);
				if(!empty($stemp)) {
					$stemp = "?".$stemp;
				}
				echo "<li><a href='index.php{$stemp}'>Refresh</a></li>";
			}
			?>
			<li><a href='https://play.google.com/store/apps/details?id=com.Mohammad.ac.SpeedTest&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1' target='_blank'><img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png' height="42"/></a></li>
			<?php
			$exp = "export.php";
			if(isset($_GET['dev'])) {
				$exp .= "?dev=" . $_GET['dev'];
			}
			echo "<li><a href='{$exp}'>Data</a></li>";
			?>
		</ul>
<hr />
</div>		
<div>
	<div style="text-align:center">
	<?php
		if(!empty($_SESSION['msg'])) {
			foreach($_SESSION['msg'] as $m) {
				echo "{$m} <br />";
			}
			echo "<hr />";
			unset($_SESSION['msg']);
		}
		?>
	</div>
	
<table width="100%" border="1">
	<col width="3%" /> <!-- No.-->
	<col width="12%" /> <!-- Date/Time.-->
	<col width="20%" /> <!-- Latitude, Longitude -->
	<col width="10%" /> <!-- Model-->
	<col width="10%" />  <!-- imsi -->
	<col width="8%" /> <!-- net name -->
	<col width="6%" />  <!-- net type -->
	<col width="10%" /> <!-- mobile data state -->
	<col width="5%" /> <!-- cell id -->
	<col width="5%" />   <!-- lac -->
	<col width="6%" /> <!-- region -->
	<col width="5%" /> <!-- rnc -->
	<col width="10%" /> <!-- rssi -->
	<col width="5%" /> <!-- Ecio -->
	<col width="15%" /> <!-- max Rx rate -->
	<col width="15%" /> <!-- max Tx rate -->
	
	<tr>
		<th>No</th> 
		<th>Date\Time</th>
		<th>Latitude,Longitude</th>
		<th>Handset Model</th>
		<th>Operator1-2</th>
		<th>Network1-Network2</th>
		<th>Type1-Type2</th>
		<th>State</th>
		<th>Wifi SSID</th>
		<th>Cell</th>
		<th>LAC/TAC</th>
		<th>Region</th>
		<th>RNC</th>
		<th>Signal Strength</th>
		<th>Ecio</th>
		<th>Max DL Rate</th>
		<th>Max UL Rate</th>
	</tr>
	
	<?php
		if (isset($_GET["page"])) { 
			$page  = $_GET["page"]; 
		} else { 
			$page=1; 
		};
		// get user info:
		/*$sSql="SELECT * FROM members WHERE id={$_SESSION['id']}";
		$member_row=mysql_fetch_assoc(mysql_query($sSql));*/
		
		$start_from = ($page-1) * $_SESSION['items_per_page'];
		/*$sSql="SELECT `trouble tickets`.*,  (if(state=1, UNIX_TIMESTAMP(max(`submitted date`))-UNIX_TIMESTAMP(`added date`), UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`added date`))/(3600*24)) as mxdate";
		$sSql .= ",`trouble tickets`.id as tt_id, members.usr, count(tt_tasks.id) as tasks_count, group_concat(concat(`next rgn`,`next dept`) order by tt_tasks.id) as dept_id, group_concat(concat(rgn,section) order by tt_tasks.id separator '=> ') as t_dept FROM `trouble tickets`";
		$sSql .= " LEFT JOIN members ON owner = members.id LEFT JOIN tt_tasks ON tt_tasks.tt_id=`trouble tickets`.id LEFT JOIN departments ON `next dept`=departments.id LEFT JOIN regions ON `next rgn`=regions.id";
		$sSql .= " group by `trouble tickets`.id HAVING ";
		if($_GET["act"] == 'sort' && isset($_GET['criteria']) && !empty($_GET['criteria'])){
			//$s_criteria=implode(' AND ', $_SESSION['criteria']);
			$sSql .= $_GET['criteria']." AND ";
		}
		$sSql.=" (owner={$_SESSION['id']} OR FIND_IN_SET({$member_row['region']}{$member_row['department']}, dept_id) OR {$member_row['department']}=5) ORDER BY ";
		if($_GET["act"] == 'sort' && isset($_GET['orderby']) && !empty($_GET['orderby'])){
			//$s_orderby=implode(',', $_SESSION['orderby']);
			$sSql .= $_GET['orderby'].", ";
		}
		$sSql .= "`trouble tickets`.id DESC";*/
		//echo $sSql;
		$sSql = "SELECT 3gTests.*, tbl_lac.region FROM 3gTests LEFT JOIN tbl_lac ON 3gTests.lac = tbl_lac.lac ";
		if($_GET['dev']) {
			$sSql .= " WHERE deviceId = '" . $_GET['dev'] . "' ";
		} else {
			$sSql .= " WHERE deviceId<>'355355055584832' ";
		}
		$sSql .= " ORDER BY  `3gTests`.`No` DESC ";
		$rs_result = mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
		if($rs_result) {
			$total_records = mysqli_num_rows($rs_result);
			//echo $sSql;
			$categories_res=mysqli_query($GLOBALS["___mysqli_ston"], $sSql." LIMIT ".$start_from.", ".$_SESSION['items_per_page']);
			for ($i = 0; $row = mysqli_fetch_assoc($categories_res); $i++)	{
				?>
				<tr>
				<td>
					<?php
					//echo $row['No'];
					echo $i+1;
					?>
				</td>	
				<td>
					<?php
					if(strcmp($row['time'],'0000-00-00 00:00:00')==0) {
					} else {
						$schedule_date = new DateTime($row['time'], new DateTimeZone('America/New_York') );
						$schedule_date->setTimeZone(new DateTimeZone('Asia/Baghdad'));
						$triggerOn =  $schedule_date->format('Y-m-d H:i');
						echo $triggerOn;
					}
					?>
				</td>
				<td>
					<?php
					echo $row['lat']. "," . $row['lon'];
					?>
				</td>
				<td>
					<?php
					echo $row['Model'];
					?>
				</td>
				
				<td>
					<?php
					echo $row['netOperator']."-".$row['netOperator2'];
					?>
				</td>
				<td>
					<?php
					echo $row['netName']."-".$row['netName2'];
					?>
				</td>
				<td>
					<?php
					$networkType = array("", "GPRS", "EDGE", "UMTS", "CDMA", 
						"EVDO_0", "EVDO_A", "1xRTT", "HSDPA", "HSUPA", "HSPA", 
						"IDEN", "EVDO_B", "LTE", "EHRPD", "HSPAP", "GSM");
					echo $networkType[$row['netType']]."-".$networkType[$row['netType2']];
					
					?>
				</td>
				<td>
					<?php
					echo $row['mobileState'];
					?>
				</td>
				<td>
					<?php
					echo $row['wifissid'];
					?>
				</td>
				<td>
					<?php
					$tmp = str_pad($row['cid_3g'], 5, "0", STR_PAD_LEFT);
					echo $tmp;
					?>
				</td>
				<td>
					<?php
					$tmp = str_pad($row['lac'], 5, "0", STR_PAD_LEFT);
					echo $tmp;
					?>
				</td>	
				<td>
					<?php
					echo $row['region'];
					?>
				</td>
				<td>
					<?php
					echo $row['rnc'];
					?>
				</td>
				<td>
					<?php
					$sig = explode(" ", $row['SignalStrengths'], 10);
					if(strcmp($row['Model'],'HUAWEI P7-L10')==0) {
						echo $sig[3];
					} else {
					    if(strcmp($row['rssi'],"99")==0 || strcmp($row['rssi'],"0")==0) {
					    	echo "NA";
					    } else {
					    	echo $row['rssi'];
					    }
					}
					?>
				</td>
				<td>
					<?php
					//$sig = explode(" ", $row['SignalStrengths'], 10);
					if(strcmp($sig[4],"-1")!=0) {
						echo $sig[4];
					}
					?>
				</td>
				<td>
					<?php
					echo $row['maxRxRate'];
					?>
				</td>
				<td>
					<?php
					echo $row['maxTxRate'];
					?>
				</td>
				</tr>
				<?php
			}
		}
	?>
	</table>

	<div id="pages" style="text-align:center">
	<form action="action.php" method="post" enctype="multipart/form-data">
	Show <select name="sel_perpage">
	<option <?php echo ($_SESSION['items_per_page']==8) ? 'selected="selected"':''; ?>>8</option>
	<option <?php echo ($_SESSION['items_per_page']==16) ? 'selected="selected"':''; ?>>16</option>
	<option <?php echo ($_SESSION['items_per_page']==32) ? 'selected="selected"':''; ?>>32</option>
	<option <?php echo ($_SESSION['items_per_page']==64) ? 'selected="selected"':''; ?>>64</option>
	<option <?php echo ($_SESSION['items_per_page']==100) ? 'selected="selected"':''; ?>>100</option>
	<option <?php echo ($_SESSION['items_per_page']==200) ? 'selected="selected"':''; ?>>200</option>
	<option <?php echo ($_SESSION['items_per_page']==1000) ? 'selected="selected"':''; ?>>1000</option>
	</select>per page 
	
		<input type="submit" name="submit" value="Go" class="bt_Save" />
	</form>
	
	<div></div>  
	Go to page: <?php
	
		for ($i = 1; $i <= 1 + ($total_records-1)/$_SESSION['items_per_page'];$i++){
				if($i!=1) 
					echo '-';
				if ($page == $i) {
					echo $i;
				} else {
					if(isset($_GET['page'])){
						unset($_GET['page']);
					}
					$stemp = http_build_query($_GET, '', '&amp;');
					//print_r($stemp);
					if(!empty($stemp)) {
						$stemp = $stemp."&";
					}
					echo "<a href='index.php?{$stemp}page={$i}'>{$i}</a>";
				}
				
			}
	?>
	</div>
</div> 
<!-- ======= OWA start ======= -->
</body>
</html>