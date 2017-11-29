<?php 

	$column_width = (int)(80/count($columns));
	
	if(!empty($list)){
?><div class="bDiv" >
		<table cellspacing="0" cellpadding="0" border="0" id="flex1">
		<thead>
			<tr class='hDiv'>
				<?php foreach($columns as $column){?>
				<th width='<?php echo $column_width?>%'>
					<div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>" 
						rel='<?php echo $column->field_name?>'>
						<?php echo $column->display_as?>
					</div>
				</th>
				<?php }?>
				<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th align="left" abbr="tools" axis="col1" class="" width='20%'>
					<div class="text-right">
						<?php echo $this->l('list_actions'); ?>
					</div>
				</th>
				<?php }?>
			</tr>
		</thead>		
		<tbody>
		<?php $i=0; ?> 
<?php foreach($list as $num_row => $row){
$i++;

?>        
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
            <?php if($column->field_name!="roomsession"){?> 
				<div class='text-left<?php echo $i?>'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
                <?php }else if(($row->status =='Session Initiated' || $row->status == 'Running') && isset($row->roomsession) && $row->roomsession !=""){?>
                <a href="<?php echo $row->{$column->field_name}?>" target="_blank" style="display:block;background-color:#950d11;color:white;display:block;padding:5px;border-radius:5px;text-align:center">Join</a>
                <?php }?>
			</td>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td align="left" width='20%'>
				<div class='tools'>				
					<?php if(!$unset_delete){?>
                    	<a href='<?php echo $row->delete_url?>' title='<?php echo $this->l('list_delete')?> <?php echo $subject?>' class="delete-row" >
                    			<span class='delete-icon'></span>
                    	</a>
                    <?php }?>
                    <?php if(!$unset_edit){?>
						<a href='<?php echo $row->edit_url?>' title='<?php echo $this->l('list_edit')?> <?php echo $subject?>' class="edit_button"><span class='edit-icon'></span></a>
					<?php }?>
					<?php if(!$unset_read){?>
						<a href='<?php echo $row->read_url?>' title='<?php echo $this->l('list_view')?> <?php echo $subject?>' class="edit_button"><span class='read-icon'></span></a>
					<?php }?>
					<!--hawde ana zedtoun sah sah w zedet ossass fo2 ba3reffoun ghayaret el class sah sah 100 bel 100 gd g d-->
					
					
					
					
					<!--end of ana li zeyeddoun sahih sahih-->
					<?php 
					if(!empty($row->action_urls)){
						foreach($row->action_urls as $action_unique_id => $action_url){ 
							$action = $actions[$action_unique_id];
							if($action->label=="Join"){
					?>
					<form method="post" action="/zoom">
                    <input type="hidden" name="bookingId" value="<?php echo $row->booking_id;?>"/>
							<!--<a href="<?php echo $action->link_url."&roomid=".$row->roomsession; ?>" class="crud-action" title="<?php echo $action->label?>" id="joinbut<?php echo $i?>" name="testvideocall" style="visibility:hidden"></form>-->
							<input type="submit" id="joinbut<?php echo $i?>" name="testvideocall" style="display:none; background-color:#950d11;color:white;padding:5px;border-radius:5px;text-align:center;border:0px" value="Initiate session" onclick="window.open('/session-started/<?php echo $row->booking_id ?>', '_blank');"/></form>

							<?php 
							
								if(!empty($action->image_url))
								{
									?><!--<img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" />--><?php 	
								}
							?><!--</a>-->
							
					<?php }else{?>
						<a href="<?php echo $action_url?>" class="<?php echo $action->css_class; ?> crud-action" title="<?php echo $action->label?>"><?php 
								if(!empty($action->image_url))
								{
									?><img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" /><?php 	
								}
							?></a>
					<?php }
						}
					}
					?>					
                    <div class='clear'></div>
				</div>
			</td>
			<?php }?>
		</tr>

<?php } ?>        
		</tbody>
		</table>
	</div>
	
<?php }else{?>
	<br/>
	&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->l('list_no_items'); ?>
	<br/>
	<br/>
    
<?php }?>
<script>
					var preffereddate=$('.text-left<?php echo $i?>:eq(4)').text();
					var timeslot=$('.text-left<?php echo $i?>:eq(5)').text().trim();
                    
                    var dbhours = Number(timeslot.match(/^(\d+)/)[1]);
                    var dbminutes = Number(timeslot.match(/:(\d+)/)[1]);
                    var AMPM = timeslot.match(/\s(.*)$/)[1];
                    if(AMPM == "PM" && dbhours<12) dbhours = dbhours+12;
                    if(AMPM == "AM" && dbhours==12) hours = dbhours-12;
                    var sHours = dbhours.toString();
                    var sMinutes = dbminutes.toString();
                    if(dbhours<10) sHours = "0" + sHours;
                    if(dbminutes<10) sMinutes = "0" + sMinutes;

                    var dbtime = sHours+":"+sMinutes+":00";
					
					var status=$('.text-left<?php echo $i?>:eq(7)').text();
					var currentdate = new Date();

					var dd = currentdate.getDate();
					var mm = currentdate.getMonth()+1; //January is 0!
					var yyyy = currentdate.getFullYear();
                    
					if(dd<10) {
					 dd = '0'+dd
					} 

					if(mm<10) {
					mm = '0'+mm
					} 

					currentdate = dd + '/' + mm + '/' + yyyy;
					var now= new Date();
					var hours=now.getHours();
					var minutes=now.getMinutes();
                    var allowed = minutes - dbminutes>=-10 && minutes - dbminutes<=59;
                    var sminutes = minutes;
					if(minutes<10){
					    sminutes="0"+minutes;
					}
					var seconds=now.getSeconds();
					if(seconds<10){
					seconds="0"+seconds;
					}
					var currenttime =  hours+ ":" +sminutes+ ":" +seconds ;					
		            if((currentdate==preffereddate && allowed==true && currenttime>=dbtime && status=="Approved") || status == "Session Initiated"){			
	                    $("#joinbut<?php echo $i?>").css("display","block");
	                    setTimeout( function(){ 
                                        $("#joinbut<?php echo $i?>").css("display","none");
                                    }  , (dbminutes+59-minutes)*60*1000);
                     }
					</script>
<?php
/*Zoom Support*/
 
if(array_key_exists('testvideocall',$_POST)){
   $zoomclass=new ZoomAPI();
   $jsonformat=$zoomclass->createAMeeting('iK78ivWsQOiFS9q_CO9EnQ','test');
 $json_a=json_decode($jsonformat,true);
 $zoomstart_url=$json_a['start_url'];
  $zoomjoin_url=$json_a['join_url'];
header("Location:$zoomstart_url"); /* Redirect browser */
exit();

}
?>


