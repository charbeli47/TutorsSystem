<html>
<head>
    <title> OpenTok Getting Started </title>
    <link href="css/app.css" rel="stylesheet" type="text/css">
    <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="starcss.css">

<style>
.checked {
    color: orange;
}
</style>
<style>
.heading-line{
font-size: 24px;
    font-weight: 400;
    color: #e27d7f;
    border-bottom: 1px solid #e4e4e4;
    padding-bottom: 5px;
    margin: 10px 0 24px 0;
}
.input-group {
    height: 40px;
    margin-bottom: 15px;
    border-radius: 5px;
    color: #333333;
}
.custominput {
    width: 100%;
    font-size: 15px;
    font-weight: 400;
    padding-left: 10px;
    border: 0;
    color:#655063;
    padding-top: 10px;
    background-color:rgba(204, 204, 204, 0.38);
    border-radius: 5px;
    margin-top:20px;
}
.input-group textarea {
    width: 100%;
    font-size: 15px;
    font-weight: 400;
    padding-left: 10px;
    border: 0;
    color:#655063;
    padding-top: 10px;
     background-color:rgba(204, 204, 204, 0.38);
    border-radius: 5px;
    margin-top:20px;
}
 .btn-link-dark {
    margin-top: 55px;
    border-radius: 5px;
    color: #ffffff;
    background-color: #e27d7f;
    color: #ffffff;
    font-size: 14px;
    font-weight: 400;
    padding: 8px 22px;
    border: 2px solid #e27d7f; 
    text-align: center;
    background: #e27d7f;
}
</style>

</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "maisonpubdb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
	
	
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE pre_bookings SET status='session_initiated' WHERE roomsession='".$_GET['roomid']."'";
    if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
    } else {
    echo "Error updating record: " . $conn->error;
    }
	mysql_connect("localhost", "root", "") or die (mysql_error ());
	$sqll = "SELECT student_id, tutor_id, course_id FROM pre_bookings WHERE roomsession LIKE '".$_GET['roomid']."'";
	mysql_select_db("maisonpubdb") or die(mysql_error());
	// Execute the query (the recordset $rs contains the result)
	$rs = mysql_query($sqll);
	
	// Loop the recordset $rs
	// Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)) {
// Write the value of the column FirstName (which is now in the array $row)
	  $studentid=$row['student_id'];
	  $tutorid=$row['tutor_id'];
	  $courseid=$row['course_id'];
	  }

	// Close the database connection
	mysql_close();
 ?>
    <div id="videos">
        <div id="subscriber"></div>
        <div id="publisher"></div>
		</div>
		<div id="ratingbuttondiv">
		<button type="button" id="but" style="border:none;position:relative;margin-top:-450px;float:right;background-color:#655063;border-radius:25px;color:white;height:30px;z-index:9999" onclick="showratingarea();">Your Feedback</button>
		</div>
<div class="ratingarea" id="ratingarea" style="position:relative;margin-top:-450px;background-color:white;width:350px; height:350px;float:right;z-index:9999; border-radius: 15px;padding: 0px 10px 0px 10px;display:none;border:2.5px solid #655063">

<form id="studentrate" style="display:none" method="post" action="">
<h2 class="heading-line">Rate This Tutor & Give Feedback On Course</h2>

<!--rating stars -->
<div>
<label style="display:inline-block;float:left;width:30%;margin-top:12px;color:#e27d7f">Rate Tutor :</label><fieldset class="rating">
    <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
    <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
    <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
    <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
    <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
    <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
    <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
    <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
    <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
    <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
</fieldset>
</div>
<!--rating stars -->
<!--rating stars -->
</br>
<div>
<label style="display:inline-block;float:left;width:30%;margin-top:12px;color:#e27d7f">Rate Course :</label>
<fieldset class="rating2">
    <input type="radio" id="star25" name="ratingc" value="5" /><label class = "full" for="star25" title="Awesome - 5 stars"></label>
    <input type="radio" id="star24half" name="ratingc" value="4.5" /><label class="half" for="star24half" title="Pretty good - 4.5 stars"></label>
    <input type="radio" id="star24" name="ratingc" value="4" /><label class = "full" for="star24" title="Pretty good - 4 stars"></label>
    <input type="radio" id="star23half" name="ratingc" value="3.5" /><label class="half" for="star23half" title="Meh - 3.5 stars"></label>
    <input type="radio" id="star23" name="ratingc" value="3" /><label class = "full" for="star23" title="Meh - 3 stars"></label>
    <input type="radio" id="star22half" name="ratingc" value="2.5" /><label class="half" for="star22half" title="Kinda bad - 2.5 stars"></label>
    <input type="radio" id="star22" name="ratingc" value="2" /><label class = "full" for="star22" title="Kinda bad - 2 stars"></label>
    <input type="radio" id="star21half" name="ratingc" value="1.5" /><label class="half" for="star21half" title="Meh - 1.5 stars"></label>
    <input type="radio" id="star21" name="ratingc" value="1" /><label class = "full" for="star21" title="Sucks big time - 1 star"></label>
    <input type="radio" id="star2half" name="ratingc" value="0.5" /><label class="half" for="star2half" title="Sucks big time - 0.5 stars"></label>
</fieldset>
<!--rating stars -->
</div>
<div class="input-group">
<textarea name="reviewcourse" rows="4" style="width:100%" placeholder="Review About The Course ">
</textarea>
</div>
<input type="submit" value="Submit" name="submitstudent" class="btn-link-dark" style="width:50%" />
</form>
<?php

if(isset($_POST['submitstudent']))
    {
$sql = "INSERT INTO pre_course_rating (course_id, student_id, rating, review)
VALUES ('$courseid', '$studentid', '".$_POST['ratingc']."', '".$_POST['reviewcourse']."')";

$conn->query($sql);
    
$sql2 = "INSERT INTO pre_tutor_rating (tutor_id, student_id, rating)
VALUES ('$tutorid', '$studentid', '".$_POST['rating']."')";

$conn->query($sql2);
}
?>

<form id="tutorrate" style="display:block" method="post" action="">
<h2 class="heading-line">Give A Review On Student</h2>
<div class="input-group">
<textarea name="reviewstudent" rows="4" style="width:100%">
Review About Student 
</textarea>
</div>
<input type="submit" value="Submit" name="submittutor" class="btn-link-dark" style="width:50%" />
</form>
<?php
if(isset($_POST['submittutor']))
    {
$sql = "INSERT INTO pre_student_rating (student_id, tutor_id, review)
VALUES ('$studentid', '$tutorid', '".$_POST['reviewstudent']."')";
$conn->query($sql);

}
?>
</div>
    <script type="text/javascript" src="js/app.js"></script>
	<script>
function gup( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}
debugger
var user=gup('user', '')

if(user=="student"){
document.getElementById("studentrate").style.display = "block";
document.getElementById("tutorrate").style.display = "none";

}
else{
document.getElementById("studentrate").style.display = "none";
document.getElementById("tutorrate").style.display = "block";
}
function showratingarea(){
debugger
document.getElementById("but").style.display = "none";
$('#ratingarea').fadeIn('30000','','');
}
</script>
</body>
</html>


