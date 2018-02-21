<html>
<head>
  
  
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

<div class="customcontainer" style="width:50%;margin:20% auto;">
<div class="course_li" style="width:85%;margin:0 auto;">Processing, please wait...</div>





<form action="https://secureacceptance.cybersource.com/pay" method="post" id="sbmFrm"/>
    <?php
        foreach($params as $name => $value) {
            echo "<input type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }

        echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
    ?>



</form>
</div>
<script>
document.getElementById("sbmFrm").submit();
</script>
</body>
</html>
