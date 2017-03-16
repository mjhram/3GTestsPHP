<?php
define ("site_name",'login_register.php');
define ("cookie_timeout",3*24*60*60);
error_reporting(E_ALL ^ E_NOTICE);

define('INCLUDE_CHECK',true);

require '../connect.php';
require '../functions.php';
// Those two files can be included only if INCLUDE_CHECK is defined


session_name('aTTS');
// Starting the session

//session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

session_start();
/*$_SESSION['msg'][] = $_COOKIE['ATTSRemember'];
$_SESSION['msg'][] = $_SESSION['rememberMe'];
$_SESSION['msg'][] = $_SESSION['id'];*/


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
	//delete the cookie
	if(isset($_COOKIE['ATTSRemember'])){
		//delete the cookie
		setcookie('ATTSRemember', "", time()-3600);
	}
	header("Location: login_register.php");
	exit;
}
//if the user already logged in => "home page"
if($_SESSION['id']){
			header("Location:index.php");
			exit;
} else if(isset($_COOKIE['ATTSRemember'])){
	$sSql = "SELECT id,usr FROM members WHERE id='{$_COOKIE['ATTSRemember']}'";
	$row = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sSql));
	if($row) {
			$_SESSION['id'] = $row['id'];
			$_SESSION['usr'] = $row['usr'];
			header("Location:index.php");
			exit;
	}
}
		
