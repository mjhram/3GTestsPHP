<?php
if($_POST['submit']=='Update')
{
	header("Location: download.php?file=../files_db/net Test.apk");
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=440" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Internet Test</title>

<link rel="stylesheet" type="text/css" href="MyWeb.css" media="screen" />

</head>

<body>
<div id="toppanel">
<div id="panel">
<div class="content clearfix">
<form action="" method="post">
	There is an update for "3G Tests" app<br>
	<div></div>
	<input type="submit" name="submit" value="Update" class="bt_register" />
</form>
</div>
</div>
</div>
<?php

?>

</body>
</html>