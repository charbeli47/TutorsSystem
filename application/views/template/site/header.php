<!DOCTYPE html>
<html lang="en">

<head>
    <title>
	<?php 
	if(isset($pagetitle) && $pagetitle != '')
	echo $pagetitle.' - '. $this->config->item('site_settings')->site_title ;
	elseif(isset($this->config->item('site_settings')->site_title) && $this->config->item('site_settings')->site_title != '')
	echo $this->config->item('site_settings')->site_title;
	else
		echo get_languageword('Tutors').' : '.get_languageword('Find Tutors Now');
	?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="description" content="<?php if(isset($meta_description) && $meta_description != "") echo $meta_description; elseif(isset($this->config->item('seo_settings')->meta_description) && $this->config->item('seo_settings')->meta_description != '') echo $this->config->item('seo_settings')->meta_description; else if(isset($this->config->item('seo_settings')->site_description) && $this->config->item('seo_settings')->site_description != '') echo $this->config->item('seo_settings')->site_description;?>">

	<meta name="keywords" content="<?php if(isset($meta_keywords) && $meta_keywords != "") echo $meta_keywords; elseif(isset($this->config->item('seo_settings')->meta_keyword)) echo $this->config->item('seo_settings')->meta_keyword;?>">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php if(isset($this->config->item('site_settings')->favicon) && file_exists(URL_PUBLIC_UPLOADS . 'settings/thumbs/'.$this->config->item('site_settings')->favicon)) echo URL_PUBLIC_UPLOADS2.'settings/thumbs/'.''.$this->config->item('site_settings')->favicon; else echo URL_FRONT_IMAGES.'favicon.ico';?>"/>
	
	<?php
	if(isset($grocery) && $grocery == TRUE) 
	{	
	?>
		<?php 
		foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>
		<script src="<?php echo URL_FRONT_JS;?>jquery.js"></script>
	<?php
	}
if(isset($hascalendar) && $hascalendar == TRUE){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php }?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
       <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
       <!--[if lt IE 9]>
           <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
           <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
       <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo URL_FRONT_CSS;?>main.css">

        
<link rel="stylesheet" href="<?php echo base_url() ?>fullcalendar/fullcalendar.min.css" />
    <script>
        function showTimer(duration, container)
        {
            
// Set the date we're counting down to
var countDownDate = new Date().getTime() + 1000 * duration * 60;

// Update the count down every 1 second
var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById(container).innerHTML = minutes + "m " + seconds + "s for your next session";
    if (distance <= 0) {
        clearInterval(x);
        document.getElementById(container).innerHTML ="Your session started please clist on Session Initiated Box below to start";
    }
}, 1000);
        }
