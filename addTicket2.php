<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Test</title>
</head>
<body class="newStyle1">
	
 
	<form action="addOoklaTest.php" method="post" enctype="multipart/form-data">
		<label class="grey" for="Date">Date:</label>
		<input class="field" type="text" name="Date" id="Date"  maxlength="20" value="Date"/>
		
		<label class="grey" for="ConnType">ConnType:</label>
		<input class="field" type="text" name="ConnType" id="ConnType" maxlength="15" value="Unknown"/>
		
		<label class="grey" for="Lat">Lat:</label>
		<input class="field" type="text" name="Lat" id="Lat" maxlength="10" value="imei"/>
		
		<label class="grey" for="Lon">Lon:</label>
		<input class="field" type="text" name="Lon" id="Lon" maxlength="10" value=""/>
		
		<label class="grey" for="Download">Download:</label>
		<input class="field" type="text" name="Download" id="Download" maxlength="10" value=""/>
		
		<label class="grey" for="Upload">Upload:</label>
		<input class="field" type="text" name="Upload" id="Upload" maxlength="10" value=""/>
		
		<label class="grey" for="Latency">Latency:</label>
		<input class="field" type="text" name="Latency" id="Latency" maxlength="7" value=""/>
		
		<input type="submit" name="submit" value="Send" class="bt_Save" />
		</div>
	</form>
</div>

</body>
</html>