<?php
require '../functions.php';

error_reporting(E_ALL ^ E_NOTICE);
// Starting the session
session_name('aTTS');
session_start();

if($_POST['submit']=='Request')
{
	$user_email=$_POST['username'].$_POST['domainSel'];
	if(!checkEmail($user_email))
	{
		$_SESSION['msg'][] = "Your email is not valid!";
		header("Location: forgot_pass.php");
		exit;
	}
	
	require '../connect.php';
	$user_email=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $user_email) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$pass = substr(sha1($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
	$sSql = "SELECT id,usr,email,activated, approved FROM members WHERE email='{$user_email}'";
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
	if($res) {
		$row = mysqli_fetch_assoc($res);
		if(strcasecmp($row['email'],$user_email) != 0) {
				$_SESSION['msg'][] = "This email is not registered."; 
				header("Location: forgot_pass.php");
				exit;
		} elseif ($row['activated']=="0") {
				$_SESSION['msg'][] = "This account is not Validated yet. [You should receive an email upon registeration and use the link inside that email to validate your email].";
				header("Location: forgot_pass.php");
				exit;
		}elseif ($row['approved']=="0") {
				$_SESSION['msg'][] = "This account is not approved from department head yet.";
				header("Location: forgot_pass.php");
				exit;
		}
		{
  		mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE members SET pass='".sha1($pass)."', password='".$pass."' WHERE email='{$user_email}'");
  		if(mysqli_affected_rows($link)==1) {
					$headers   = array();
 					$headers[] = "MIME-Version: 1.0";
 					$headers[] = "Content-type: text/html; charset=utf-8";
 					$headers[] = "From: noreply.atts@a.com";
 					$headers[] = "CC: mmm20122002-a@yahoo.com";
 					$headers = implode("\r\n", $headers);
  		
  		
  			$temp = "Dear Mr. ";
  			$temp .= ucfirst($row['usr']);
  			$temp .= ", <br />Your password has been changed upon your request. The new password is: {$pass}";
  			$temp .= "<br \><br \><br \>".'Best Regards'."<br \>".'Site Adminsteration';
				$temp = send_mail('noreply.atts@a.com',
								$user_email,
								'System-Change Password',
								$temp, $headers);
				$_SESSION['msg'][] = "Password has been changed successfully.";
				if($temp) {
						$_SESSION['msg'][] = 'An email has been sent with the new password';
				} else {
						$_SESSION['msg'][] = 'Failed to send the password';
				}
				header("Location: forgot_pass.php");
				exit;
			} else {
				$_SESSION['msg'][] = 'Problem changing the password. Please contact Site Administration';
				header("Location: forgot_pass.php");
				exit;
			}
		}
	} else {
		   $_SESSION['msg'][] = 'Couldnot find this account'; 
		   header("Location: forgot_pass.php");
		   exit;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 3G Test: Forgot Password</title>

<style type="text/css">
.newStyle3 {
	background-color: #DA0C35;
	margin-top: 4px;
	margin-right: 5px;
}
input.field {
	border: 1px black solid;
	background: #eeeeee;
	margin-right: 5px;
	margin-top: 4px;
	width: 200px;
	color: black;
	height: 16px;
}
input:focus.field {
	background: #999999;
}

input.bt_chPass {
	border-style: none;
	width: 94px;
	height:24px;
	color: white;
	background: transparent url(../images/bt_register.png) no-repeat 0 0;
	display: block;
}

a {
	color: white;
}
</style>

</head>

<body class="newStyle3">
	<ul id="menu">
			<li style="width: 82px; height: 37px; display: block; float: right; background-color: inherit;"><a href="index.php">Home</a></li>

		</ul>

<img src="..//images//logo.png" alt="Logo" />
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
	<div></div>
	<div>
				<h1>Change Password</h1>
				<form action="" method="post">
					The password will be sent to your email
					<div></div>
 					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="email" size="23" value=""/>
					<select name="domainSel">
						<option>@gmail.com</option>
					</select>
					<div></div>
					<input type="submit" name="submit" value="Request" class="bt_chPass" />
					
				</form>
</div>
	
</body>
</html>