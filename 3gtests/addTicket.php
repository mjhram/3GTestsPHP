<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Test</title>
</head>
<body class="newStyle1">
	Hello 
	<form action="addTest.php" method="post" enctype="multipart/form-data">
		<label class="grey" for="imsi">imsi:</label>
		<input class="field" type="text" name="imsi" id="imsi"  maxlength="20" value="imsi"/>
		<label class="grey" for="phoneNumber">phoneNumber:</label>
		<input class="field" type="text" name="phoneNumber" id="phoneNumber" maxlength="20" value="964"/>
		<label class="grey" for="imei">imei:</label>
		<input class="field" type="text" name="imei" id="imei" maxlength="20" value="imei"/>
		<label class="grey" for="netOperator">netOper:</label>
		<input class="field" type="text" name="netOperator" id="netOperator" maxlength="10" value=""/>
		<label class="grey" for="netName">netName:</label>
		<input class="field" type="text" name="netName" id="netName" maxlength="10" value=""/>
		<label class="grey" for="netType">netType(int):</label>
		<input class="field" type="text" name="netType" id="netType" maxlength="5" value=""/>
		<label class="grey" for="netClass">2G/3G:</label>
		<input class="field" type="text" name="netClass" id="netClass" maxlength="2" value=""/>
		<label class="grey" for="phoneType">phoneType(int):</label>
		<input type="submit" name="submit" value="Send" class="bt_Save" />
		</div>
	</form>
</div>

</body>
</html>