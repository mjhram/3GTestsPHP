<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

error_reporting(E_ALL ^ E_NOTICE);
session_name('aTTS');
session_start();
?>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Internet Test</title>
</head>

<body>
<?php
 
if($_SESSION['ar_en']==1) {
	header('location: en/index.php');
} else {
	header('location: en/index.php');
}
?>
</body>

</html>
