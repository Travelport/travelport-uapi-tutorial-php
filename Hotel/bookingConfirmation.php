<!DOCTYPE html>
<html lang="en-us">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="Universal.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="TabStyle.css" rel="stylesheet" type="text/css" />
<link href="GreenHead.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="JScript.js"></script>
<script type="text/javascript" src="tabletab.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script>   
    $(function() {
         $( "#dob1" ).datepicker();   
    }); 
</script>
<script>   
    $(function() {
         $( "#dob2" ).datepicker();   
    }); 
</script>
<script>   
    $(function() {
         $( "#exp" ).datepicker();   
    }); 
</script>
<title>Hotel Booking UAPI Sample App</title>
</head>

<body>
<script type="text/javascript" src="treeMenu.js"></script>
<h1>Hotel Confirmation Details</h1>
<h2>Booking Confirmation</h2>
<p>&nbsp;</p>
<?php	
		if(!isset($_SESSION)){ 
			session_start();
		}//Starting the session to execute all the requests in the same transaction
		
		echo "<h2>Hotel Details</h2>";
		echo "<p>&nbsp;</p>";
		echo "<table>";
		//Parsing data using xpath to get Hotel Confirmation details
		$conf = simplexml_load_string($_SESSION["content"],NULL,NULL,"http://schemas.xmlsoap.org/soap/envelope/");
		$conf->registerXPathNamespace('soap','http://schemas.xmlsoap.org/soap/envelope/');
		$conf->registerXPathNamespace('hotel', 'http://www.travelport.com/schema/hotel_v29_0');
		$conf->registerXPathNamespace('common_v29_0', 'http://www.travelport.com/schema/common_v29_0');
		$conf->registerXPathNamespace('universal', 'http://www.travelport.com/schema/universal_v29_0');
		
		foreach($conf->xpath("//soap:Body") as $body){
			foreach($conf->xpath("//universal:HotelCreateReservationRsp/universal:UniversalRecord/hotel:HotelReservation/hotel:HotelProperty") as $property){
				foreach($property->attributes() as $a=>$b){
					echo "<tr>";
					echo "<td><h6>$a</h6></td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td><h6>Address</h6></td>"."<td>&nbsp;</td>";
				echo "</tr>";
				foreach($property->xpath("//hotel:PropertyAddress/hotel:Address") as $a=>$b){
						echo "<tr>";
						echo "<td>&nbsp;</td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
						echo "</tr>";
				}
				$Type = null;
				$Number = null;
				foreach($conf->xpath("//universal:HotelCreateReservationRsp/universal:UniversalRecord/hotel:HotelReservation/hotel:HotelProperty/common_v29_0:PhoneNumber") as $phone){
					foreach($phone->attributes() as $a=>$b){
						if($a == 'Type'){
						   $Type = $b;
						}
						else if($a == 'Number'){
						   $Number = $b;
						}
						if($Type != null && $Number != null){
							echo "<tr>";
							echo "<td>$Type</td>"."<td><input type=\"hidden\" id=\"$Type\" name=\"$Type\" value=\"$Number\">$Number</td>";
							echo "</tr>";
							$Type = null;
							$Number = null;
						}
					}
				}
			}
			echo "</table>";
			
			echo "<h2>Guest Details</h2>";
			echo "<p>&nbsp;</p>";
			echo "<table>";
			
			foreach($conf->xpath("//universal:HotelCreateReservationRsp/universal:UniversalRecord/common_v29_0:BookingTraveler/common_v29_0:BookingTravelerName") as $traveler){
				foreach($traveler->attributes() as $a=>$b){
					echo "<tr>";
					echo "<td>$a</td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
					echo "</tr>";
				}								
			}
			foreach($conf->xpath("//universal:HotelCreateReservationRsp/universal:UniversalRecord/common_v29_0:BookingTraveler/common_v29_0:Address") as $address){
				foreach($address->children('common_v29_0',true) as $a=>$b){
					if($a != 'ProviderReservationInfoRef'){
						echo "<tr>";
						echo "<td>$a</td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
						echo "</tr>";
					}					
				}
			}
			echo "</table>";			
			
			echo "<h2>Confirmation Details</h2>";
			echo "<p>&nbsp;</p>";
			echo "<table>";
			
			foreach($conf->xpath("//universal:HotelCreateReservationRsp/universal:UniversalRecord") as $UR){
				foreach($UR->attributes() as $a=>$b){
					if($a == 'LocatorCode'){
						echo "<tr>";
						echo "<td>Universal Record Locator Code</td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
						echo "</tr>";
					}
				}
				foreach($UR->xpath("//universal:ProviderReservationInfo") as $PNR){
					foreach($PNR->attributes() as $a=>$b){
						if($a == 'LocatorCode'){
							echo "<tr>";
							echo "<td>PNR</td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
							echo "</tr>";
						}
					}
				}
				foreach($UR->xpath("//hotel:HotelReservation") as $CN){
					foreach($CN->attributes() as $a=>$b){
						if($a == 'BookingConfirmation'){
							echo "<tr>";
							echo "<td>Confirmation Number</td>"."<td><input type=\"hidden\" id=\"$a\" name=\"$a\" value=\"$b\">$b</td>";
							echo "</tr>";
						}
					}
				}
			}
			
		    echo "</table>";
			
		}
?>
</body>
</html>
<?php
	if(!isset($_SESSION)){ 
		session_start();
	}
?>