</script>  
<!--Start of Zendesk Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){
z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='https://v2.zopim.com/?dQF9inZqajsH6ZcEUDNlVW6RTfsyJfWv';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zendesk Chat Script-->         
</head>

<body>
    <!-- Preloader -->
    <!-- <div id="preloader">
        <div id="status">&nbsp;</div>
    </div> -->
    <!-- Ends Preloader -->

    <!-- Top bar -->
    <?php if(strip_tags($this->config->item('site_settings')->top_most_section) == "On") { ?>
    <div class="navbar-inverse top-bar">
        <div class="container">
            <ul class="nav navbar-nav top-nav-left">
                <li><a href="<?php echo URL_HOME_ABOUT_US;?>"><?php echo get_languageword('About Us');?> </a></li>
                <li><a href="<?php echo URL_HOME_ALL_COURSES;?>"><?php echo get_languageword('Curriculum');?> </a></li>
            </ul>
            <?php if(isset($this->config->item('site_settings')->land_line) && $this->config->item('site_settings')->land_line != '') { ?>
			<ul class="nav navbar-nav pull-right">
                  <!--<li><a href="tel:<?php echo $this->config->item('site_settings')->land_line; ?>"><i class="fa fa-phone top-bar-icn"></i><?php echo get_languageword('Feel_free_to_call_us_anytime_on');?>  <?php echo $this->config->item('site_settings')->land_line;?></a></li>-->
            </ul>
			<?php } ?>
        </div>
    </div>
    <?php } ?>
    <!-- Ends Topbar -->

    <!-- Nagigation -->
    <nav class="navbar navbar-default yamm">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <?php if ($this->ion_auth->logged_in() && !empty($my_profile)) { ?>
                <button type="button" class="offcanvas-btn visible-xs" data-toggle="offcanvas" style="display: none;">
                    <img src="<?php echo get_tutor_img($my_profile->photo, $my_profile->gender);?>" class="img-circle " alt="<?php echo $my_profile->first_name;?> <?php echo $my_profile->last_name;?>">
                </button>
                <?php } ?>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mega-nav-menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <!--<a href="<?php echo base_url();?>"><img src="<?php  if(isset($this->config->item('site_settings')->logo) && $this->config->item('site_settings')->logo != '') echo URL_PUBLIC_UPLOADS_SETTINGS.''.$this->config->item('site_settings')->logo; else echo URL_FRONT_IMAGES.'Logo.png';?>" class="logo <?php if($this->ion_auth->logged_in() && !empty($my_profile)) echo "dahboard-logo"; ?>" alt="logo"></a>-->
            </div>

            <!-- Collect the nav links, mega-menu, vertical-menu and other content for toggling -->
            <div class="collapse navbar-collapse" id="mega-nav-menu">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="<?php if(isset($activemenu) && $activemenu == "home") echo 'active'; ?>" href="<?php echo SITEURL;?>"> <?php echo get_languageword('Home');?> </a></li>

                    <?php if(!$this->ion_auth->logged_in()) { ?>
                        <li><a class="<?php if(isset($activemenu) && $activemenu == "search_tutor") echo 'active'; ?>" href="<?php echo URL_HOME_SEARCH_TUTOR;?>"> <?php echo get_languageword('Find Tutor');?> </a></li>

                        <!--<li><a class="<?php if(isset($activemenu) && $activemenu == "search_institute") echo 'active'; ?>" href="<?php echo URL_HOME_SEARCH_INSTITUTE;?>"> <?php echo get_languageword('Find Institute');?> </a></li>

					   <li><a class="<?php if(isset($activemenu) && $activemenu == "search_student_leads") echo 'active'; ?>" href="<?php echo URL_HOME_SEARCH_STUDENT_LEADS;?>" title="<?php echo get_languageword('find_student_leads'); ?>"> <?php echo get_languageword('Find Leads');?> </a></li>-->
                    <?php } 
                          else { 
                            $user_id = $this->ion_auth->get_user_id();
                            if($this->ion_auth->is_student()) {
                    ?>
                        <li><a class="<?php if(isset($activemenu) && $activemenu == "search_tutor") echo 'active'; ?>" href="<?php echo URL_HOME_SEARCH_TUTOR;?>"> <?php echo get_languageword('Find Tutor');?> </a></li>
                         <!--<li><a class="<?php if(isset($activemenu) && $activemenu == "search_institute") echo 'active'; ?>" href="<?php echo URL_HOME_SEARCH_INSTITUTE;?>"> <?php echo get_languageword('Find Institute');?> </a></li>-->
                    <?php } else if(($this->ion_auth->is_tutor() || $this->ion_auth->is_institute()) && !is_inst_tutor($user_id)) { ?>
                       <!-- <li><a class="<?php if(isset($activemenu) && $activemenu == "search_student_leads") echo 'active'; ?>" href="<?php echo URL_HOME_SEARCH_STUDENT_LEADS;?>" title="<?php echo get_languageword('find_student_leads'); ?>"> <?php echo get_languageword('Find Leads');?> </a></li>-->
                    <?php } } ?>


                    <?php 
                            $find_courses = get_categories(array('limit' => 6));
                            if(!empty($find_courses)) {
                    ?>
                    <li class="dropdown">
                        <a class="<?php if(isset($activemenu) && $activemenu == "courses") echo 'active'; ?>" href="#"><?php echo get_languageword('Find Curriculums');?> <span class="caret"></span></a>
                        <ul class="dropdown-menu vertical-megamenu" role="menu">

                            <?php foreach ($find_courses as $row) { ?>
                            <li title="<?php echo $row->name; ?>"><a href="<?php echo URL_HOME_ALL_COURSES.'/'.$row->slug;?>"> <?php echo $row->name; ?></a></li>
                            <?php } ?>
                            <li><a href="<?php echo URL_HOME_ALL_COURSES; ?>"><small class="pull-right"> <?php echo get_languageword('View All');?></small></a></li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if(!($this->ion_auth->is_tutor() || $this->ion_auth->is_institute() || $this->ion_auth->is_admin())) { ?>
                    <!--<li><a class="<?php if(isset($activemenu) && $activemenu == "buy_courses") echo 'active'; ?>" href="<?php echo URL_HOME_BUY_COURSES;?>"> <?php echo get_languageword('Curriculum');?> </a></li>-->
                    <?php } ?>

                    <li class="dropdown">
                        <a class="<?php if(isset($activemenu) && $activemenu == "blog") echo 'active'; ?>" href="#"><?php echo get_languageword('pages');?>  <span class="caret"></span></a>
                        <ul class="dropdown-menu vertical-megamenu" role="menu">
                            <li><a href="<?php echo URL_HOME_ABOUT_US;?>"><?php echo get_languageword('About Us');?></a></li>
                            <li><a href="<?php echo URL_HOME_FAQS;?>"><?php echo get_languageword('FAQs');?></a></li>
                            <li><a href="<?php echo URL_HOME_CONTACT_US;?>"><?php echo get_languageword('Contact Us');?></a></li>

                           <?php 
                           $pages_titles= $this->base_model->get_page_by_title();
                           foreach($pages_titles as $row ){?>
                           <li><a href="<?php echo URL_BLOG_PAGES .'/'.$row->slug;?>"><?php echo $row->name;?></a></li>
                           <?php }?>

                            <?php if(!$this->ion_auth->logged_in()) { ?>
                            <li><a href="<?php echo URL_AUTH_LOGIN;?>"><?php echo get_languageword('Login');?></a></li>
                            <?php } ?>

                        </ul>
                    </li>


                    <?php if (!$this->ion_auth->logged_in()) { ?>
					<li>
                        <a href="<?php echo URL_AUTH_LOGIN;?>"> <span class="nav-btn"> <i class="fa  fa-sign-in"></i> &nbsp; <?php echo get_languageword('Login');?> <span class="hidden-navbtn"><?php echo get_languageword('Or');?> <?php echo get_languageword('Register');?></span></span>
                        </a>
                    </li>
					<?php } else {
						$url = base_url().'tutor/index';
						$ctrl = 'tutor';
						if($this->ion_auth->is_student())
						{
							$url = base_url().'student/index';
							$ctrl = 'student';
						}
                        elseif($this->ion_auth->is_institute())
                        {
                            $url = base_url().'institute/index';
                            $ctrl = 'institute';
                        }
                        elseif($this->ion_auth->is_admin())
                        {
                            $url = base_url().'admin/index';
                            $ctrl = 'admin';
                        }

						?>
                        <?php if(!$this->ion_auth->is_student()) { ?>
                        <li><a class="<?php if(isset($activemenu) && $activemenu == "contact_us") echo 'active'; ?>" href="<?php echo URL_HOME_CONTACT_US;?>"> <?php echo get_languageword('Contact Us');?> </a></li>
                        <?php } else { ?>
                        <style>
                            .nav > li > a {
                                padding: 10px 14px !important;
                            }
                        </style>
                        <?php } ?>
						<li>
                        <a href="<?php echo $url;?>"> <span class="nav-btn"> <i class="fa  fa-dashboard"></i> &nbsp; <?php echo get_languageword('My Dashboard');?></span>
                        </a>
						</li>
						<li>
                        <a href="<?php echo URL_AUTH_LOGOUT;?>"> <span class="nav-btn"> <i class="fa fa-sign-out"></i> &nbsp; <?php echo get_languageword('Logout');?></span>
                        </a>
						</li>
						<?php
					} ?>

					<?php if(isset($this->config->item('site_settings')->land_line) && $this->config->item('site_settings')->land_line != '') { ?>
                    <li class="visible-xs"><a href="javascript:void(0);"><i class="fa fa-phone top-bar-icn"></i><?php echo get_languageword('Call');?> : <?php echo $this->config->item('site_settings')->land_line;?></a></li>
					<?php } ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>
    <?php
    if ($this->ion_auth->logged_in()) {
    $user_id = $this->ion_auth->get_user_id();
    if($this->ion_auth->is_student())
						{
                        $student_id = $this->ion_auth->get_user_id();
     $query = "select count(*) initiated_bookings from ".$this->db->dbprefix('bookings')." where student_id = ".$user_id." And status='session_initiated'";
		$student_dashboard_data['initiated_bookings'] = $this->db->query($query)->row()->initiated_bookings;
    $studentNotification = $student_dashboard_data['initiated_bookings'];
    if(isset($studentNotification) && $studentNotification!="" && $studentNotification!="0"){
 ?>
    <div style="width:100%;height:50px;line-height:50px;background-color:#fdf8e4;border-bottom:1px solid #f8f3df;color:red;font-weight:bold;text-align:center">Your tutor initiated a session. Please click <a href="<?php echo base_url();?>enquiries/session_initiated">here</a> to start learning.</div>
    <?php }}else{
    $dateTimeVariable = date("Y-m-d H:i:s");
    $query = "select count(*) approved_bookings from (SELECT STR_TO_DATE(CONCAT(start_date, ' ', time_slot), '%Y-%c-%e %h:%i %p') as course_date FROM ".$this->db->dbprefix('bookings')." where tutor_id=$user_id and status='approved') as table1 where course_date> DATE_SUB('$dateTimeVariable', INTERVAL 60 MINUTE) and course_date< DATE_ADD('$dateTimeVariable', INTERVAL 20 MINUTE)";
    $student_dashboard_data['approved_bookings'] = $this->db->query($query)->row()->approved_bookings;
    $studentNotification = $student_dashboard_data['approved_bookings'];
    if(isset($studentNotification) && $studentNotification!="" && $studentNotification!="0"){
?>
    <div style="width:100%;height:50px;line-height:50px;background-color:#fdf8e4;border-bottom:1px solid #f8f3df;color:red;font-weight:bold;text-align:center">You have sessions that need to be initiated soon. Please click <a href="<?php echo base_url();?>student-enquiries/approved">here</a> to start session.</div>
    <?php }}}?>
       <!-- Ends Navigation -->