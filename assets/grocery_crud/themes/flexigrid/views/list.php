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
$i++;?>        
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
				<div class='text-left<?php echo $i?>'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
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
					<form method="post">
							<!--<a href="<?php echo $action->link_url."&roomid=".$row->roomsession; ?>" class="crud-action" title="<?php echo $action->label?>" id="joinbut<?php echo $i?>" name="testvideocall" style="visibility:hidden"></form>-->
							<input type="submit" id="joinbut<?php echo $i?>" name="testvideocall" style="visibility:hidden; background-img:http://localhost/tutorsproj/Web/assets/front/images//initiate-session.png"></form>

							<?php 
							
								if(!empty($action->image_url))
								{
									?><img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" /><?php 	
								}
							?></a>
							<script>
					debugger
					var preffereddate=$('.text-left<?php echo $i?>:eq(5)').text();
					var timeslot=$('.text-left<?php echo $i?>:eq(6)').text();
					var timeslotarr=timeslot.split('-');
					var ts=timeslotarr[0];
					ts=timeslotarr[0]+":"+"00"+":"+"00";
					var status=$('.text-left<?php echo $i?>:eq(8)').text();
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
					if(minutes<10){
					minutes="0"+minutes;
					}
					var seconds=now.getSeconds();
					if(seconds<10){
					seconds="0"+minutes;
					}
					var currenttime =  hours+ ":" +minutes+ ":" +seconds ;					
		if(currentdate==preffereddate && minutes<10 && currenttime>=ts && status=="Approved"){			
	  $("#joinbut<?php echo $i?>").css("visibility","visible");
	  setTimeout( function(){ 
     $("#joinbut<?php echo $i?>").css("visibility","hidden");
  }  , (10-minutes)*60*1000);
   }

					</script>
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

<?php
/*Zoom Support*/
 class ZoomAPI{
/*The API Key, Secret, & URL will be used in every function.*/
private $api_key = 'E8OYGdEDSleFqd646QUZOw';
private $api_secret = 'bcAxseuyNhe6fqRjfzNOoWhGW4rP1M483FbJ';
private $api_url = 'https://api.zoom.us/v1/';

/*Function to send HTTP POST Requests*/
/*Used by every function below to make HTTP POST call*/
function sendRequest($calledFunction, $data){
	/*Creates the endpoint URL*/
	$request_url = $this->api_url.$calledFunction;

	/*Adds the Key, Secret, & Datatype to the passed array*/
	$data['api_key'] = $this->api_key;
	$data['api_secret'] = $this->api_secret;
	$data['data_type'] = 'JSON';

	$postFields = http_build_query($data);
	/*Check to see queried fields*/
	/*Used for troubleshooting/debugging*/
	//echo $postFields;

	/*Preparing Query...*/
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_URL, $request_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	/*Check for any errors*/
	$errorMessage = curl_exec($ch);
	//echo $errorMessage;
	curl_close($ch);

	/*Will print back the response from the call*/
	/*Used for troubleshooting/debugging		*/
	//echo $request_url;
	var_dump($data);
	var_dump($response);
	if(!$response){
		return false;
	}
	/*Return the data in JSON format*/
	$jsonresponse=json_encode($response);
	$arrdata=json_decode($jsonresponse, true);
	//echo $arrdata;
	return $arrdata;
}

public function createAMeeting(){
  $createAMeetingArray = array();
  $createAMeetingArray['host_id'] ='iK78ivWsQOiFS9q_CO9EnQ';
  $createAMeetingArray['topic'] = "test";
  $createAMeetingArray['type'] = "1";
  return $this->sendRequest('meeting/create', $createAMeetingArray);
}
public function getUserInfoByEmail(){
  $getUserInfoByEmailArray = array();
  $getUserInfoByEmailArray['email'] = 'youssefkeryakos@gmail.com';
  $getUserInfoByEmailArray['login_type'] = '100';
  return $this->sendRequest('user/getbyemail',$getUserInfoByEmailArray);
}
}
if(array_key_exists('testvideocall',$_POST)){
   $zoomclass=new ZoomAPI();
   $jsonformat=$zoomclass->createAMeeting();
 $json_a=json_decode($jsonformat,true);
 $zoomstart_url=$json_a['start_url'];
  $zoomjoin_url=$json_a['join_url'];
header("Location:$zoomstart_url"); /* Redirect browser */
exit();

}
?>


