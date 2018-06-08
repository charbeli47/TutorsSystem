<!--Inner Dashboard Sub Menu-->
<?php $this->load->view('navigation');?>
<!--Inner Dashboard Sub Menu--> 

  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-9">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle : 'No Title'?>
              <?php if(isset($helptext) && count($helptext) > 0) {?>
			  <!--Help--> 
               <div class="btn digi-trash digi-remove pull-right help" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">  <img src="<?php echo URL_ADMIN_IMAGES;?>help.png"> </div>
              <!-- Help -->
			  <?php } ?>
              
            </div>
            <div class="panel-body"> 
              <?php if(isset($helptext) && count($helptext) > 0) {?>
			  <!--Help Collapse-->
              <div class="collapse" id="collapseExample">
                <div class="well help_coll">
                  <ul>
                    <?php foreach($helptext as $helpmessage) {?>
					<li><span class="glyphicon glyphicon-ok-circle"></span> <?php echo $helpmessage;?> </li>
					<?php } ?>                    
                  </ul>
                </div>
              </div>
              <!--Help Collapse-->
			  <?php } ?>
              <!--Input Text Feilds-->
              <div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
              <h2 class="devideHeader"><?php echo get_languageword('basic_details')?></h2>
			  
              <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'slide_name' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->slide_name;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'slide_name',
						'id'	=>	'slide_name',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('slide_name')?><font color="red">*</font></label>
                </div>
              </div>
			  
              
			  
			  
			  
			  	  
			  <div class="form-group">
                <div class="group">
                  <?php
				  if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'status' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->status;
					}
					else
					{
						$val = '';
					}
				  $options = activeinactive();
				  echo form_dropdown('status', $options, $val, 'required="required" class="chzn-select"');
				  ?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('status')?></label>
                </div>
              </div>
			  
              <div class="form-group">
                <div class="fileType"> <label ><?php echo get_languageword('slide_image')?></label>                
                  <input id="image" name="image" class="file" type="file" placeholder="Upload File"  data-min-file-count="3">
				  <?php			   
					$image = '';
					if( isset($details) &&  count($details) > 0)
					{
						$image = $details[0]->image;
					}
					if(!empty($image))
					echo '<img src="'.URL_PUBLIC_UPLOADS_HOME_SLIDER . $image.'" alt="'.$details[0]->image.'">';
					?>
                </div>
              </div>
              
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
          <i class="fa fa-send"></i> <?php echo get_languageword('submit')?> 
            </button>
			
			<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="addnew">
             <i class="fa fa-send"></i> <?php echo get_languageword('add__new')?> 
            </button>
            
               <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_HOME_SLIDER_VIEWSLIDES;?>'">
           <i class="flaticon-round73"></i> <?php echo get_languageword('cancel')?> 
            </button>
            </div>
			<input type="hidden" name="id" value="<?php echo $id;?>">
              
              <!--Input Text Feilds--> 
              
            </div>
          </div>
        </div>
      </div>
	  </form>
      
     
    </div>
  </div>
 