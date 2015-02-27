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
* (C) 2015 Travelport, Inc. 
* This code is for illustration purposes only. 
*/
$TARGETBRANCH = 'Please use the TargetBranch which you received in the welcome letter';
$CREDENTIALS = 'Please use the UserName which you received in the welcome letter:Please use the password which you received in the welcome letter'; 
$Provider = '1G';//Please use the Provider which you used to create the PNR in Smartpoint, Please use the same PCC as your targetBranch PCC
$PNR = '5WP7N4';//Please use the PNR which you created in Smartpoint, Please create the PNR in smartpoint using the same PCC as your targetBranch PCC
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://www.travelport.com/schema/common_v30_0" xmlns:univ="http://www.travelport.com/schema/universal_v30_0">
   <soapenv:Header/>
   <soapenv:Body>
      <univ:UniversalRecordImportReq AuthorizedBy="user" ProviderCode="$Provider" ProviderLocatorCode="$PNR" TargetBranch="$TARGETBRANCH" TraceId="trace">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
      </univ:UniversalRecordImportReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;


$file = "001-".$Provider."_UniversalRecordImportReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
prettyPrint($message,$file);//call function to pretty print xml


$auth = base64_encode("$CREDENTIALS"); 
$soap_do = curl_init("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/UniversalRecordService");

$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Accept: gzip,deflate", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($message),
); 
//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($soap_do, CURLOPT_POST, true ); 
curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header); 
curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
$return = curl_exec($soap_do);
curl_close($soap_do);

$file = "001-".$Provider."_UniversalRecordImportRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
$content = prettyPrint($return,$file);
parseOutput($content);
//outputWriter($file, $return);
//print_r(curl_getinfo($soap_do));




//Pretty print XML
function prettyPrint($result,$file){
	$dom = new DOMDocument;
	$dom->preserveWhiteSpace = false;
	$dom->loadXML($result);
	$dom->formatOutput = true;		
	//call function to write request/response in file	
	outputWriter($file,$dom->saveXML());	
	return $dom->saveXML();
}

//function to write output in a file
function outputWriter($file,$content){	
	file_put_contents($file, $content); // Write request/response and save them in the File
}


function parseOutput($content){	//parse the Search response to get values to use in detail request
	$UniversalRecordImportRsp = $content; //use this if response is not saved anywhere else use above variable
	//echo $AirAvailabilitySearchRsp;
	$xml = simplexml_load_String("$UniversalRecordImportRsp", null, null, 'SOAP', true);	
	
	if($xml)
		echo "Processing! Please wait!";
	else{
		trigger_error("Encoding Error!", E_USER_ERROR);
	}

	$Results = $xml->children('SOAP',true);
	foreach($Results->children('SOAP',true) as $fault){
		if(strcmp($fault->getName(),'Fault') == 0){
			trigger_error("Error occurred request/response processing!", E_USER_ERROR);
		}
	}
	
	$count = 0;
	$fileName = "UniversalRecord.txt";
	if(file_exists($fileName)){
		file_put_contents($fileName, "");
	}
	foreach($Results->children('universal',true) as $nodes){//this will put the UniversalRecord attributes in the file
		foreach($nodes->children('universal',true) as $hsr){
			if(strcmp($hsr->getName(),'UniversalRecord') == 0){
				foreach($hsr->attributes() as $a => $b){
					$GLOBALS[$a] = "$b";
					file_put_contents($fileName,$a." : ".$b."\r\n", FILE_APPEND);					
				}					
			}
		}
	}
	$fileName = "Reservation.txt";
	if(file_exists($fileName)){
		file_put_contents($fileName, "");
	}
	foreach($Results->children('universal',true) as $nodes){//this will put the air reservation attributes in the file
		foreach($nodes->children('universal',true) as $hsr){
			foreach($hsr->children('air', true) as $reservations){
				if(strcmp($reservations->getName(), 'AirReservation') == 0){
					foreach($reservations->attributes() as $a => $b){
						$GLOBALS[$a] = "$b";
						file_put_contents($fileName,$a." : ".$b."\r\n", FILE_APPEND);
					}
				}
			}
		}
	}
	
	echo "\r\n"."Processing Done. Please check results in files.";

}

?>