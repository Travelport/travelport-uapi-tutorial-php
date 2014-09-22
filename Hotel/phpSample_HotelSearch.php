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
* Schema used (UAPI_8.0) 
*/
//
if(!isset($_SESSION)){ 
	session_start();
}
include_once('phpSample_index.php');
// Sample Hotel Search request parameters:
//common parameters
initializeCommons(); //initialize targetBranch/userName etc...
////As of now these are hard coded, should be taken from other php/html forms, after initializing the values keep them in session for later use
$LOCATION = $_SESSION["LOCATION"]; // Hotel Location you want to search
$CHECKINDATE = $_SESSION["CHECKIN"]; // Checkin Date
$CHECKOUTDATE = $_SESSION["CHECKOUT"]; // Checkout Date
$NUMADULTS = $_SESSION["NUMADULTS"];
$TARGETBRANCH = $_SESSION["TARGETBRANCH"];
$PROVIDER  = $_SESSION["PROVIDER"];

//
// This is the SOAP request
//
// IMPORTANT: The SOAP envelope variables are case sensitive, be consistent!
//

$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	<soapenv:Header/>
	<soapenv:Body>
		<hot:HotelSearchAvailabilityReq xmlns:com="http://www.travelport.com/schema/common_v29_0" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0" AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace">
			<com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
			<hot:HotelSearchLocation>
				<hot:HotelLocation Location="$LOCATION"/>
			</hot:HotelSearchLocation>
			<hot:HotelSearchModifiers NumberOfAdults="$NUMADULTS">
				<com:PermittedProviders>
					<com:Provider Code="$PROVIDER"/>
				</com:PermittedProviders>
			</hot:HotelSearchModifiers>
			<hot:HotelStay>
				<hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
				<hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
			</hot:HotelStay>
		</hot:HotelSearchAvailabilityReq>
	</soapenv:Body>
</soapenv:Envelope>
EOM;

$file = "001-".$PROVIDER."_HotelAvailabilityReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
prettyPrint($message,$file);//call function to pretty print xml

//Call function to run the request
$result = runRequest($message); // send the request as parameter to the function

$_SESSION["content"] = $result; // if you do not intend to save response in file use this to use the response for further processing
//call function to pretty print xml
$file = "001-".$PROVIDER."_HotelAvailabilityRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
prettyPrint($result,$file);

require_once('phpSample_HotelDetail.php');
//	
// Official PHP CURL manual; http://php.net/manual/en/book.curl.php
//
?>