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
<?php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function get_timezone()
{  //$_SERVER['REMOTE_ADDR']
//$ip = get_client_ip();

$timezone = file_get_contents('http://seu.brandseducation.com/timezone.php');
//$ipInfo = json_decode($ipInfo);
//$timezone = $ipInfo->timezone;
return $timezone;
}
foreach($list as $num_row => $row){
$i++;
if(isset($row->{"time_slot"}))
{
	$timeslot = $row->{"time_slot"};
	$satrt_Date = $row->{"start_date"};
	$timezone = get_timezone();
	$tz = new DateTimeZone($timezone);
	$start = $satrt_Date." ".$timeslot;
	$start = str_replace('/', '-', $start);
	
	$date = new DateTime($start);

	$date->setTimezone($tz);
	}
?>        
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
            <?php if($column->field_name!="roomsession"){
			if($column->field_name == "preferred_commence_date"){?> 
			<div class='text-left<?php echo $i?>'><?php echo $date->format('dd/MM/yyyy'); ?></div>
			<?php }else if($column->field_name == "time_slot"){?> 
			<div class='text-left<?php echo $i?>'><?php echo $date->format('h:i:s A'); ?></div>
			<?php }else{?>
				<div class='text-left<?php echo $i?>'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
                <?php }}else if(($row->status =='Session Initiated' || $row->status =='Running' ) && isset($row->status) && $row->status !=""){?>
                <a href="<?php echo $row->{$column->field_name}?>" target="_blank" style="display:block;background-color:#950d11;color:white;display:block;padding:5px;border-radius:5px;text-align:center" onclick="changeSessionToRunning(<?php echo $row->booking_id?>,this)">Join</a>
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
					<?php 
					if(!empty($row->action_urls)){
						foreach($row->action_urls as $action_unique_id => $action_url){ 
							$action = $actions[$action_unique_id];
							if($action->label=="Join"){
					?>
					<form method="post" action="/zoom">
                    <input type="hidden" name="bookingId" value="<?php echo $row->booking_id;?>"/>
							<!--<a href="<?php echo $action->link_url."&roomid=".$row->roomsession; ?>" class="crud-action" title="<?php echo $action->label?>" id="joinbut<?php echo $i?>" name="testvideocall" style="visibility:hidden"></form>-->
							<input type="submit" id="joinbut<?php echo $i?>" name="testvideocall" style="background-color:#950d11;color:white;padding:5px;border-radius:5px;text-align:center;border:0px" value="Initiate session" onclick="window.open('/session-started/<?php echo $row->booking_id ?>', '_blank');"/></form>

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
					
                     function changeSessionToRunning(bookingId, f)
                     {
                        $.post("/home/ChangeStatus",{bookingId:bookingId}, function(data){if(data=="success"){f.style.display = "none";}});
						
                     } 
					 var timez = Intl.DateTimeFormat().resolvedOptions().timeZone;
					 $.post("/timezone.php",{timezone:timez});
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


