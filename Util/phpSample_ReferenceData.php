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
*/
$TARGETBRANCH = 'Enter the Target Branch that you received in your Welcome letter';
$CREDENTIALS = 'Username provided in the welcome letter:Password provided in the welcome letter'; 
$Provider = '1V';
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:util="http://www.travelport.com/schema/util_v33_0" xmlns:com="http://www.travelport.com/schema/common_v33_0">
   <soapenv:Header/>
   <soapenv:Body>
      <util:ReferenceDataRetrieveReq TraceId="trace" AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TypeCode="Airport">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
         <util:ReferenceDataSearchModifiers MaxResults="12000" StartFromResult="0" ProviderCode="$Provider"/>
      </util:ReferenceDataRetrieveReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;


$file = "001-".$Provider."_ReferenceDataRetrieveReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
prettyPrint($message,$file);//call function to pretty print xml


$auth = base64_encode("$CREDENTIALS"); 
$soap_do = curl_init("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/UtilService");
/*("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");*/
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

$file = "001-".$Provider."_ReferenceDataRetrieveRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
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
	$ReferenceDataRetrieveRsp = $content; //use this if response is not saved anywhere else use above variable
	$xml = simplexml_load_String("$ReferenceDataRetrieveRsp", null, null, 'SOAP', true);	
	
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
	$fileName = "Data.csv";
	if(file_exists($fileName)){
		file_put_contents($fileName, "");
	}
	foreach($Results->children('util',true) as $nodes){
		foreach($nodes->children('util',true) as $hsr){
			if(strcmp($hsr->getName(),'ReferenceDataItem') == 0){				
				$count = $count + 1;
				//file_put_contents($fileName,"",FILE_APPEND);
				foreach($hsr->attributes() as $a => $b){
						$GLOBALS[$a] = "$b";
						//echo "$a"." : "."$b";
						if(strcmp($a, 'Code') == 0){
							file_put_contents($fileName,$b."\t".",", FILE_APPEND);						
						}
						else if(strcmp($a, 'Name') == 0){
							file_put_contents($fileName,$b."\r\n", FILE_APPEND);						
						}
				}									
						
			}
			//break;
		}
	}
	
	echo "\r\n"."Processing Done. Please check results in files.";

}

?>
