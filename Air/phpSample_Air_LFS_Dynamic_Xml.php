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
$Provider = '1V';//1G/1V/1P/ACH
$PreferredDepDate = date('Y-m-d', strtotime("75 day"));//This should be taken from User input in HTML/PHP user interface
$PreferredRetDate = date('Y-m-d', strtotime("81 day"));//This should be taken from User input in HTML/PHP user interface

$NumberOfTravelers = 2;


$Origin = "DEN"; //This should be taken from User input in HTML/PHP user interface
$Destination = "ATL"; //This should be taken from User input in HTML/PHP user interface
$Carrier = "UA";//This should be taken from User input in HTML/PHP user interface
$CabinClass = "Economy";//This should be taken from User input in HTML/PHP user interface

//Create SOAP XML
$message = new DOMDocument('1.0', 'UTF-8');
//$xmlRoot = $message->createElement("xml");
//$xmlRoot = $message->appendChild($xmlRoot);

//Create Envelope
$xmlRoot = $message->createElementNS("http://schemas.xmlsoap.org/soap/envelope/","soapenv:Envelope","");

/*$xmlRootAttribute = $message->createAttribute('xmlns:soapenv');
// Value for the created attribute
$xmlRootAttribute->value = 'http://schemas.xmlsoap.org/soap/envelope/';

$xmlRoot->appendChild($xmlRootAttribute);*/
$xmlRoot = $message->appendChild($xmlRoot);

//Create Header
$xmlRootHeader = $message->createElement("soapenv:Header");
$xmlRootHeader = $xmlRoot->appendChild($xmlRootHeader);
//Create Body
$xmlRootBody = $message->createElement("soapenv:Body");
$xmlRootBody = $xmlRoot->appendChild($xmlRootBody);

$lfsRootNode = $message->createElementNS("http://www.travelport.com/schema/air_v42_0","air:LowFareSearchReq","");
$lfsRootNode = $xmlRootBody->appendChild($lfsRootNode);

$lfsRootNodeattribute = $message->createAttribute("TraceId");
$lfsRootNodeattribute->value = "trace";
$lfsRootNode->appendChild($lfsRootNodeattribute);

$lfsRootNodeattribute = $message->createAttribute("AuthorizedBy");
$lfsRootNodeattribute->value = "user";
$lfsRootNode->appendChild($lfsRootNodeattribute);

$lfsRootNodeattribute = $message->createAttribute("TargetBranch");
$lfsRootNodeattribute->value = $TARGETBRANCH;
$lfsRootNode->appendChild($lfsRootNodeattribute);

//Above part of code will be same for almost all the requests in UAPI

//Below part of code might be different based on request structure

$lfsRootNodeattribute = $message->createAttribute("SolutionResult");
$lfsRootNodeattribute->value = "true";//If PricePoint is needed then we need to pass the value as false, bydefault this valus is false, incase of true we will receive PricingSolutions
$lfsRootNode->appendChild($lfsRootNodeattribute);

$billPointOfSaleNode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:BillingPointOfSaleInfo","");
$billPointOfSaleNodeattribute = $message->createAttribute("OriginApplication");
$billPointOfSaleNodeattribute->value = "UAPI";
$billPointOfSaleNode->appendChild($billPointOfSaleNodeattribute);

$billPointOfSaleNode = $lfsRootNode->appendChild($billPointOfSaleNode);

//Outbound Flight Request
$outboundFlightLeg = $message->createElement("air:SearchAirLeg");
$outboundFlightLeg = $lfsRootNode->appendChild($outboundFlightLeg);

$originLeg = $message->createElement("air:SearchOrigin");
$originLeg = $outboundFlightLeg->appendChild($originLeg);
$destinatonLeg = $message->createElement("air:SearchDestination");
$destinatonLeg = $outboundFlightLeg->appendChild($destinatonLeg);
$prefOutDate = $message->createElement("air:SearchDepTime");
$prefOutDateAttribute = $message->createAttribute("PreferredTime");
$prefOutDateAttribute->value = $PreferredDepDate;
$prefOutDate->appendChild($prefOutDateAttribute);
$prefOutDate = $outboundFlightLeg->appendChild($prefOutDate);

