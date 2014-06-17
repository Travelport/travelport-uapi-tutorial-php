<!DOCTYPE html>
<html lang="en-us">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="Universal.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="JScript.js"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<title>Hotel Sample Flow PHP</title>
</head>
<body>
<script type="text/javascript" src="treeMenu.js"></script>
<h1>Hotel Booking Error</h1>
<h2>&nbsp;</h2>
        <p>&nbsp;</p>
<h2></h2>
<form name="form1" method="post" action="phpSample_HotelSearch.php">
  <p>
		<h2>Error Occurred in Hotel Process Flow!</h2>
  </p>
  <p>
  </p>
</form>
<h2>&nbsp;</h2>
</body>
</html>
<?php
	if(!isset($_SESSION)){ 
		session_start();
	}//Starting the session to execute all the requests in the same transaction
?>