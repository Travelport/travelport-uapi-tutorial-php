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

if(!isset($_SESSION)){ 
	session_start();
} //Starting the session to execute all the requests in the same transaction

function initializeHeader($message){ //Initialize all the header contents
	$auth = base64_encode($_SESSION["CREDENTIALS"]);
	// Initialize the CURL object with the uAPI endpoint URL
	//$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/HotelService"); // Endpoint URL
	$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/HotelService"); // Endpoint URL
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
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); // Verify nothing about host certificate
	curl_setopt($soap_do, CURLOPT_POST, true ); 
	curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
	curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
	curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);

	return $soap_do;

}

function initializeCommons(){ //Initialize all the common parameters
	//should be taken from other php-html forms, after initializing the valus keep them in session for later use
	if(isset($_POST['submit'])){
		$TARGETBRANCH = $_POST['targetbranch'];
		$_SESSION["TARGETBRANCH"] = $TARGETBRANCH;
		$USERNAME = $_POST['username'];
		$_SESSION["USERNAME"] = $USERNAME;
		$PASSWORD = $_POST['password'];
		$_SESSION["PASSWORD"] = $PASSWORD;
		$CREDENTIALS = $USERNAME.":".$PASSWORD;
		$_SESSION["CREDENTIALS"] = $CREDENTIALS;	
		$PROVIDER = $_POST['provider']; //Give permitted/preferred provider as 1G/1P/TRM
		$_SESSION["PROVIDER"] = $PROVIDER;
		$LOCATION = $_POST['location'];
		$_SESSION["LOCATION"] = $LOCATION;
		$NUMADULTS = $_POST['numadults'];
		$_SESSION["NUMADULTS"] = $NUMADULTS;
		$CHECKIN = $_POST['checkin'];
		$CHECKINDT = date("Y-m-d", strtotime($CHECKIN));
		$_SESSION["CHECKIN"] = $CHECKINDT;
		$CHECKOUT = $_POST['checkout'];
		$CHECKOUTDT = date("Y-m-d", strtotime($CHECKOUT));
		$_SESSION["CHECKOUT"] = $CHECKOUTDT;
	}
}

function runRequest($message){ // Run the request
	initializeCommons(); //initialize common values
	$soap_do = initializeHeader($message); //initialize header
	//
	// Run the request
	//
	$result = curl_exec($soap_do);
	if (curl_errno($soap_do) != '') 
    {
        //print curl_errno($soap_do) . ' - ' . curl_error($soap_do);
		header("Location: http://locationOfErrorpage/projects/Hotel/error.php"); //Use the location where you are keeping the error page
    }
	curl_close($soap_do);
	return $result;
}


//Pretty print XML
function prettyPrint($result,$file){
	$dom = new DOMDocument("1.0");
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;	
	$dom->loadXML(simplexml_load_string($result)->asXML());
	
	//call function to write request/response in file	
	outputWriter($file,$dom->saveXML());
	return $dom->saveXML();
}

//if you want to write the request/response use this function
//if you do not want it the response can be send as $content to the next php from parsing and processing
//function to write output in a file
function outputWriter($file,$content){	
	file_put_contents($file, $content); // Write request/response and save them in the File
}

?>