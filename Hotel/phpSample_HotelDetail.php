<html>
<head>
<link href="Universal.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="JScript.js"></script>
</head>
<body>
<h1>Hotel Shop &amp; Book</h1>
<h2>&nbsp;</h2>
        <p>&nbsp;</p>
<h2>Please select hotel and proceed with booking</h2>
<?php
/* 
* uAPI sample communication in php language 
* 
* This example requires the cURL library to be installed and working. 
* 
* Please note, in the sample code below, the variable $CREDENTIALS is created by binding your username and password together with a colon e.g. 
* 
* $auth = base64_encode("Universal API/API1234567:mypassword"); 
* 
* The variable $TARGETBRANCH should be set to the TargetBranch provided by Travelport. 
* 
* (C) 2014 Travelport, Inc. 
* This code is for illustration purposes only.
* Schema used (UAPI_6.1) 
*/
if(!isset($_SESSION)){ 
	session_start();
}
//Execute the Search request in the flow
include_once('phpSample_index.php');
//
// Sample Hotel Search request parameters:
//
$CHECKINDATE = $_SESSION["CHECKIN"]; // Checkin Date
$CHECKOUTDATE = $_SESSION["CHECKOUT"]; // Checkout Date
$NUMADULTS = $_SESSION["NUMADULTS"];

//Common Parameters
$PROVIDER = $_SESSION["PROVIDER"];
$TARGETBRANCH = $_SESSION["TARGETBRANCH"];

$message = ""; // variable to store the request

parseOutput();//Call the function to parse previous response

function parseOutput(){	//parse the Search response to get values to use in detail request
	//$hotelSearchRsp = file_get_contents('001_HotelAvailabilityRsp.xml');
	$hotelSearchRsp = $_SESSION["content"]; //use this if response is not saved anywhere else use above variable
	$xml = simplexml_load_String("$hotelSearchRsp", null, null, 'SOAP', true);	
	if(!$xml)
	{
		//trigger_error("Encoding Error!", E_USER_ERROR);
		header("Location: http://LocationOfErrorPage/projects/Hotel/error.php"); //Use the Location of the error page
  	}

	$Results = $xml->children('SOAP',true);
	foreach($Results->children('SOAP',true) as $fault){
		if(strcmp($fault->getName(),'Fault') == 0){
			//trigger_error("Error occurred request/response processing!", E_USER_ERROR);
			header("Location: http://LocationOfErrorPage/projects/Hotel/error.php"); //Use the Location of the error page
		}
	}
	global $count;
	$count = 2;
	foreach($Results->children('hotel',true) as $nodes){
		foreach($nodes->children('hotel',true) as $hsr){
			echo "<form name=\"form$count\" action=\"phpSample_HotelSelect.php\" method=\"post\">";	
			echo "<table>";
			if(strcmp($hsr->getName(),'HotelSearchResult') == 0){					
				foreach($hsr->children('hotel',true) as $hp){					
					if(strcmp($hp->getName(),'HotelProperty') == 0){
						foreach($hp->attributes() as $a => $b){
								//$a = "$b";
								if($a != "HotelCode" && $a != "VendorLocationKey" && $a != "NetTransCommissionInd" && $a != "MinAmountRateChanged" && $a != "MaxAmountRateChanged"){
									echo "<tr>";
									echo "<td><h6>$a</h6></td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
									echo "</tr>";
								}
								else{
									echo "<tr class=\"hide\">";
									echo "<td class=\"hide\"><h6>$a</h6></td>"."<td class=\"hide\"><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
									echo "</tr>";
								}
						}												
					}
					else if(strcmp($hp->getName(),'RateInfo') == 0){
						foreach($hp->attributes() as $a => $b){
								//$a = "$b";
								if($a != "HotelCode" && $a != "VendorLocationKey" && $a != "NetTransCommissionInd" && $a != "MinAmountRateChanged" && $a != "MaxAmountRateChanged"){
									echo "<tr>";
									echo "<td><h6>$a</h6></td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
									echo "</tr>";
								}
								else{
									echo "<tr class=\"hide\">";
									echo "<td class=\"hide\"><h6>$a</h6></td>"."<td class=\"hide\"><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
									echo "</tr>";
								}

						}
					}
					
				}								
			}			
			echo "</table>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";						
			echo "<input id=\"submit$count\" class=\"myButton\" name=\"submit$count\" value=\"Book\" type=\"submit\">";
			echo "</form>";
			echo "</br>";
			$count = $count+1;
		}		
	}
	$Token = 'Token';
	foreach($Results->children('hotel',true) as $nodes){
		foreach($nodes->children('common_v26_0',true) as $hsr){
			if(strcmp($hsr->getName(),'HostToken') == 0){
				$GLOBALS[$Token] = $hsr[0];
			}
		}
	}

}
?>
</body>
</html>