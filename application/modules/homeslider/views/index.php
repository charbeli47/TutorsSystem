<?php $this->load->view('template/common/search');?>
<div class="container">
<div class="row">
<div class="col-lg-12">
<ol class="breadcrumb bdb">
 <li><a href="<?php echo URL_USER;?>"><?php echo get_languageword('dashboard');?></a></li>
  <li><?php echo get_languageword('home_slider');?></li>
</ol>
</div>
</div>
</div>

<div class="container">
<div class="row">

<div id="infoMessage"><?php echo print_message($message);?></div>
 
    <?php
	foreach($homeslider as $package)
	{
		$image = URL_PUBLIC_UPLOADS_PACKAGES . 'package-icon.png';
		
		if(isset($package->image) && $package->image != '' && file_exists('assets/uploads/homeslider/'.$package->image))
		{
		$image = URL_PUBLIC_UPLOADS_HOME_SLIDER . $package->image;
		}
		?>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="package">
		<img src="<?php echo $image;?>" alt="" title="">
		 <h3><?php echo $package->slide_name;?> </h3> 
		
		</div>
		</div>
	<?php } ?>
 

 
		  </div>
		
</div>
 

