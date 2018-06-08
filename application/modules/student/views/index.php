<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
		<!--<div class="col-md-4 pad10">
			<div class="dash-block d-block1">
				<h2><?php echo $my_profile->net_credits;?></h2>
                <p><?php echo get_languageword('Net Credits');?></p>
			</div>
		</div>-->	
        <div class="col-md-12">
        
        <?php
        foreach($bookings as $row):
            $now = strtotime(date("H:i:s"));
            $then = strtotime($row->time_slot);
          
            $minutes = ($then- $now)/60;  
        ?>
        <?php if($minutes>0){?>
         <div id="counter<?php echo $row->booking_id?>" class="alert alert-info" style="font-size:20px"></div>
         <script>
         
         showTimer(<?php echo $minutes?>,'counter<?php echo $row->booking_id?>');
         
         </script>
         <?php }
         endforeach;
        ?>
        
        </div>
		<div class="col-md-4 pad10">
			<div class="dash-block d-block2">
				<h2><?php echo $student_dashboard_data['total_bookings'];?><a class="pull-right" href="<?php echo base_url();?>enquiries"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Total Bookings');?></p>
			</div>
		</div>
		<div class="col-md-4 pad10">
			<div class="dash-block d-block3">
				<h2><?php echo $student_dashboard_data['pending_bookings']?><a class="pull-right" href="<?php echo base_url();?>enquiries/pending"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Bookings Pending');?>  </p>
			</div>
		</div>
		<div class="col-md-4 pad10">
			<div class="dash-block d-block4">
				<h2><?php echo $student_dashboard_data['initiated_bookings'];?><a class="pull-right" href="<?php echo base_url();?>enquiries/session_initiated"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Sessions Initiated');?></p>
			</div>
		</div>
		<div class="col-md-4 pad10">
			<div class="dash-block d-block5">
				<h2><?php echo $student_dashboard_data['approved_bookings'];?><a class="pull-right" href="<?php echo base_url();?>enquiries/approved"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Bookings Approved');?></p>
			</div>
		</div>	
        <div class="col-md-4 pad10">
			<div class="dash-block d-block6">
				<h2><?php echo $student_dashboard_data['free_courses'];?></h2>
				<p><?php echo get_languageword('Remaining free classes');?></p>
			</div>
		</div>	
		<!--<div class="col-md-4 pad10">
			<div class="dash-block d-block6">
				<h2><?php echo $student_dashboard_data['open_leads'];?><a class="pull-right" href="<?php echo base_url();?>student/student_leads"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Open Leads');?></p>
			</div>
		</div>-->
		
		<!--<div class="col-md-4 pad10">
			<div class="dash-block d-block7">
				<h2><?php echo $student_dashboard_data['closed_leads'];?><a class="pull-right" href="<?php echo base_url();?>student/student_leads"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Closed Leads');?></p>
			</div>
		</div>-->

		<!--<div class="col-md-4 pad10">
			<div class="dash-block d-block8">
				<h2><?php echo $student_dashboard_data['inst_enrolled'];?><a class="pull-right" href="<?php echo base_url();?>student/enrolled-courses"><?php echo get_languageword('View');?></a></h2>
				<p><?php echo get_languageword('Institue Enrolled Courses');?></p>
			</div>
		</div>-->

		
	</div>

</div>
<!-- Dashboard panel ends --> 