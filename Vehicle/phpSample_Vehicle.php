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
* (C) 2013 Travelport, Inc. 
* This code is for illustration purposes only. 
* Schema used (UAPI_8.0) 
*/
$TARGETBRANCH = 'Enter the Target Branch that you received in your Welcome letter';
$USERNAME = 'Universal API/Enter the UserName that you received in your Welcome letter';
$PASSWORD = 'Enter the Password that you received in your Welcome letter';
$CREDENTIALS = $USERNAME.":".$PASSWORD;


//
// Sample Vehicle Search request parameters:
//
$LOCATION = 'DEN'; // Vehicle Location
$LOCATIONTYPE = 'Airport';//Vehicle Location Type
$PICKUPDATE = '2014-06-15T09:00:00'; // Checkin Date
$DROPINDATE = '2014-06-30T09:00:00'; // Checkout Date
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	<soapenv:Header/>
	<soapenv:Body>
		<veh:VehicleSearchAvailabilityReq xmlns:com="http://www.travelport.com/schema/common_v29_0" xmlns:veh="http://www.travelport.com/schema/vehicle_v29_0" AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace">
			<com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
			<veh:VehicleDateLocation PickupDateTime="$PICKUPDATE" PickupLocation="$LOCATION" PickupLocationType="$LOCATIONTYPE" ReturnDateTime="$DROPINDATE"/>
		</veh:VehicleSearchAvailabilityReq>
	</soapenv:Body>
</soapenv:Envelope>
EOM;

$auth = base64_encode("$CREDENTIALS"); 

// Initialize the CURL object with the uAPI endpoint URL
$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/VehicleService"); //Endpoint URL

//
// This is the header of the request
//
$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Accept: gzip,deflate", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($message),
); 
//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); // Timeout options
//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); // Verify nothing about peer certificates
curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); // Verify nothing about Host certificates
curl_setopt($soap_do, CURLOPT_POST, true ); 
curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header); 


//
// Run the request
//

$return = curl_exec($soap_do);
print '<br>';
echo $return;
print '<br>';
print_r(curl_getinfo($soap_do));


//	
// Official PHP CURL manual; http://php.net/manual/en/book.curl.php
//
?>