if($_POST['submit']=='Login')
{
	$err = array();
	// Will hold our errors
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		$_POST['username'] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['username']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$_POST['password'] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['password']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data

		$row = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id,usr FROM members WHERE usr='{$_POST['username']}' AND pass='".sha1($_POST['password'])."'"));
		if($row['usr'])
		{
			// If everything is OK login
			
			$_SESSION['usr']=$row['usr'];
			$_SESSION['id'] = $row['id'];
			//$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			if($_POST['rememberMe']=='1') {
				setcookie('ATTSRemember', $_SESSION['id'], time() + cookie_timeout);
			} else if(isset($_COOKIE['ATTSRemember'])){
				//delete the cookie
				setcookie('ATTSRemember',"",time()-3600);
			}
			$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$aSql .= "'Login', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
		}
		else $err[]='Wrong username and/or password!';
	}
	
	if($err) {
		$_SESSION['msg']['login-err'] = implode('<br />',$err);
		// Save the error messages in the session
			$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
			if(!$_SESSION['usr']) {
						$aSql .= "-1,";
			} else {
				$aSql .= "{$_SESSION['id']},";
			}
			$aSql .= "'Login', 'Failed', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
			mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
		header("Location: login_register.php");
		exit;
	}

	header("Location: index.php");
	exit;
} else if($_POST['submit']=='Register')
{
	// If the Register form has been submitted
	
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>20)
	{
		$err[]='Your username must be between 4 and 20 characters!';
	}
	
	/*if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Your username contains invalid characters!';
	}*/
	$emailexist = TRUE;
	if(strcasecmp($_POST['domainSel'], "No Email")==0 ) {
		//$email = $_POST['username'].$_POST['domainSel'];
		$email = "";
		$emailexist = FALSE;
	} else {
		$email = $_POST['username'].$_POST['domainSel'];
		if(!checkEmail($email))
		{
			$err[]='Your email is not valid!'.$email;
		}
	}
	if(!count($err))
	{
		// If there are no errors
		$pass = substr(sha1($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
		// Generate a random password		
		$_POST['username'] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['username']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$_POST['deptSel'] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['deptSel']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$_POST['regionSel'] = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['regionSel']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

		$sSql="SELECT * FROM departments WHERE id={$_POST['deptSel']}";
		$deptrow=mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sSql));
		$sSql="SELECT * FROM regions WHERE id={$_POST['regionSel']}";
		$rgnrow=mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sSql));
		
		$aCode = substr(md5(microtime().rand(1,100000)),0,6);
		if($emailexist) {
			$sql = "INSERT INTO members(usr,pass,password,email,department,region,regIP,ActCode)";
			$sql .= "VALUES('{$_POST['username']}', '".sha1($pass)."', '{$pass}', '{$_POST['username']}{$_POST['domainSel']}', {$_POST['deptSel']}, ";
			$sql .= "{$_POST['regionSel']}, '{$_SERVER['REMOTE_ADDR']}', '{$aCode}')";
		} else {
			$sql = "INSERT INTO members(usr,pass,password,department,region,regIP,ActCode)";
			$sql .= "VALUES('{$_POST['username']}', '".sha1('123456')."', '123456', {$_POST['deptSel']}, ";
			$sql .= "{$_POST['regionSel']}, '{$_SERVER['REMOTE_ADDR']}', '{$aCode}')";
		}
		mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if(mysqli_affected_rows($link)==1)
		{
			if($emailexist) {
				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
 				$headers[] = "Content-type: text/html; charset=utf-8";
 				$headers[] = "From: noreply";
 				$headers[] = "CC: mmm20122002-a@yahoo.com";
 				$headers = implode("\r\n", $headers);
      	
				$temp= "Dear ".ucfirst($_POST['username']).",<br />";
				$temp .="Thanks for joining Internet Test. To validate your account, please click on the following link:";
				$temp .= "<a href='http://10.5.13.46/ATS/en/Activate.php?act={$aCode}&usrid=".((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res)."'>Validate</a>";
				$temp .= "<br />Otherwise please <a href='http://10.5.13.46/ATS/en/Activate.php?act={$aCode}&usrid=".((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res)."&do=cancel'>Cancel</a> this application";
				$temp .= "<br />Account Name: {$_POST['username']}";
				$temp .= "<br />Department: {$deptrow['business unit']}/{$deptrow['section']}";
				$temp .= "<br />Region: {$rgnrow['region']}";
				$temp .= "<br /><br /><br />Best Regards, <br />Site Administration";
				
				
					$temp = send_mail(	'noreply.atts@a.com',
							$_POST['username'].$_POST['domainSel'],
							'Registration',
							$temp, $headers);
				if($temp) {
					$_SESSION['msg']['reg-success']='We sent you an email to validate your account!';
					$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
					if(!$_SESSION['usr']) {
								$aSql .= "-1,";
					} else {
						$aSql .= "{$_SESSION['id']},";
					}
					$aSql .= "'register', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
					mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
				} else {
					$_SESSION['msg']['reg-success']='Email couldnt be sent';
					$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
					if(!$_SESSION['usr']) {
								$aSql .= "-1,";
					} else {
						$aSql .= "{$_SESSION['id']},";
					}
					$aSql .= "'register', 'Success', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
					mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
				}  
			} else {
				//user has no email:
				header("Location: Activate.php?act={$aCode}&usrid=".((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res));
				exit;
			}
		}
		else $err[]='The username is already used!';
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
		$aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
		if(!$_SESSION['usr']) {
					$aSql .= "-1,";
		} else {
			$aSql .= "{$_SESSION['id']},";
		}
		$aSql .= "'register', 'Failed', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
		mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
	}	
	
	header("Location: login_register.php");
	exit;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Internet Test: Login/register</title>

<link rel="stylesheet" type="text/css" href="MyWeb.css" media="screen" />

</head>

<body>
<?php
$aSql = "INSERT INTO log(user, details, ip,url,sitename) VALUES(";
if(!$_SESSION['usr']) {
			$aSql .= "-1,";
} else {
	$aSql .= "{$_SESSION['id']},";
}
$aSql .= "'Site Access', '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
//$_SESSION['msg'][]=$sSql;
mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
?>
	
<!-- Panel -->
<div id="toppanel">
	<ul id="menu">
				<li><a href='https://play.google.com/store/apps/details?id=com.Mohammad.ac.SpeedTest&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png' height="42"/></a></li>

		
	</ul>
	<div style ="text-align: left"> 
		Hello <?php 
		if(!$_SESSION['usr']):
			echo 'Guest';
		else:
		?>
			<!-- 
			<a href="profile.php"><?php echo($_SESSION['usr'])?></a> | <a href="?logoff">Log off</a>
			--?
		<?php
			endif;
		?> 
	</div>
	<hr />
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
		
	<div id="panel" style="left: 0px; top: 0px">
		<div class="content clearfix">
			<div class="left">
				<h1>Internet Test</h1>
				<h2>Welcome to 3G Test</h2>		
				<p class="grey">You can show your handset history from any browser using "ajerlitaxi.com/3gtests/en/index.php?dev=xxxxxxx". where xxxx is your device ID. This is shown in the address when you press "Show History" from your handset</p>

			</div>

			<div class="left right">
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    
                    <?php
						
						if($_SESSION['msg']['login-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" maxlength="32" />
					<!--select name="Select1">
						<option>@gmail.com</option>
						<option>@yahoo.com</option>
						<option>No Email</option>
					</select-->
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" maxlength="40" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
        			<div align="left">
        			<!--
        			<a href="forgot_pass.php">Forgot password</a> 
        			-->
						</div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
			<div class="left right">			
				<!-- Register Form -->
				<!-- 
				<form action="" method="post">
					<h1>New Member</h1>		
                    
                    <?php
						
						if($_SESSION['msg']['reg-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if($_SESSION['msg']['reg-success'])
						{
							echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>
                    		
					
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="username" id="username" maxlength="256" />
					<select name="domainSel">
						<option>@gmail.com</option>
						<option>@yahoo.com</option>
						<option>No Email</option>
					</select>
					<label class="grey" for="City">Department:</label>
					<select name="deptSel">
					<?php
					$sSql="SELECT * FROM departments";
					$dept_res=mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
					for ($i = 0; $dept_row = mysqli_fetch_assoc($dept_res); $i++)	{
						echo '<option value="'.$dept_row['id'].'">'.$dept_row['business unit'].'/'.$dept_row['section'].'</option>';
					}
					?>
					</select>
					<select name="regionSel">
					<?php
					$sSql="SELECT * FROM regions";
					$dept_res=mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
					for ($i = 0; $dept_row = mysqli_fetch_assoc($dept_res); $i++)	{
						echo '<option value="'.$dept_row['id'].'">'.$dept_row['region'].'</option>';
					}
					?>
					</select>
					<label>An email with instructions will be sent to you to complete the registeration process</label>
					<div></div>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
				-->
			</div>
		</div>
	</div> <!-- /login -->	
	
	

	
</div> <!--panel -->

	
</body>
</html>