<html>
<head>
  <?php include 'security.php' ?>
  
  
  <title></title>
  
    <!--<link rel="stylesheet" type="text/css" href="payment.css"/>-->
	<style>
.btn-link-dark {
    color: #ffffff;
    font-size: 16px;
    font-weight: 400;
    margin-top: 50px;
    padding: 8px 28px;
    border: 2px solid #e27d7f;
    border-radius: 5px;
    text-align: center;
    background: #e27d7f;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
}
.course_li{
margin-bottom: 40px;
    color:white;
    font-size: 16px;
    font-weight: 700;
	}
	.customcontainer{
background:#534051;
    border: 2px solid #e27d7f;
	}

</style>

</head>

<body>
<?php
// i will do the connection to database and take value from abbel tutor-profile to insert the bookin////
$servername = "localhost";
$username = "root";
$password = "";
$dbname="maisonpubdb";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
$sql = "INSERT INTO pre_bookings (student_id, tutor_id, course_id, duration_value, duration_type, fee, per_credit_value, start_date, end_date, time_slot, admin_commission, admin_commission_val, prev_status, status, updated_by, roomsession)
VALUES ('{$_POST['student_id']}', '{$_POST['tutor_id']}', '{$_POST['course_id']}', '{$_POST['duration_value']}', '{$_POST['duration_type']}', '{$_POST['amount']}', '{$_POST['per_credit_value']}', '{$_POST['start_date']}', '{$_POST['end_date']}', '{$_POST['time_slot']}', '{$_POST['admin_commission']}', '{$_POST['admin_commission_val']}', '{$_POST['prev_status']}', '{$_POST['status']}', '{$_POST['updated_by']}', '{$_POST['roomsession']}' )";
$sql2 = "DELETE FROM pre_bookings WHERE course_id=0";
if ($conn->query($sql)&&$conn->query($sql2) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>







<?php
    foreach($_REQUEST as $name => $value) {
        $params[$name] = $value;
    }
?>






<fieldset id="confirmation" style="display: none;">
    <div >
        <?php
            foreach($params as $name => $value) {
                echo "<div>";
                echo "<span class=\"fieldName\">" . $name . "</span><span class=\"fieldValue\">" . $value . "</span>";
                echo "</div>\n";
            }
        ?>
    </div>
</fieldset>

<div class="customcontainer" style="width:50%;margin:0 auto;">
<div class="course_li" style="width:85%;margin:0 auto;">You will be redirected to BLOM Bank Secure page, please confirm to provide your card details</div>





<form action="https://testsecureacceptance.cybersource.com/pay" method="post" id="sbmFrm"/>
    <?php
        foreach($params as $name => $value) {
            echo "<input type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }

        echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
    ?>

<input class="btn-link-dark" style="display:block;margin:0 auto;margin-top:20px;" type="submit" id="submit" value="Confirm"/>
<script>
document.getElementById("sbmFrm").submit();
</script>
</form>
</div>

</body>
</html>
