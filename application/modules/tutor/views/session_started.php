<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
    <?php
		$attributes = array('name' => 'course_completed_for_student_booking_form', 'id' => 'course_completed_for_student_booking_form', 'class' => 'comment-form dark-fields');
		echo form_open(URL_TUTOR_COMPLETE_SESSION, $attributes);?>
		<div class="col-sm-12">
        <label><?php echo get_languageword('Time Remaining');?>:</label>
        <div style=background-color:gray;padding:10px;font-size:20px;color:white;color:white" id="demo"></p>

<script>
// Set the date we're counting down to
var countDownDate = new Date().getTime() + 1000 * <?php echo $duration?> * 60;

// Update the count down every 1 second
var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
    if (distance <= 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML ="Please insert your notes before submit";
        $("#submitbutt").show();
    }
}, 1000);
</script>
        </div>

			<div class="col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Description');?>:</label>
					<?php			   

					$val = set_value('status_desc', (!empty($status_desc)) ? $status_desc : '');

					$element = array(
						'name'	=>	'status_desc',
						'id'	=>	'status_desc',
						'value'	=>	$val,
						'class' => 'form-control',
                        'required'=>'required',
						'placeholder' => get_languageword('Write your notes here.'),
					);
					echo form_textarea($element);
					?>
				</div>
			</div>
			<?php echo form_hidden('booking_id', $bookingId);
            <?php echo form_hidden('course_id', $courseId);
			?>
			<div class="col-sm-12 ">
				<button class="btn-link-dark dash-btn" style="display:none" id="submitbutt" name="submitbutt" type="Submit"><?php echo get_languageword('End Session');?></button>
			</div>

		</form>
	</div>

</div>
<!-- Dashboard panel ends -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/timer/timercircle.css">
    <link rel="stylesheet" type="text/css" href="/assets/timer/skins/yellowcircle.css">
    <link rel="stylesheet" type="text/css" href="/assets/timer/skins/purplecircle.css">
    <link rel="stylesheet" type="text/css" href="/assets/timer/skins/firecircle.css">
    <link rel="stylesheet" type="text/css" href="/assets/timer/skins/whitecircle.css">
    <link rel="stylesheet" type="text/css" href="/assets/timer/skins/simplecircle.css">
    <script type="text/javascript" src="/assets/timer/circle.js"></script>
    <script>
        $(document).ready(function() {
            
            var t2 = new Circlebar({
                element: ".circle-2",
                maxValue: 252,
                dialWidth: 40,
                fontColor: "#777",
                fontSize: "30px",
                skin: "fire",
                type: "manual"
            });

           
        });
        </script>