//It can be aiportCode, or City Code, or CityOrAirportCode with any one of them as preferrence
$aiportCode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:Airport","");
$aiportCodeattribute = $message->createAttribute("Code");
$aiportCodeattribute->value = $Origin;
$aiportCode->appendChild($aiportCodeattribute);
$aiportCode = $originLeg->appendChild($aiportCode);

$aiportCode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:Airport","");
$aiportCodeattribute = $message->createAttribute("Code");
$aiportCodeattribute->value = $Destination;
$aiportCode->appendChild($aiportCodeattribute);
$aiportCode = $destinatonLeg->appendChild($aiportCode);

//Inbound/Return Flight Request
//we can add this part as a inside a if block if return flight requested by the user
$inboundFlightLeg = $message->createElement("air:SearchAirLeg");
$inboundFlightLeg = $lfsRootNode->appendChild($inboundFlightLeg);

$originLeg = $message->createElement("air:SearchOrigin");
$originLeg = $inboundFlightLeg->appendChild($originLeg);
$destinatonLeg = $message->createElement("air:SearchDestination");
$destinatonLeg = $inboundFlightLeg->appendChild($destinatonLeg);
$prefOutDate = $message->createElement("air:SearchDepTime");
$prefOutDateAttribute = $message->createAttribute("PreferredTime");
$prefOutDateAttribute->value = $PreferredRetDate;
$prefOutDate->appendChild($prefOutDateAttribute);
$prefOutDate = $inboundFlightLeg->appendChild($prefOutDate);

//It can be aiportCode, or City Code, or CityOrAirportCode with any one of them as preferrence
$aiportCode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:Airport","");
$aiportCodeattribute = $message->createAttribute("Code");
$aiportCodeattribute->value = $Destination;
$aiportCode->appendChild($aiportCodeattribute);
$aiportCode = $originLeg->appendChild($aiportCode);

$aiportCode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:Airport","");
$aiportCodeattribute = $message->createAttribute("Code");
$aiportCodeattribute->value = $Origin;
$aiportCode->appendChild($aiportCodeattribute);
$aiportCode = $destinatonLeg->appendChild($aiportCode);


//Below part is modifiers and optional data
$airSearchModifiersNode = $message->createElement("air:AirSearchModifiers");
$airSearchModifiersNode = $lfsRootNode->appendChild($airSearchModifiersNode);

$prefProviderNode = $message->createElement("air:PreferredProviders");
$prefProviderNode = $airSearchModifiersNode->appendChild($prefProviderNode);

$prefCabinNode = $message->createElement("air:PermittedCabins");
$prefCabinNode = $airSearchModifiersNode->appendChild($prefCabinNode);

$prefCarrierNode = $message->createElement("air:PermittedCarriers");
$prefCarrierNode = $airSearchModifiersNode->insertBefore($prefCarrierNode, $prefCabinNode);//this has been added just to show that a particular node can be inserted before a particualr node dynamically


//if there is multiple provider as permitted or preferred we can run this code in a loop to add all the carriers permitted
$perfProviderCodeNode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:Provider","");
$perfProviderCodeNodeattribute = $message->createAttribute("Code");
$perfProviderCodeNodeattribute->value = $Provider;
$perfProviderCodeNode->appendChild($perfProviderCodeNodeattribute);
$perfProviderCodeNode = $prefProviderNode->appendChild($perfProviderCodeNode);

//if there is multiple carrier as permitted or preferred we can run this code in a loop to add all the carriers permitted
$perfCarrierCodeNode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:Carrier","");
$perfCarrierCodeNodeattribute = $message->createAttribute("Code");
$perfCarrierCodeNodeattribute->value = $Carrier;
$perfCarrierCodeNode->appendChild($perfCarrierCodeNodeattribute);
$perfCarrierCodeNode = $prefCarrierNode->appendChild($perfCarrierCodeNode);

$perfCabinCodeNode = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:CabinClass","");
$perfCabinCodeNodeattribute = $message->createAttribute("Type");
$perfCabinCodeNodeattribute->value = $CabinClass;
$perfCabinCodeNode->appendChild($perfCabinCodeNodeattribute);
$perfCabinCodeNode = $prefCabinNode->appendChild($perfCabinCodeNode);



