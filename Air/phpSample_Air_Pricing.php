<?php

$adult = 1;
$child = 0;
$infants = 0;

$Gallpax= array();

if($adult > 0 && $child> 0 && $infants> 0){
		
	for($i = 1; $i <= $adult ; $i++){
		$adultcount='<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="ADT" />';
		array_push($Gallpax, $adultcount);
	}
	for($i = 1; $i <= $child ; $i++){
		$childcount = '<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="CNN" Age="10" DOB="2012-06-04" />';
		array_push($Gallpax,$childcount);
	}
	for($i = 1; $i <= $infants ; $i++){
		$infantscount = '<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="INF" Age="1" DOB="2021-06-04" />';
		array_push($Gallpax, $infantscount);
	
	}


			
	}else if($adult > 0 && $child > 0){

	for($i = 1; $i <= $adult ; $i++){
		$adultcount='<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="ADT" />';
		array_push($Gallpax, $adultcount);
	}
	for($i = 1; $i <= $child ; $i++){
		$childcount = '<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="CNN" Age="10" DOB="2012-06-04" />';
		array_push($Gallpax,$childcount);
	}
	
	}else if($adult > 0 && $infants > 0){
	
	for($i = 1; $i <= $adult ; $i++){
		$adultcount ='<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="ADT" />';
		array_push($Gallpax, $adultcount);
	}
	for($i = 1; 1 <= $infants ; $i++){
		$infantscount = '<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="INF" Age="1" DOB="2021-06-04" />';
		array_push($Gallpax, $infantscount);
	
	}

	}else{

	for($i = 1; $i <= $adult ; $i++){
		$adultcount='<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="ADT" />';
		array_push($Gallpax, $adultcount);
	}

}		

	$TARGETBRANCH = 'Enter the Target Branch that you received in your Welcome letter';
	$CREDENTIALS = 'Universal API/API1234567:Password provieded in the welcome leter'; 
	$message = <<<EOM
	<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
    <soapenv:Header/>
		<soapenv:Body>
			<AirPriceReq xmlns="http://www.travelport.com/schema/air_v42_0" TraceId="Kayes Fahim" AuthorizedBy="Travelport" TargetBranch="$TARGETBRANCH">
				<BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v42_0" OriginApplication="uAPI" />
				<AirItinerary>
					<AirSegment Key="mDWV6ESqWDKAD0zHHAAAAA==" AvailabilitySource="Q" Equipment="77W" AvailabilityDisplayType="Fare Shop/Optimal Shop" Group="0" Carrier="EK" FlightNumber="583" Origin="DAC" Destination="DXB" DepartureTime="2022-06-29T10:15:00.000+06:00" ArrivalTime="2022-06-29T13:05:00.000+04:00" FlightTime="290" Distance="2207" ProviderCode="1G" />
					
				</AirItinerary>
				<AirPricingModifiers InventoryRequestType="DirectAccess">
					<BrandModifiers ModifierType="FareFamilyDisplay" />
				</AirPricingModifiers>
				<SearchPassenger xmlns="http://www.travelport.com/schema/common_v42_0" Code="ADT" Key="ZUdreFl5WGc0akZmcFZNWQ==" />
				<AirPricingCommand>
					<AirSegmentPricingModifiers AirSegmentRef="mDWV6ESqWDKAD0zHHAAAAA==" FareBasisCode="LSSOPBD1">
					<PermittedBookingCodes>
						<BookingCode Code="L" />
					</PermittedBookingCodes>
					</AirSegmentPricingModifiers>
				</AirPricingCommand>
				<FormOfPayment xmlns="http://www.travelport.com/schema/common_v42_0" Type="Credit" />
			</AirPriceReq>
		</soapenv:Body>
	</soapenv:Envelope> 
EOM;



	$auth = base64_encode("$CREDENTIALS"); 
	$soap_do = curl_init("https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
	$header = array(
	"Content-Type: text/xml;charset=UTF-8", 
	"Accept: gzip,deflate", 
	"Cache-Control: no-cache", 
	"Pragma: no-cache", 
	"SOAPAction: \"\"",
	"Authorization: Basic $auth", 
	"Content-length: ".strlen($message),
	); 


	curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($soap_do, CURLOPT_POST, true ); 
	curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
	curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header); 
	curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
	$return = curl_exec($soap_do);
	curl_close($soap_do);

	//print_r($return);

	//$return = file_get_contents("res.xml") ;
	$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $return);
	$xml = new SimpleXMLElement($response);
	//print_r($xml);
	if(isset($xml->xpath('//airAirPriceRsp')[0])){
		$body = $xml->xpath('//airAirPriceRsp')[0];
		
	$result = json_decode(json_encode((array)$body), TRUE); 

    // All Data Show In Array Formate
	print_r($result);
    
	}


?>