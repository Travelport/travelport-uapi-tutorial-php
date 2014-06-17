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
<h1>Hotel Book</h1>
<h2>Please enter booking information</h2>
<p>&nbsp;</p>
<div class="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1">Guest Information</a></li>
        <li><a href="#tab2">Contact Information</a></li>        
        <li><a href="#tab3">Payment Information</a></li>
    </ul>
    <p class="line-separator">&nbsp;</p>
    <div class="tab-content">
	<form id="formguest" action="phpSample_HotelBook.php" method="post" name="formguest">
        <p class="line-separator">&nbsp;</p>
    	<div id="tab1" class="tab active">        	
            	<table class="tabtable" style="margin-left:0.2em;">
  <tr>
    <td>Prefix</td>
    <td><select id="prefix1" name="prefix1" title="prefix1">
      <option value="Mr">Mr</option>
      <option value="Ms">Ms</option>
      <option value="Mrs">Mrs</option>    	
    </select></td>
  </tr>
  <tr>
    <td>First Name</td>
    <td><input name="firstname1" type="text" id="firstname1" title="firstname1"></td>
  </tr>
  <tr>
    <td>Last Name</td>
    <td><input name="lastname1" type="text" id="lastname1" title="lastname1"></td>
  </tr>
  <tr>
    <td>Gender</td>
    <td><select id="gen1" name="gen1" title="gen1">
      <option value="M">M</option>
      <option value="F">F</option>  	
    </select></td>
  </tr>
  <tr>
    <td>Nationality</td>
    <td><select id="con1" name="con1" title="gen1">
      <option value="US">US</option>
      <option value="CA">CA</option>  	
    </select></td>
  </tr>
  <tr>
    <td>Date Of Birth</td>
    <td><input type="text" name="dob1" id="dob1"/></td>
  </tr>
<?php
	if($_SESSION["NUMADULTS"] == '2'){
		echo "<tr>
					<td>Prefix</td>
					<td><select id=\"prefix2\" name=\"prefix2\" title=\"prefix2\">
					  <option value=\"Mr\">Mr</option>
					  <option value=\"Ms\">Ms</option>
					  <option value=\"Mrs\">Mrs</option>    	
					</select></td>
				  </tr>
				  <tr>
					<td>First Name</td>
					<td><input name=\"firstname2\" type=\"text\" id=\"firstname2\" title=\"firstname2\"></td>
				  </tr>
				  <tr>
					<td>Last Name</td>
					<td><input name=\"lastname2\" type=\"text\" id=\"lastname2\" title=\"lastname2\"></td>
				  </tr>
				  <tr>
					<td>Gender</td>
					<td><select id=\"gen2\" name=\"gen2\" title=\"gen2\">
					  <option value=\"M\">M</option>
					  <option value=\"F\">F</option>  	
					</select></td>
				  </tr>
				  <tr>
					<td>Nationality</td>
					<td><select id=\"con2\" name=\"con2\" title=\"gen2\">
					  <option value=\"US\">US</option>
					  <option value=\"CA\">CA</option>  	
					</select></td>
				  </tr>
				  <tr>
					<td>Date Of Birth</td>
					<td><input type=\"text\" id=\"dob2\" name=\"dob2\"/></td>
				  </tr>";
	}
  ?>  