//Below part is to add number of traveler in the request
for($i = 0; $i < $NumberOfTravelers; $i++)
{
    $travelerDetails = $message->createElementNS("http://www.travelport.com/schema/common_v42_0","com:SearchPassenger","");
    $travelerDetailsattribute = $message->createAttribute("BookingTravelerRef");
    $travelerDetailsattribute->value = $i;
    $travelerDetails->appendChild($travelerDetailsattribute);
    $travelerDetailsattribute = $message->createAttribute("Code");
    $travelerDetailsattribute->value = "ADT";
    $travelerDetails->appendChild($travelerDetailsattribute);
    $travelerDetails = $lfsRootNode->appendChild($travelerDetails);
}



$message->preserveWhiteSpace = false;
$message->formatOutput = true;
$message = $message->saveXML();



$file = "001-".$Provider."_LowFareSearchReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
prettyPrint($message,$file);//call function to pretty print xml


$auth = base64_encode("$CREDENTIALS");
$soap_do = curl_init("https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
/*("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");*/
$header = array(
"Content-Type: text/xml;charset=UTF-8",
"Accept-Encoding: gzip,deflate",
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
curl_setopt($soap_do, CURLOPT_ENCODING, '');
$return = curl_exec($soap_do);
curl_close($soap_do);

$file = "001-".$Provider."_LowFareSearchRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
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

function ListAirSegments($key, $lowFare){
	foreach($lowFare->children('air',true) as $airSegmentList){
		if(strcmp($airSegmentList->getName(),'AirSegmentList') == 0){
			foreach($airSegmentList->children('air', true) as $airSegment){
				if(strcmp($airSegment->getName(),'AirSegment') == 0){
					foreach($airSegment->attributes() as $a => $b){
						if(strcmp($a,'Key') == 0){
							if(strcmp($b, $key) == 0){
								return $airSegment;
							}
						}
					}
				}
			}
		}
	}
}

function PriceSegment($segment){

}


function parseOutput($content){	//parse the Search response to get values to use in detail request
	$LowFareSearchRsp = $content; //use this if response is not saved anywhere else use above variable
	//echo $LowFareSearchRsp;
	$xml = simplexml_load_String("$LowFareSearchRsp", null, null, 'SOAP', true);

	if($xml)
		echo "Processing! Please wait!";
	else{
		//trigger_error("Encoding Error!", E_USER_ERROR);
		$error = "Encoding Error! Check SOAP Fault";
		throw new Exception($error);
	}

	$Results = $xml->children('SOAP',true);
	foreach($Results->children('SOAP',true) as $fault){
		if(strcmp($fault->getName(),'Fault') == 0){
			//trigger_error("Error occurred request/response processing!", E_USER_ERROR);
			$error = "Error occurred request/response processing! Check SOAP Fault";
			throw new Exception($error);
		}
	}

	/*$count = $count + 1;
						file_put_contents($fileName,"\r\n"."Air Segment ".$count."\r\n"."\r\n", FILE_APPEND);
						foreach($hp->attributes() as $a => $b	){
								$GLOBALS[$a] = "$b";
								//echo "$a"." : "."$b";
								file_put_contents($fileName,$a." : ".$b."\r\n", FILE_APPEND);
						}*/

	$count = 0;
	$fileName = "flights.txt";
	if(file_exists($fileName)){
		file_put_contents($fileName, "");
	}
	foreach($Results->children('air',true) as $lowFare){
		foreach($lowFare->children('air',true) as $airPriceSol){
			if(strcmp($airPriceSol->getName(),'AirPricingSolution') == 0){
				$count = $count + 1;
				file_put_contents($fileName, "Air Pricing Solutions Details ".$count.":\r\n", FILE_APPEND);
				file_put_contents($fileName,"--------------------------------------\r\n", FILE_APPEND);
				foreach($airPriceSol->children('air',true) as $journey){
					if(strcmp($journey->getName(),'Journey') == 0){
						file_put_contents($fileName,"\r\nJourney Details: ", FILE_APPEND);
						file_put_contents($fileName,"\r\n", FILE_APPEND);
						file_put_contents($fileName,"--------------------------------------\r\n", FILE_APPEND);
						foreach($journey->children('air', true) as $segmentRef){
							if(strcmp($segmentRef->getName(),'AirSegmentRef') == 0){
								foreach($segmentRef->attributes() as $a => $b){
									$segment = ListAirSegments($b, $lowFare);
									foreach($segment->attributes() as $c => $d){
										if(strcmp($c, "Origin") == 0){
											file_put_contents($fileName,"From ".$d."\r\n", FILE_APPEND);
										}
										if(strcmp($c, "Destination") == 0){
											file_put_contents($fileName,"To ".$d."\r\n", FILE_APPEND);
										}
										if(strcmp($c, "Carrier") == 0){
											file_put_contents($fileName,"Airline: ".$d."\r\n", FILE_APPEND);
										}
										if(strcmp($c, "FlightNumber") == 0){
											file_put_contents($fileName,"Flight ".$d."\r\n", FILE_APPEND);
										}
										if(strcmp($c, "DepartureTime") == 0){
											file_put_contents($fileName,"Depart ".$d."\r\n", FILE_APPEND);
										}
										if(strcmp($c, "ArrivalTime") == 0){
											file_put_contents($fileName,"Arrive ".$d."\r\n", FILE_APPEND);
										}
									}

								}
							}
						}

					}
				}
				foreach($airPriceSol->children('air',true) as $priceInfo){
					if(strcmp($priceInfo->getName(),'AirPricingInfo') == 0){
						file_put_contents($fileName,"\r\nPricing Details: ", FILE_APPEND);
						file_put_contents($fileName,"\r\n", FILE_APPEND);
						file_put_contents($fileName,"--------------------------------------\r\n", FILE_APPEND);
						foreach($priceInfo->attributes() as $e => $f){
								if(strcmp($e, "ApproximateBasePrice") == 0){
									file_put_contents($fileName,"Approx. Base Price: ".$f."\r\n", FILE_APPEND);
								}
								if(strcmp($e, "ApproximateTaxes") == 0){
									file_put_contents($fileName,"Approx. Taxes: ".$f."\r\n", FILE_APPEND);
								}
								if(strcmp($e, "ApproximateTotalPrice") == 0){
									file_put_contents($fileName,"Approx. Total Price: ".$f."\r\n", FILE_APPEND);
								}
								if(strcmp($e, "BasePrice") == 0){
									file_put_contents($fileName,"Base Price: ".$f."\r\n", FILE_APPEND);
								}
								if(strcmp($e, "Taxes") == 0){
									file_put_contents($fileName,"Taxes: ".$f."\r\n", FILE_APPEND);
								}
								if(strcmp($e, "TotalPrice") == 0){
									file_put_contents($fileName,"Total Price: ".$f."\r\n", FILE_APPEND);
								}
						}
					}
				}
				file_put_contents($fileName,"\r\n", FILE_APPEND);
				break;
			}

		}
	}

	PriceSegment($segment);

	$Token = 'Token';
	$TokenKey = 'TokenKey';
	$fileName = "tokens.txt";
	if(file_exists($fileName)){
		file_put_contents($fileName, "");
	}
	foreach($Results->children('air',true) as $nodes){
		foreach($nodes->children('air',true) as $hsr){
			if(strcmp($hsr->getName(),'HostTokenList') == 0){
				foreach($hsr->children('common_v29_0', true) as $ht){
					if(strcmp($ht->getName(), 'HostToken') == 0){
						$GLOBALS[$Token] = $ht[0];
						foreach($ht->attributes() as $a => $b){
							if(strcmp($a, 'Key') == 0){
								file_put_contents($fileName,$TokenKey.":".$b."\r\n", FILE_APPEND);
							}
						}
						file_put_contents($fileName,$Token.":".$ht[0]."\r\n", FILE_APPEND);
					}
				}
			}
		}
	}

	echo "\r\n"."Processing Done. Please check results in files.";

}

?>
