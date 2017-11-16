<html>
<?php $unim = uniqid(1); ?>
<?php $r=rand(0,10000000); ?>

<head>
<title>Secure Acceptance - Payment Form Example</title>
    
<body>

<p style="font-family: arial, serif; font-size:20pt; color:#035398; font-style:Bold">
 Kindly fill the below information to proceed to payment:<br>
<form id="payment_form" action="payment_confirmation.php" method="post">
</p>
    
<p style="font-family: arial, serif; font-size:14pt; color:#035398; font-style:Bold">
<input type="hidden" name="access_key" value="0573013c517732cc9fc745e7f04e892d">   
<input type="hidden" name="profile_id" value="A2B22BBD-BF54-4AEE-94F8-667D02CEE07E">
<input type="hidden" name="transaction_uuid" value="<?php echo uniqid() ;?>">
<input type="hidden" name="signed_date_time" value="<?php echo gmdate("Y-m-d\TH:i:s\Z"); ?>">    
<input type="hidden" name="locale" value="en">

<input type="hidden" name="currency" value="USD">
<input type="hidden" name="transaction_type" value="sale">        
<input type="hidden" name="reference_number" value="<?php echo($r);?>">

<input type="hidden" name="signed_field_names" value="bill_to_address_line2,amount,access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,currency">
<input type="hidden" name="unsigned_field_names" value="bill_to_surname,bill_to_forename,bill_to_address_country,bill_to_address_line1,bill_to_address_city,bill_to_email,bill_to_phone">

First Name <input type="text" name="bill_to_forename" value="Mohamad"><br><br>
Last Name <input type="text" name="bill_to_surname" value="Mzannar"><br><br>
Phone or Mobile Number <input type="text" name="bill_to_phone" value="03892998"><br><br>
Email <input type="text" name="bill_to_email" value="acquiring@blom.com.lb"><br><br>
Building <input type="text" name="bill_to_address_line2" value="hamra"><br><br>
Street <input type="text" name="bill_to_address_line1" value="hamra"><br><br>
City <input type="text" name="bill_to_address_city" value="beirut"><br><br>
Country<input type="text" name="bill_to_address_country" value="lb"><br><br>
Amount<input type="text" name="amount" value="100"><br><br>

<input type="submit" id="submit" name="submit" value="Submit"/> 

</form></body>

</html>
 


