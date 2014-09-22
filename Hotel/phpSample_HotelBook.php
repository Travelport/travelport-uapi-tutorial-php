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
}
//Execute the Detail request in the flow
include_once('phpSample_index.php');

//Common Parameters
$TARGETBRANCH = $_SESSION["TARGETBRANCH"];
$PROVIDER  = $_SESSION["PROVIDER"];
//
// Sample Hotel Search request parameters:
//
$CHECKINDATE = $_SESSION["CHECKIN"]; // Checkin Date
$CHECKOUTDATE = $_SESSION["CHECKOUT"]; // Checkout Date

////As of now these are hard coded, should be taken from other php/html forms, after initializing the values keep them in session for later use
//Traveler info
if(isset($_POST)){
$PREF1 =  $_POST['prefix1'];
$FIRST1 = $_POST['firstname1'];
$LAST1 = $_POST['lastname1'];
$GEN1 = $_POST['gen1'];
$DOB1 = date("Y-m-d", strtotime($_POST['dob1']));
$AGE1 = floor( (strtotime(date('Y-m-d')) - strtotime($DOB1)) / (60*60*24*365));
if($AGE1 > 18){
	$TRAVELER1 = 'ADT';
}
else{
	$TRAVELER1 = 'CHD';
}
$COUNTRY1 = $_POST['con1'];
$PHONE1 = $_POST['phone1'];
$PHONETYPE1 = 'Home';

if($_SESSION["NUMADULTS"] == '2'){
$PREF2 = $_POST['prefix2'];
$FIRST2 = $_POST['firstname2'];
$LAST2 = $_POST['lastname2'];
$GEN2 = $_POST['gen2'];
$DOB2 = date("Y-m-d", strtotime($_POST['dob2']));
$AGE2 = floor( (strtotime(date('Y-m-d')) - strtotime($DOB2)) / (60*60*24*365));
$COUNTRY2 = $_POST['con2'];
if($AGE2 > 18){
	$TRAVELER2 = 'ADT';
}
else{
	$TRAVELER2 = 'CHD';
}
$PHONE1 = $_POST['phone1'];
$PHONETYPE2 = 'Home';
}


$AREACODE = $_POST['area1'];
$COUNTRYCODE = $_POST['ccode1'];
$LOC = 'DEN';
$EMAIL = $_POST['email'];
$EMAILTYPE = 'Home';
$ADDRESSNAME = 'Home';
$STREET = $_POST['add'];
$CITY = $_POST['city'];
$STATE = $_POST['state'];
$ZIP = $_POST['zip'];
$COUNTRY = $_POST['con1'];

////As of now these are hard coded, should be taken from other php/html forms, after initializing the values keep them in session for later use
//Credit Card info

$CCBCOUNTRY = $_POST['cccon'];
$CCBANK = $_POST['ccbank'];
$CCNUM = $_POST['ccnum'];
$CCEXP = date("Y-m", strtotime($_POST['exp']));
$CCTYPE = $_POST['cctype'];
$CCNAME =$_POST['ccname'];//Credit Card Holder Name
$CVV = $_POST['cvv'];

parseDetailOutput();//Call function to parse the previous response

//
// This is the SOAP request
//
// IMPORTANT: The SOAP envelope variables are case sensitive, be consistent!
//
if(strcmp($PROVIDER, '1G') == 0){ // TRM needs to add HostToken/other attributes in the request
if($_SESSION["NUMADULTS"] == '2'){
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <univ:HotelCreateReservationReq AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace" UserAcceptance="true" ProviderCode="$PROVIDER" xmlns:univ="http://www.travelport.com/schema/universal_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <com:BookingTraveler Age="$AGE1" DOB="$DOB1" Gender="$GEN1" Key="/qloV1aUQNuQVeqc9YMwBg==" Nationality="$COUNTRY1" TravelerType="$TRAVELER1" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST1" Last="$LAST1" Prefix="$PREF1"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE1" Type="$PHONETYPE1"/>
            <com:Email EmailID="test@travelport.com" Type="$EMAILTYPE"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <com:BookingTraveler Age="$AGE2" DOB="$DOB2" Gender="$GEN2" Key="TpWYkY2oSU66ZofoGRgbHg==" Nationality="$COUNTRY2" TravelerType="$TRAVELER2" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST2" Last="$LAST2" Prefix="$PREF2"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE2" Type="$PHONETYPE2"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <hot:HotelRateDetail RatePlanType="$RatePlanType" Total="$Total" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hotel:PropertyAddress xmlns:hotel="http://www.travelport.com/schema/hotel_v29_0">
               <hotel:Address>$Address1</hotel:Address>
            </hotel:PropertyAddress>
         </hot:HotelProperty>
         <hot:HotelStay xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
            <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
         </hot:HotelStay>
         <com:Guarantee Type="Guarantee" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:CreditCard BankCountryCode="$CCBCOUNTRY" BankName="$CCBANK" ExpDate="$CCEXP" Number="$CCNUM" Type="$CCTYPE"/>
         </com:Guarantee>
         <hot:GuestInformation NumberOfRooms="1" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:NumberOfAdults>2</hot:NumberOfAdults>
         </hot:GuestInformation>
      </univ:HotelCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
else{
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <univ:HotelCreateReservationReq AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace" UserAcceptance="true" ProviderCode="$PROVIDER" xmlns:univ="http://www.travelport.com/schema/universal_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <com:BookingTraveler Age="$AGE1" DOB="$DOB1" Gender="$GEN1" Key="/qloV1aUQNuQVeqc9YMwBg==" Nationality="$COUNTRY1" TravelerType="$TRAVELER1" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST1" Last="$LAST1" Prefix="$PREF1"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE1" Type="$PHONETYPE1"/>
            <com:Email EmailID="test@travelport.com" Type="$EMAILTYPE"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <hot:HotelRateDetail RatePlanType="$RatePlanType" Total="$Total" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hotel:PropertyAddress xmlns:hotel="http://www.travelport.com/schema/hotel_v29_0">
               <hotel:Address>$Address1</hotel:Address>
            </hotel:PropertyAddress>
         </hot:HotelProperty>
         <hot:HotelStay xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
            <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
         </hot:HotelStay>
         <com:Guarantee Type="Guarantee" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:CreditCard BankCountryCode="$CCBCOUNTRY" BankName="$CCBANK" ExpDate="$CCEXP" Number="$CCNUM" Type="$CCTYPE"/>
         </com:Guarantee>
         <hot:GuestInformation NumberOfRooms="1" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:NumberOfAdults>2</hot:NumberOfAdults>
         </hot:GuestInformation>
      </univ:HotelCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
}
else if(strcmp($PROVIDER, 'TRM') == 0){
if($_SESSION["NUMADULTS"] == '2'){
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <univ:HotelCreateReservationReq AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace" UserAcceptance="true" ProviderCode="$PROVIDER" xmlns:univ="http://www.travelport.com/schema/universal_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <com:BookingTraveler Age="$AGE1" DOB="$DOB1" Gender="$GEN1" Key="/qloV1aUQNuQVeqc9YMwBg==" Nationality="$COUNTRY1" TravelerType="$TRAVELER1" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST1" Last="$LAST1" Prefix="$PREF1"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE1" Type="$PHONETYPE1"/>
            <com:Email EmailID="test@travelport.com" Type="$EMAILTYPE"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <com:BookingTraveler Age="$AGE2" DOB="$DOB2" Gender="$GEN2" Key="TpWYkY2oSU66ZofoGRgbHg==" Nationality="$COUNTRY2" TravelerType="$TRAVELER2" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST2" Last="$LAST2" Prefix="$PREF2"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE2" Type="$PHONETYPE2"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <hot:HotelRateDetail RatePlanType="$RatePlanType" Surcharge="$Surcharge" ApproximateBase="$ApproximateBase" ApproximateTax="$ApproximateTax" RateSupplier="$RateSupplier" RateOfferId="$RateOfferId" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hotel:PropertyAddress xmlns:hotel="http://www.travelport.com/schema/hotel_v29_0">
               <hotel:Address>$Address1</hotel:Address>
            </hotel:PropertyAddress>
         </hot:HotelProperty>
         <hot:HotelStay xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
            <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
         </hot:HotelStay>
         <com:Guarantee Type="Guarantee" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:CreditCard BankCountryCode="$CCBCOUNTRY" BankName="$CCBANK" CVV="$CVV" ExpDate="$CCEXP" Name="$CCNAME" Number="$CCNUM" Type="$CCTYPE"/>
         </com:Guarantee>
         <hot:GuestInformation NumberOfRooms="1" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:NumberOfAdults>2</hot:NumberOfAdults>
         </hot:GuestInformation>
         <com:HostToken Host="$PROVIDER" xmlns:com="http://www.travelport.com/schema/common_v29_0">$Token</com:HostToken>
      </univ:HotelCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
else{
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <univ:HotelCreateReservationReq AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace" UserAcceptance="true" ProviderCode="$PROVIDER" xmlns:univ="http://www.travelport.com/schema/universal_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <com:BookingTraveler Age="$AGE1" DOB="$DOB1" Gender="$GEN1" Key="/qloV1aUQNuQVeqc9YMwBg==" Nationality="$COUNTRY1" TravelerType="$TRAVELER1" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST1" Last="$LAST1" Prefix="$PREF1"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE1" Type="$PHONETYPE1"/>
            <com:Email EmailID="test@travelport.com" Type="$EMAILTYPE"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <hot:HotelRateDetail RatePlanType="$RatePlanType" Surcharge="$Surcharge" ApproximateBase="$ApproximateBase" ApproximateTax="$ApproximateTax" RateSupplier="$RateSupplier" RateOfferId="$RateOfferId" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hotel:PropertyAddress xmlns:hotel="http://www.travelport.com/schema/hotel_v29_0">
               <hotel:Address>$Address1</hotel:Address>
            </hotel:PropertyAddress>
         </hot:HotelProperty>
         <hot:HotelStay xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
            <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
         </hot:HotelStay>
         <com:Guarantee Type="Guarantee" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:CreditCard BankCountryCode="$CCBCOUNTRY" BankName="$CCBANK" CVV="$CVV" ExpDate="$CCEXP" Name="$CCNAME" Number="$CCNUM" Type="$CCTYPE"/>
         </com:Guarantee>
         <hot:GuestInformation NumberOfRooms="1" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:NumberOfAdults>2</hot:NumberOfAdults>
         </hot:GuestInformation>
         <com:HostToken Host="$PROVIDER" xmlns:com="http://www.travelport.com/schema/common_v29_0">$Token</com:HostToken>
      </univ:HotelCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
}
else{
if($_SESSION["NUMADULTS"] == '2'){
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <univ:HotelCreateReservationReq AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace" UserAcceptance="true" ProviderCode="$PROVIDER" xmlns:univ="http://www.travelport.com/schema/universal_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <com:BookingTraveler Age="$AGE1" DOB="$DOB1" Gender="$GEN1" Key="/qloV1aUQNuQVeqc9YMwBg==" Nationality="$COUNTRY1" TravelerType="$TRAVELER1" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST1" Last="$LAST1" Prefix="$PREF1"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE1" Type="$PHONETYPE1"/>
            <com:Email EmailID="test@travelport.com" Type="$EMAILTYPE"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <com:BookingTraveler Age="$AGE2" DOB="$DOB2" Gender="$GEN2" Key="TpWYkY2oSU66ZofoGRgbHg==" Nationality="$COUNTRY2" TravelerType="$TRAVELER2" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST2" Last="$LAST2" Prefix="$PREF2"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE2" Type="$PHONETYPE2"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <hot:HotelRateDetail RatePlanType="$RatePlanType" RateGuaranteed="$RateGuaranteed" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hotel:PropertyAddress xmlns:hotel="http://www.travelport.com/schema/hotel_v29_0">
               <hotel:Address>$Address1</hotel:Address>
            </hotel:PropertyAddress>
         </hot:HotelProperty>
         <hot:HotelStay xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
            <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
         </hot:HotelStay>
         <com:Guarantee Type="Guarantee" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:CreditCard BankCountryCode="$CCBCOUNTRY" BankName="$CCBANK" ExpDate="$CCEXP" Number="$CCNUM" Type="$CCTYPE"/>
         </com:Guarantee>
         <hot:GuestInformation NumberOfRooms="1" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:NumberOfAdults>2</hot:NumberOfAdults>
         </hot:GuestInformation>
      </univ:HotelCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
else{
$message = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <univ:HotelCreateReservationReq AuthorizedBy="user" TargetBranch="$TARGETBRANCH" TraceId="trace" UserAcceptance="true" ProviderCode="$PROVIDER" xmlns:univ="http://www.travelport.com/schema/universal_v29_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v29_0"/>
         <com:BookingTraveler Age="$AGE1" DOB="$DOB1" Gender="$GEN1" Key="/qloV1aUQNuQVeqc9YMwBg==" Nationality="$COUNTRY1" TravelerType="$TRAVELER1" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:BookingTravelerName First="$FIRST1" Last="$LAST1" Prefix="$PREF1"/>
            <com:PhoneNumber AreaCode="$AREACODE" CountryCode="$COUNTRYCODE" Location="$LOC" Number="$PHONE1" Type="$PHONETYPE1"/>
            <com:Email EmailID="test@travelport.com" Type="$EMAILTYPE"/>
            <com:Address>
               <com:AddressName>$ADDRESSNAME</com:AddressName>
               <com:Street>$STREET</com:Street>
               <com:City>$CITY</com:City>
               <com:State>$STATE</com:State>
               <com:PostalCode>$ZIP</com:PostalCode>
               <com:Country>$COUNTRY</com:Country>
            </com:Address>
         </com:BookingTraveler>
         <hot:HotelRateDetail RatePlanType="$RatePlanType" RateGuaranteed="$RateGuaranteed" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0"/>
         <hot:HotelProperty HotelChain="$HotelChain" HotelCode="$HotelCode" Name="$Name" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hotel:PropertyAddress xmlns:hotel="http://www.travelport.com/schema/hotel_v29_0">
               <hotel:Address>$Address1</hotel:Address>
            </hotel:PropertyAddress>
         </hot:HotelProperty>
         <hot:HotelStay xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:CheckinDate>$CHECKINDATE</hot:CheckinDate>
            <hot:CheckoutDate>$CHECKOUTDATE</hot:CheckoutDate>
         </hot:HotelStay>
         <com:Guarantee Type="Guarantee" xmlns:com="http://www.travelport.com/schema/common_v29_0">
            <com:CreditCard BankCountryCode="$CCBCOUNTRY" BankName="$CCBANK" ExpDate="$CCEXP" Number="$CCNUM" Type="$CCTYPE"/>
         </com:Guarantee>
         <hot:GuestInformation NumberOfRooms="1" xmlns:hot="http://www.travelport.com/schema/hotel_v29_0">
            <hot:NumberOfAdults>2</hot:NumberOfAdults>
         </hot:GuestInformation>
      </univ:HotelCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
EOM;
}
}

$file = "003-".$PROVIDER."_HotelBookReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
prettyPrint($message,$file); //Calling the Pretty Print function

//
// Run the request
//

$result = runRequest($message); // send the request as parameter to the function

$_SESSION["content"] = $result; // if you do not intend to save response in file use this to use the response for further processing

//call function to pretty print xml
$file = "003-".$PROVIDER."_HotelBookRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
prettyPrint($result,$file);
require_once('bookingConfirmation.php');
}

//Parse the previous response to get the values to populate Request xml
function parseDetailOutput(){
	//$hotelDetailRsp = file_get_contents('002_HotelDetailRsp.xml'); // Parsing the Hotel Detail Response xml 
	$hotelDetailRsp = $_SESSION["content"]; //use this if response is not saved anywhere else use above variable
	$xml = simplexml_load_String("$hotelDetailRsp", null, null, 'SOAP', true);
	if(!$xml)
	{
		header("Location: http://LocationOfErrorPage/projects/Hotel/error.php"); // Use the location of the error page
	}

	$Results = $xml->children('SOAP',true);
	
	foreach($Results->children('SOAP',true) as $fault){
		if(strcmp($fault->getName(),'Fault') == 0){
			//trigger_error("Error occurred request processing!", E_USER_ERROR);
			header("Location: http://LocationOfErrorPage/projects/Hotel/error.php"); // Use the location of the error page
		}
	}

	foreach($Results->children('hotel',true) as $nodes){
		foreach($nodes->children('hotel',true) as $hsr){
			if(strcmp($hsr->getName(),'RequestedHotelDetails') == 0){
				foreach($hsr->children('hotel',true) as $hp){					
					if(strcmp($hp->getName(),'HotelProperty') == 0){
						foreach($hp->attributes() as $a => $b	){
								$GLOBALS[$a] = "$b";
						}
						$COUNT = 0;
						$Address = 'Address';
						foreach($hp->children('hotel',true) as $pa){							
							if(strcmp($pa->getName(),'PropertyAddress') == 0){								
								foreach($pa->children('hotel',true) as $ha){														
									if(strcmp($ha->getName(),'Address') == 0){
										$GLOBALS[$Address.$COUNT++] = $ha[0];								
									}
								}
							}							
						}
					}
				}
				foreach($hsr->children('hotel',true) as $hp){
					if(strcmp($hp->getName(),'HotelRateDetail') == 0){
						foreach($hp->attributes() as $a => $b	){
								$GLOBALS[$a] = "$b";
						}
						break;
					}
									
				}
			}
			break;
		}
	}
}

//call function to write output in a file

//	
// Official PHP CURL manual; http://php.net/manual/en/book.curl.php
//
?>