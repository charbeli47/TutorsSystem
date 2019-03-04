    
<!-- Header #homepage -->
    <section class="header-homepage">
    <!--slider-->
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/homeslider/css/style.css">
    
    <div class="slider">
	<div class="slider-inner">
    <?php
    $i=0; 
      foreach($homeslider as $row)
            {?>
            <div class="slide <?php $i==0?"active":""?>"><img src="/assets/uploads/homeslider_logos/<?php echo $row->image?>" style="width:100%;height:auto;vertical-align:top"/></div>
                <?php
$i++;
 }?>
	</div>
	
	<nav class="slider-nav">
    <?php
        $j=0; 
 foreach($homeslider as $row)
            {?>
            <div <?php echo $j==0?'class="active slidepointer"':'class="slidepointer"'?>></div>
                <?php $j++; }?>
		
	</nav>
</div>
	
        <div class="container">
            <div class="row header-margin">
                <div class="col-sm-12">
                    <h1 class="hero-title"><?php echo get_languageword('Learn').' - '.get_languageword('Read').' - '.get_languageword('Practice');?></h1>
                    <p class="hero-tag"><?php echo get_languageword('Live Conversation Classes With a vast choice of Arabic Teachers to find the')?> <strong><?php echo get_languageword('right'); ?></strong> <?php echo get_languageword('class for you');?></p>
                </div>
                <?php if(!$this->ion_auth->is_tutor()) { ?>
                <div class="col-sm-12">
                    <!-- Home Search form -->
                    <?php 
                          if(!empty($location_opts) || !empty($course_opts)): 
                            $this->load->view('sections/search_section_home', array('location_opts' => $location_opts, 'course_opts' => $course_opts), false);
                         endif;
                    ?>
                    <!-- Home Search form -->
                </div>
                <?php } ?>
                <div class="col-sm-12">
                    <!--<img src="<?php echo URL_FRONT_IMAGES;?>headericons.png" alt="" class="img-responsive">-->
                </div>
            </div>
        </div>
    </section>
    <!-- Ends Header #homepage -->

    <!-- Advantages #homepage -->
    <?php if(strip_tags($this->config->item('site_settings')->advantages_section) == "On") {
            echo $this->config->item('sections')->Advantages_Section;

         } ?>
    <!-- Ends Advantages #homepage -->
    <!--<section>
        <div class="container">
            <div class="row">
            <div class="col-sm-12 ">
                        <h2 class="heading"><span><?php echo get_languageword('our_packages'); ?></span></h2>
                    </div>
				<?php 
				  foreach($package_data as $l) { ?>
			   	<div class="col-lg-4 col-md-4 col-sm-12">
				  <div class="pricing_div">
					  <div class="site_pac_hed green-hed">
						<img src="/assets/uploads/package_logos/<?php echo $l->image?>" style="width:100%"/>
						<?php 
						$currency_symbol = '';
						if(isset($site_settings->currency_symbol))
						    $currency_symbol = $site_settings->currency_symbol;
						$final_cost = $l->package_cost;
						   if(isset($l->discount) && ($l->discount != 0))
							if($l->discount_type == 'Value')
							{
								$final_cost = $l->package_cost - $l->discount;
													
							}
							else
							{
								$discount = ($l->discount/100)*$l->package_cost;							
								$final_cost = $l->package_cost - $discount;
							?>
						<?php } else { ?>
						<?php 
						   //if($currency_symbol != '')
							//echo $currency_symbol.' ';
							//echo $final_cost;
						}
							?> 
					 </div>
					<div class="pack-list">
						<p><?php echo get_languageword('Package Name');?> <strong><?php echo $l->package_name?></strong></p>
						<p><?php echo get_languageword('Classes to be obtained: '); ?> <strong><?php echo $l->credits?></strong></p>

						<?php if(isset($l->discount) && ($l->discount_type == 'Value')){?>
						<p><strong> <?php echo get_languageword('Discount: ');?><?php echo get_system_settings('currency_symbol').' '. $l->discount;?></strong></p>
						<?php }
						else
						{?>
							<p> <?php echo get_languageword('Discount: ');?><strong><?php echo  $l->discount;?> %</strong></p>
						<?php }
						?>

						<p><?php echo get_languageword('Package Cost: ');?> <strike><?php echo get_system_settings('currency_symbol').' '.$l->package_cost?></strike><strong> <b>
						<?php echo  get_system_settings('currency_symbol').' '. $final_cost;?></b></strong></p>
						
					 </div>

					 <div class="radio">
							<center><a href="/auth/login?red=/student/list-packages"><span class="nav-btn"> <i class="fa  fa-sign-in"></i> Sign Up </span></a></center>
					</div>
				  </div>
			   	</div>
			   	<?php } ?>
		</div>
        </div>
    </section>-->

    <!-- Our-Popular #homepage -->
    <?php if(!empty($popular_courses)) { ?>
    <section class="our-popular">
        <div class="container">
            <div class="row-margin">
                <div class="row ">
                    <div class="col-sm-12 ">
                        <h2 class="heading"><?php echo get_languageword('our_popular_courses'); ?></h2>
                    </div>

                    <?php foreach ($popular_courses as $key => $courses) { 

                            $category = explode('_', $key);

                            //Category Details
                            $category_id   = $category[0];
                            $category_slug = $category[1];
                            $category_name = $category[2];

                        ?>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="pop-list">
                            <a href=<?php echo URL_HOME_ALL_COURSES.'/'.$category_slug;?> class="link-all"><?php echo get_languageword('see_all'); ?></a>
                            <h3 class="heading-line" title="<?php echo $category_name; ?>"><?php echo $category_name; ?></h3>
                            <ul>
                                <?php foreach ($courses as $key => $value) {

                                        $course   = explode('_', $value);
                                        //Course Details
                                        $course_id   = $course[0];
                                        $course_slug = $course[1];
                                        $course_name = $course[2];

                                 ?>
                                    <li><a href="<?php echo URL_HOME_SEARCH_TUTOR.'/'.$course_slug;?>" title="<?php echo $course_name; ?>"><?php echo $course_name; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                    <?php } ?>

                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="mtop4">
                            <a href="<?php echo URL_HOME_ALL_COURSES; ?>" class="btn-link"><?php echo get_languageword('check_all_courses'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    <!-- Ends Our-Popular #homepage -->
	<!--<div class="container">
	<div id="questionsdiv">
	<h2 class="heading">Take The Quiz To Know Your Appropriate Curriculum To Begin with</h2>-->
	
	  <?php
	 /* $i=0;
	  foreach ($quiz_questions as $quest) { 
	  if ($i=="0") {*/
   ?>
	<!--<div class="col-sm-12 quizdiv" style="display:block">
   <h3 class="heading-line"><?php //echo $quest->question?></h3>-->
    <?php
	 /* foreach ($options_question as $opt) {  
	  if($opt->questionid==$quest->idquestion){
	  if($opt->correct==0){*/
	   ?>
	   <!--<input type="radio" style="opacity:8" name="question<?php echo $i?>" value="0" ><?php echo $opt->optiontext?><br>  <br>-->
	 <?php /*}
	 else {*/
	 ?>
	   <!--<input type="radio" style="opacity:8" name="question<?php echo $i?>" value="20" ><?php //echo $opt->optiontext?><br>  <br>-->
	<?php  /*} 
	}
	}*/ ?>
    <br>  <br>
    <!--<input type="submit" class="nav-btn next"  value=" Next ">
   
</div>-->
  <?php /*}
  else{*/
    ?>
	<!--<div class="col-sm-12 quizdiv" style="display:none">
   <h3 class="heading-line" ><?php //echo $quest->question?></h3>
   -->
   <?php
	  /*foreach ($options_question as $opt) {  
   if($opt->questionid==$quest->idquestion){
   if($opt->correct=='YES'){*/
	   ?>
	   <!--<input type="radio" style="opacity:8"name="question<?php echo $i?>" value="0" ><?php echo $opt->optiontext?><br>  <br>-->
	 <?php /*}
	 else {*/
	 ?>
	   <!--<input type="radio" style="opacity:8" name="question<?php echo $i?>" value="20" ><?php echo $opt->optiontext?><br>  <br>-->
	<?php  /*} 
	}
	}*/ ?>
	
    <!--<br>  <br>-->
	<?php /*if($i==count($quiz_questions)-1){*/
	?>
    <!--<input type="submit" class="nav-btn" onclick="showresult()"  value="Show Results">-->
	<?php /*}
	else {*/
	 ?>
	 <!--<input type="submit" class="nav-btn next"   value=" Next ">-->

	<?php //}
	?>
   
<!--</div>
  <?php /*}
  $i++;
  }*/ ?>

 </div> 
<div class="col-sm-12" id="resultsdiv" style="display:none">
<h2 class="heading">Quiz Results</h2>  
   <center><h3 id="resultscore"> </h3></center>
   </div>
   </div>
  -->
 
  <script>
  $('.next').click(function(){
   $(this).parent().hide().next().show();//hide parent and show next
});
</script>
<script>
var countquestions=document.getElementsByClassName("quizdiv").length;
function showresult(){
document.getElementById("questionsdiv").style.display = "none";
document.getElementById("resultsdiv").style.display = "block";

var somme=0;
for (var j = 0; j<countquestions; j++) { 
var radios = document.getElementsByName('question'+j);
for (var x = 0, length = radios.length; x < length; x++)
{
 if (radios[x].checked)
 {
  // do whatever you want with the checked radio
  somme=somme+parseInt(radios[x].value);
// only one radio can be logically checked, don't check the rest
  break;
 }
}
}
$.post("/home/GetQuizScore/" + somme,function(msg){
		document.getElementById('resultscore').innerHTML = "We recommend you to take <span style='color:green'>" + msg+"</span>";
});
//alert(somme);
  

}
</script>


    <!-- Featured-On #homepage -->
    <?php /*if(strip_tags($this->config->item('site_settings')->featured_on_section) == "On") {

            echo $this->config->item('sections')->Featured_On_Section;

         } */?>
    <!-- Ends Featured-On #homepage -->

    <!-- Lession-cards #homepage -->
    <?php if(!empty($recent_courses)) { ?>
    <section class="lession-cards">
        <div class="container">
            <div class="row row-margin">
                <div class="col-sm-12 ">
                    <h2 class="heading"><?php echo get_languageword('Recently Added');?> <span><?php echo get_languageword('Courses');?></span></h2>
                </div>
                <?php foreach($recent_courses as $row) { ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="lession-card">
                        <a href="<?php echo '/course/'.$row->slug;?>">
                            <figure class="imghvr-zoom-in">
                                <div class="card">
                                    <div class="card-img" style="height:auto">
                                        <img src="<?php echo get_course_img($row->image); ?>" class="img-responsive" alt="">
                                        <figcaption></figcaption>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title" title="<?php echo $row->name;?>"><?php echo $row->name;?></h4>
                                        <!--<p class="card-info animated fadeIn" title="<?php echo $row->description;?>"><?php if(!empty($row->description)) echo $row->description; else echo '&nbsp;';?></p>-->
                                    </div>
                                </div>
                            </figure>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php } ?>
    <!-- Ends Lession-cards #homepage -->

    <!-- How-it-works #homepage -->
    <?php $about_us_how_it_works = $this->base_model->get_page_how_it_works(); 

        if(!empty($about_us_how_it_works)) {

            echo $about_us_how_it_works[0]->description;
        }
    ?>
    <!-- Ends How-it-works #homepage -->
    <div class="container" id='testimonials'>
        <div class="row row-margin">
        <div class="col-sm-12 ">
                <h2 class="heading"><?php echo get_languageword('Here’s a little demo video to get you familiar with');?> <span><?php echo get_languageword('Odemy');?></span>   <?php echo get_languageword('Platform');?>!</h2>
            </div>
            <div class="col-sm-12">
            <center>
            <iframe class="homeyoutube" src="https://www.youtube.com/embed/XmU2bD8CVhI?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </center>
            </div>
        </div>
    </div>
    <!-- Testimonial slider -->
    <!--<div class="container" id='testimonials'>
        <div class="row row-margin">
            <div class="col-sm-12 ">
                <h2 class="heading"><?php echo get_languageword('Why Students');?> <span><?php echo get_languageword('Love Us');?></span></h2>
            </div>
            <div class="col-sm-12">
                <div class="testimonial-slider owl-theme">
                    <?php foreach($site_testimonials as $row) {?>
                    <div class=" item">
                        <div class="feedback-block">
                            <div class="comment">
                                <h4>“</h4>
                                <p><?php echo $row->comments;?></p>
                            </div>
                            
                                <div class="profile-block">
                                    <div class="media-left">
                                        <div class="profile-img">
                                            <img src="<?php if(isset($row->image)) echo  URL_PUBLIC_UPLOADS_TESTIMONIALS.'/'. $row->image;?>" alt=".." class="img-circle">
                                        </div>
                                    </div>
                                    <div class="media-body">

                                        <h4><?php echo $row->name;?></h4>
                                        <p><?php echo $row->position;?></p>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>-->
    <!-- Ends Testimonial slider -->

    <!-- Counter #Homepage -->
    <?php //$this->load->view('lesson_count.php'); ?>
    <!-- Counter #Homepage -->

    <?php /*if(!empty($home_tutor_ratings)) {*/?>
    <!-- Top-rated slider -->
   <!-- <section class="weekly-top-rated">
        <div class="container">
            <div class="row row-margin">
                <div class="col-md-12">
                    <h2 class="heading-border-btm"><?php echo get_languageword('weekly_top'); ?> <span><?php echo get_languageword('tutors'); ?></span></h2>
                    <div class="toprated-slider owl-theme">
                    <?php foreach($home_tutor_ratings as $rating) {
                            $hlink = URL_HOME_TUTOR_PROFILE.'/'.$rating->slug;
                        ?>
                        <div class="item">
                            <div class="profile-block">
                                <div class="media-left">
                                    <div class="profile-img">
                                        <a href="<?php echo $hlink; ?>">
                                           <img src="<?php echo get_tutor_img($rating->photo, $rating->gender); ?>" alt="" class="img-circle">
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="<?php echo $hlink; ?>">
                                        <h4 title="<?php echo $rating->username;?>"><?php echo $rating->username;?></h4>
                                        <p><span><?php echo $rating->qualification;?></span></p>
                                        <div class="top_tutor_rating" <?php if(!empty($rating->rating)) echo 'data-score='.$rating->rating; ?> ></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
    
                    </div>
                </div>
            </div>
        </div>
    </section>-->
    <!-- Ends Top-rated slider -->
<?php /*} */?>
    <!-- Call-to-register -->
    <?php /* if(strip_tags($this->config->item('site_settings')->are_you_teacher_section) == "On") {

            echo $this->config->item('sections')->Are_You_A_Teacher_Section;

         }*/ ?>
    <!-- Call-to-register -->


<link rel="stylesheet" href="<?php echo URL_FRONT_CSS;?>jquery.raty.css">
<script src="<?php echo URL_FRONT_JS;?>jquery.raty.js"></script>
<script>

    /****** Tutor Avg. Rating  ******/
   $('div.top_tutor_rating').raty({

    path: '<?php echo RESOURCES_FRONT;?>raty_images',
    score: function() {
      return $(this).attr('data-score');
    },
    readOnly: true
   });

   
</script>
<script  src="/homeslider/js/index.js"></script>