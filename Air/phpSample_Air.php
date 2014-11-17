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
$CREDENTIALS = 'Universal API/API1234567:Password provieded in the welcome leter'; 
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
      <air:AvailabilitySearchReq TraceId="trace" AuthorizedBy="user" TargetBranch="$TARGETBRANCH" xmlns:air="http://www.travelport.com/schema/air_v29_0" xmlns:com="http://www.travelport.com/schema/common_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
         <air:SearchAirLeg>
            <air:SearchOrigin>
               <com:Airport Code="LGW"/>
            </air:SearchOrigin>
            <air:SearchDestination>
               <com:Airport Code="EDI"/>
            </air:SearchDestination>
            <air:SearchDepTime PreferredTime="2014-12-31">
            </air:SearchDepTime>            
         </air:SearchAirLeg>
         <air:AirSearchModifiers>
            <air:PreferredProviders>
               <com:Provider Code="ACH"/>
            </air:PreferredProviders>
         </air:AirSearchModifiers>
      </air:AvailabilitySearchReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;

$auth = base64_encode("$CREDENTIALS"); 
$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
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
$return = curl_exec($soap_do);
print '<br>';
echo $return;
print '<br>';
print_r(curl_getinfo($soap_do));
?>