</table>
        </div>
        <div id="tab2" class="tab">
		<table class="tabtable" style="margin-left:0.2em;">
  <tr>
    <td>Contact Number</td>
    <td>
    <input maxlength="3" style="width:2em;" type="text" id="ccode1" name="ccode1" title="ccode1">-
    <input maxlength="3" style="width:3em;"  type="text" id="area1" name="area1" title="area1">-
    <input maxlength="7" type="text" id="phone1" name="phone1" title="phone1">  	
	</td>
  </tr>
  <tr>
    <td>Address</td>
    <td><input name="add" type="text" id="add" title="add"></td>
  </tr>
  <tr>
    <td>City</td>
    <td><input name="city" type="text" id="city" title="city"></td>
  </tr>
  <tr>
    <td>State</td>
    <td><select id="state" name="state" title="state">
      <option value="AL">AL</option>
      <option value="AK">AK</option>
      <option value="AZ">AZ</option>
      <option value="AR">AR</option>
      <option value="CA">CA</option>
      <option value="CO">CO</option>
      <option value="CT">CT</option>
      <option value="DE">DE</option>
      <option value="DC">DC</option>
      <option value="FL">FL</option>
      <option value="GA">GA</option>
      <option value="HI">HI</option>
      <option value="ID">ID</option>
      <option value="IL">IL</option>
      <option value="IN">IN</option>
      <option value="IA">IA</option>
      <option value="KS">KS</option>
      <option value="KY">KY</option>
      <option value="LA">LA</option>
      <option value="ME">ME</option>
      <option value="MD">MD</option>
      <option value="MA">MA</option>
      <option value="MI">MI</option>
      <option value="MN">MN</option>
      <option value="MS">MS</option>
      <option value="MO">MO</option>
      <option value="MT">MT</option>
      <option value="NE">NE</option>
      <option value="NV">NV</option>
      <option value="NH">NH</option>
      <option value="NJ">NJ</option>
      <option value="NM">NM</option>
      <option value="NY">NY</option>
      <option value="NC">NC</option>
      <option value="ND">ND</option>
      <option value="OH">OH</option>
      <option value="OK">OK</option>
      <option value="OR">OR</option>
      <option value="PA">PA</option>
      <option value="RI">RI</option>
      <option value="SC">SC</option>
      <option value="SD">SD</option>
      <option value="TN">TN</option>
      <option value="TX">TX</option>
      <option value="UT">UT</option>
      <option value="VT">VT</option>
      <option value="VA">VA</option>
      <option value="WA">WA</option>
      <option value="WV">WV</option>
      <option value="WI">WI</option> 	
      <option value="WY">WY</option>
    </select></td>
  </tr>
  <tr>
    <td>Country</td>
    <td><select id="con1" name="con1" title="con1">
      <option value="US">US</option>
    </select></td>
  </tr>
  <tr>
    <td>Zip Code</td>
    <td><input type="text" name="zip" id="zip"/></td>
  </tr>
  <tr>
    <td>Email Address</td>
    <td><input type="text" name="email" id="email"/></td>
  </tr>
</table>           
        </div>
		        <div id="tab3" class="tab">
				<table class="tabtable" style="margin-left:0.2em;">
  <tr>
    <td>Name on the Card</td>
    <td>
		<input type="text" id="ccname" name="ccname" title="ccname" style="width:17em;">
	</td>
  </tr>				
  <tr>
    <td>Credit Card</td>
    <td>
    <input maxlength="16" type="text" id="ccnum" name="ccnum" title="ccnum" style="width:17em;">
	</td>
  </tr>
  <tr>
    <td>Credit Card Bank</td>
    <td><input name="ccbank" type="text" id="ccbank" title="ccbank" style="width:3em;"></td>
  </tr>
  <tr>
    <td>Credit Card Country</td>
    <td><input name="cccon" type="text" id="cccon" title="cccon" style="width:3em;"></td>
  </tr>
  <tr>
    <td>Expiration Date</td>
    <td><input name="exp" type="text" id="exp" title="exp" style="width:7em;"></td>
  </tr>
  <tr>
    <td>Credit Card Type</td>
    <td><input name="cctype" type="text" id="cctype" title="cctype" style="width:3em;"></td>
  </tr>
  <tr>
    <td>CVV</td>
    <td><input name="cvv" type="text" id="cvv" title="cvv" style="width:3em;"></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td><input id="submitbook" class="myButton" name="submitbook" value="Complete Booking" type="submit"></td>
          </tr>
</table>
</div>           
        </div>
		</form>
    </div>    
</body>
</html>
<?php
	if(!isset($_SESSION)){ 
		session_start();//Starting the session to execute all the requests in the same transaction
	}
?>