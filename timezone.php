<?php    
    session_start();
	$timezone = "Asia/Beirut";
	if(isset($_POST["timezone"]))
	{
		$timezone = $_POST["timezone"];
		$_SESSION["timezone"] = $timezone;
	}
	else if(isset($_SESSION["timezone"]))
		$timezone = $_SESSION["timezone"];
	echo $timezone;
?>