<?php
if(!isset($_SESSION)){ 
	session_start();
}

include_once('phpSample_index.php');

//initializeCommons(); //initialize targetBranch/userName etc...
////taken from other php/html forms, after initializing the values keep them in session for later use
$LOCATION = $_SESSION["LOCATION"]; // Hotel Location you want to search
$CHECKINDATE = $_SESSION["CHECKIN"]; // Checkin Date
$CHECKOUTDATE = $_SESSION["CHECKOUT"]; // Checkout Date
$NUMADULTS = $_SESSION["NUMADULTS"];
$TARGETBRANCH = $_SESSION["TARGETBRANCH"];
$PROVIDER  = $_SESSION["PROVIDER"];

if(isset($_POST)){
$HotelChain = $_POST['HotelChain'];
$HotelCode = $_POST['HotelCode'];
$Name = $_POST['Name'];
if(strcmp($PROVIDER, 'TRM') == 0){
	$RateSupplier = $_POST['RateSupplier'];
}

if(strcmp($PROVIDER, 'TRM') != 0){ // TRM needs to add HostToken/other attributes in the request
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
      <hot:HotelDetailsReq TargetBranch="$TARGETBRANCH" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name"/>
         <hot:HotelDetailsModifiers NumberOfAdults="$NUMADULTS" RateRuleDetail="Complete">
            <com:PermittedProviders xmlns:com="http://www.travelport.com/schema/common_v29_0">
               <com:Provider Code="$PROVIDER"/>
            </com:PermittedProviders>
            <hot:HotelStay>
               <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
               <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
            </hot:HotelStay>
         </hot:HotelDetailsModifiers>
      </hot:HotelDetailsReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
else{
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
      <hot:HotelDetailsReq TargetBranch="$TARGETBRANCH" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name"/>
         <hot:HotelDetailsModifiers RateSupplier="$RateSupplier" NumberOfAdults="$NUMADULTS" RateRuleDetail="Complete">
            <com:PermittedProviders xmlns:com="http://www.travelport.com/schema/common_v29_0">
               <com:Provider Code="$PROVIDER"/>
            </com:PermittedProviders>
            <hot:HotelStay>
               <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
               <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
            </hot:HotelStay>
         </hot:HotelDetailsModifiers>
         <com:HostToken Host="$PROVIDER" xmlns:com="http://www.travelport.com/schema/common_v29_0">$Token</com:HostToken>
      </hot:HotelDetailsReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}

$file = "002-".$PROVIDER."_HotelDetailReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
prettyPrint($message,$file);

$result = runRequest($message); // send the request as parameter to the function

$_SESSION["content"] = $result; // if you do not intend to save response in file use this to use the response for further processing

//call function to pretty print xml
$file = "002-".$PROVIDER."_HotelDetailRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
prettyPrint($result,$file);
//	
// Official PHP CURL manual; http://php.net/manual/en/book.curl.php
//
require_once('bookingDetails.php');
}

?>