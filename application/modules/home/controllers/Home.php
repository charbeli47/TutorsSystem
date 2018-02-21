<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();

		$this->load->model(array('base_model', 'home_model'));
		$this->load->library('Ajax_pagination');
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		
		// SEO		
		$seo_variables = array(
			'__COURSES__' => tutor_get_config( 'global_courses' ),
			'__CATEGORIES__' => tutor_get_config( 'global_categories' ),
			'__LOCATIONS__' => tutor_get_config( 'global_locations' ),
			'__TEACHING_TYPES__' => tutor_get_config( 'global_teaching_types' ),
			);
		$seo = get_seo( 'homepage', $seo_variables );
		if ( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
			$this->data['meta_description'] = $seo['seo_description'];
			$this->data['meta_keywords'] = $seo['seo_keywords'];
		}

	}
	/*** Displays the Index Page**/
	function index()
	{

		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";

		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select_location');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('location_slug'=>$value->slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('type_of_course');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('course_slug'=>$value->slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Recent Added Courses
		$this->data['recent_courses'] = $this->home_model->get_courses(array('order_by' => 'courses.id DESC', 'limit' => 6));


		/* Category-wise Popular Courses - Start */
			$category_limit = 8;
			$course_limit   = 4;
			$this->data['popular_courses'] = $this->home_model->get_popular_courses($category_limit, $course_limit);
		/* Category-wise  Popular Courses - End */

		//Site Testimonials
		$site_testimonials = $this->home_model->get_site_testimonials();
		$this->data['site_testimonials']	= $site_testimonials;
		// Tuotor ratings
		$home_tutor_ratings = $this->home_model->get_home_tutor_ratings();
		$this->data['home_tutor_ratings'] = $home_tutor_ratings;


		//Send App Link Email - Start
		if($this->input->post()) {

			//Form Validations
			$this->form_validation->set_rules('mailid', get_languageword('Email'), 'trim|required|xss_clean|valid_email');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$to = $this->input->post('mailid');

				//Email Alert to User - Start
				//Get Send App Download Link Email Template
				$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '18'));


				if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from = get_system_settings('Portal_Email');
					}

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					} else {

						$sub = get_languageword('Tutor_App_Download_Link');
					}

					if(!empty($email_tpl->template_content)) {

						$msg = $email_tpl->template_content;

					} else {

						$msg = "";
					}

					if(sendEmail($from, $to, $sub, $msg)) {

						$this->prepare_flashmessage(get_languageword('Tutor_App_Download_Link_sent_to_your_email_successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('App not sent due to some technical issue Please enter valid email Thankyou'), 2);
					}

					redirect('/#footer_sec');
					//Email Alert to User - End

				} else {

					$this->prepare_flashmessage(get_languageword('App not available Please contact Admin for any details Thankyou'), 2);
					redirect(URL_HOME_CONTACT_US);
				}

			} else {

				$this->prepare_flashmessage(validation_errors(), 1);
				redirect('/#footer_sec');
			}

		}
		//Send App Link Email - End



		$this->data['activemenu'] 	= "home";		
		$this->data['content'] 		= 'index';
		$this->_render_page('template/site/site-template', $this->data);
	}

    public function get_calendar_courses()
    {
    $timezone = $_REQUEST["timezone"];
    $tz = new DateTimeZone($timezone);  
    $user_id = $_REQUEST["tutor_id"];
    $now = date('Y-m-d H:i:s');
    $results = $this->db->query("select * from pre_tutor_courses where start>'$now' and status=1 and not exists (select null from pre_bookings where status='approved' and pre_tutor_courses.id = pre_bookings.tutor_course_id) and tutor_id=".$user_id)->result();
    //$results = $this->db->where("start > NOW() and id not in(select tutor_course_id from pre_bookings where status='approved')")->get_where('tutor_courses',array('status' => '1','tutor_id'=>$user_id))->result();
        //$results = $this->db->get("tutor_courses")->result();
         $arr = array();
         
         $events = array();
        foreach($results as $row):
        $calevent = array();
        $date = new DateTime($row->start);
        $date->setTimezone($tz);
        $course = $this->db->get_where('categories',array('id' => $row->course_id))->result();
				$calevent["description"] = $row->content;
                $calevent["start"] = $date->format("m/d/y g:i A");
                $calevent["title"] = $course[0]->name;;
                $calevent["id"] = $row->id;
                $arr[] = $calevent;
			endforeach;
            
        //var_dump($arr);exit;
      $events["events"] = $arr; 
        echo json_encode($events);
    }
	function contact_us()
	{

		if($this->input->post()) {

			//Form Validations
			$this->form_validation->set_rules('fname',get_languageword('first_name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('lname',get_languageword('last_name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('sub',get_languageword('subject'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$first_name = $this->input->post('fname');
				$last_name  = $this->input->post('lname');
				$email 		= $this->input->post('email');
				$subjct 	= $this->input->post('sub');
				$msgg 		= $this->input->post('msg');

				//Send conatct query details to Admin Email
				//Email Alert to Admin - Start
				//Get Contact Query Email Template
				$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '16'));

				$from 	= $email;
				$to 	= get_system_settings('Portal_Email');
				$sub 	= get_languageword("Contact Query Received");
				$msg 	= '<p>
									'.get_languageword('Hello Admin, ').',</p>
								<p>
									'.get_languageword('You got contact query Below are the details').'</p>
								<p>
									<strong>'.get_languageword('first_name').':</strong> '.$first_name.'</p>
								<p>
									<strong>'.get_languageword('last_name').':</strong> '.$last_name.'</p>
								<p>
									<strong>'.get_languageword('email').':</strong> '.$email.'</p>
								<p>
									<strong>'.get_languageword('subject').':</strong> '.$subjct.'</p>
								<p>
									<strong>'.get_languageword('message').':</strong> '.$msgg.'</p>
								<p>
									&nbsp;</p>
								';
				$msg 	.= "<p>".get_languageword('Thank you')."</p>";

				if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					}

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					}

					if(!empty($email_tpl->template_content)) {

						$msg = "";
						$original_vars  = array($first_name, $last_name, $email, $subjct, $msgg,);
						$temp_vars		= array('___FIRST_NAME___', '___LAST_NAME___', '___EMAIL___', '___SUBJECT___', '___MESSAGE___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					}

				}

				if(sendEmail($from, $to, $sub, $msg)) {

					$this->prepare_flashmessage(get_languageword('Your contact request sent successfully'), 0);

				} else {

					$this->prepare_flashmessage(get_languageword('Your contact request not sent due to some technical issue Please contact us after some time Thankyou.'), 2);
				}

				redirect(URL_HOME_CONTACT_US);
				//Email Alert to Admin - End

			}

		}

		$this->data['pagetitle']	= get_languageword('contact_us');
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			);
		$seo = get_seo( 'about_us', $seo_variables );
		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
			$this->data['meta_description'] = $seo['seo_description'];
			$this->data['meta_keywords'] = $seo['seo_keywords'];
		}

		$this->data['activemenu'] 	= "contact_us";		
		$this->data['content'] 		= 'contact_us';
		
		$this->_render_page('template/site/site-template', $this->data);
	}
	
	function about_us()
	{

		$this->load->model('base_model');
		$about_us = $this->base_model->get_page_about_us();
		$this->data['about_us'] 	= $about_us;
		
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			);
		$seo = get_seo( 'about_us', $seo_variables );
		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
			$this->data['meta_description'] = $seo['seo_description'];
			$this->data['meta_keywords'] = $seo['seo_keywords'];
		}
	
		$this->data['activemenu'] 	= "blog";		
		$this->data['content'] 		= 'about_us';
		$this->_render_page('template/site/site-template', $this->data);
	}
    function confirm_payment()
	{
    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_student()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		$this->load->model('base_model');
		$bookingId = $this->book_tutor();
		$this->data['activemenu'] 	= "search_tutor";		
		$this->data['content'] 		= 'confirm_payment';
		$this->_render_page('template/site/site-template', $this->data);
	}
    function book_tutor()
	{
		if (!$this->ion_auth->logged_in()) {
			$this->prepare_flashmessage(get_languageword('please_login_to_book_tutor'), 2);
			redirect('auth/login', 'refresh');
		}

		if(!$this->ion_auth->is_student()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth', 'refresh');
		}


		if(!$this->input->post()) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_TUTOR, 'refresh');
		}

        
		$student_id 		= $this->ion_auth->get_user_id();
		$tutor_id   		= $this->input->post('tutor_id');
		$tutor_slug   		= $this->input->post('tutor_slug');
		$course_slug		= $this->input->post('course_slug');
        
        $reference_number   = $this->input->post('reference_number');
	


		//Check Whether student is premium user or not
		if(!is_premium($student_id)) {

			$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_book_tutor'), 2);
			redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
		}

		$course_details = $this->home_model->get_tutor_course_details_byid($course_slug, $tutor_id);

		//Check whether Tutor teaches the course or not
		if(empty($course_details)) {

			$this->prepare_flashmessage(get_languageword('no_course_details_found'), 2);
			redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug, 'refresh');
		}

		$course_id 				= $course_details->course_id;
		//$fee 					= $course_details->fee;
		$tutor_rec 	 = getUserRec($tutor_id);
		$fee 					= $tutor_rec ->fee;

		//Check If student has sufficient credits to book tutor
		if ( $fee > 0 ) { // If the course is paid only we need to check for credits!!
			if(!is_eligible_to_make_booking($student_id, $fee)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_book_the_tutor_Please_get_required_credits_here"), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}
		}

		$start_date  			= date('Y-m-d', strtotime($this->input->post('start_date')));
		$time_slot   			= $this->input->post('time_slot_hidden');

		/// khaline jarebbb ekheddoun men hon sah sah  100 bel 100////
		
	
		
	//Check If student already booked the tutor on the same slot and it is not yet approved by tutor
		if($this->home_model->is_already_booked_the_tutor($student_id, $tutor_id, $course_id, $start_date, $time_slot)) {

			$this->prepare_flashmessage(get_languageword('you_already_booked_this_tutor_and_your_course_not_yet_completed'), 2);
			redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug, 'refresh');
		}

		//Check If selected time-slot is available
		/*if(empty($course_details->time_slots) || !$this->home_model->is_time_slot_avail($tutor_id, $course_id, $start_date, $time_slot)) {

			$this->prepare_flashmessage(get_languageword('time_slot_not_available'), 2);
			redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug, 'refresh');
		}*/


		$content 				= $course_details->content;
		$duration_value 		= $course_details->duration_value;
		$duration_type 			= $course_details->duration_type;
		$per_credit_value 		= $course_details->per_credit_value;
		//$days_off 				= $course_details->days_off;

		$preferred_location 	= ($this->input->post('teaching_type') == "willing-to-travel") ? $this->input->post('location_slug') : $this->input->post('teaching_type');
		$message   				= $this->input->post('message');

		if($duration_type == "hours") {

			$formatted  = str_replace(':', '.', $time_slot);
			$time 	    = explode('-', str_replace(' ', '', $formatted));

			$start_time = number_format($time[0],2);
			$end_time   = number_format($time[1],2);

			$total_time = $end_time - $start_time;

			if($total_time >= 1) {

				$days = round($duration_value / $total_time);

			} else {

				$total_time = (int)(explode('.', number_format($total_time,2))[1]);
				$days = round($duration_value / ($total_time/60));
			}

			$end_date = date("Y-m-d", strtotime($start_date.'+'.$days.' days'));

		} else {

			$end_date = date("Y-m-d", strtotime($start_date.'+'.$duration_value.' '.$duration_type));
		}

		$end_date = date("Y-m-d", strtotime($end_date.'-1 days'));

		$admin_commission   	= get_system_settings('admin_commission_for_a_booking');
		$admin_commission_val   = round($fee * ($admin_commission / 100));

		$created_at   		= date('Y-m-d H:i:s');
		$updated_at   		= $created_at;
		$updated_by   		= $student_id;


		$inputdata	=	array(
								'student_id'			=> $student_id,
                                'reference_number'      => $reference_number,
								'tutor_id'				=> $tutor_id,
								'course_id'				=> $course_id,
								'content'				=> $content,
								'duration_value'		=> $duration_value,
								'duration_type'			=> $duration_type,
								'fee'					=> $fee,
								'per_credit_value'		=> $per_credit_value,
								'start_date'			=> $start_date,
								'end_date'				=> $end_date,
								'time_slot'				=> $time_slot,
								//'days_off'				=> $days_off,
								'preferred_location'	=> $preferred_location,
								'message'				=> $message,
								'admin_commission'		=> $admin_commission,
								'admin_commission_val'	=> $admin_commission_val,
								'created_at'			=> $created_at,
								'updated_at'			=> $updated_at,
								'updated_by'			=> $updated_by,
                                'tutor_course_id'       => $course_slug
							);

		$ref = $this->base_model->insert_operation($inputdata, 'bookings');
        $email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '5'));
                if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];

					$student_rec = getUserRec($student_id);
					$tutor_rec 	 = getUserRec($tutor_id);


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from 	= get_system_settings('Portal_Email');
					}

					$to 	= $from;

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject." before payment";

					} else {

						$sub = get_languageword("Booking Request From Student  before payment");
					}

					if(!empty($email_tpl->template_content)) {
                    $course_category = $this->db->get_where('course_categories',array('id' => $course_id))->row();
                    $course = $this->db->get_where('categories',array('id' => $course_category->category_id))->row();
                    
                    $course_name = $course->name;//$this->base_model->fetch_value('categories', 'name', array('id' => $booking_det->course_id));
						$original_vars  = array($tutor_rec->username, $student_rec->username, $course_name, $start_date." & ".$time_slot, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
						$temp_vars		= array('___TUTOR_NAME___', '___STUDENT_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOGINLINK___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					} else {

						$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'> ".get_languageword('Login Here')."</a> ".get_languageword('to view the booking details');
						$msg .= "<p>".get_languageword('Thank you')."</p>";
					}

					sendEmail($from, $to, $sub, $msg);
                    
				}
		if($ref > 0) {

			//Log Credits transaction data & update user net credits - Start
			$log_data = array(
							'user_id' => $student_id,
							'credits' => $fee,
							'per_credit_value' => $per_credit_value,
							'action'  => 'debited',
							'purpose' => 'Slot booked with the Tutor "'.$tutor_slug.'" and Booking Id is '.$ref,
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'bookings',
							'reference_id' => $ref,
						);
            
			log_user_credits_transaction($log_data);

			update_user_credits($student_id, $fee, 'debit');
            
			//Log Credits transaction data & update user net credits - End


			//Email Alert to Tutor - Start
				//Get Tutor Booking Success Email Template
				//$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '5'));

				/*if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];

					$student_rec = getUserRec($student_id);
					$tutor_rec 	 = getUserRec($tutor_id);


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from 	= get_system_settings('Portal_Email');
					}

					$to 	= $tutor_rec->email;

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					} else {

						$sub = get_languageword("Booking Request From Student");
					}

					if(!empty($email_tpl->template_content)) {

						$original_vars  = array($tutor_rec->username, $student_rec->username, $course_slug, $start_date." & ".$time_slot, $preferred_location, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
						$temp_vars		= array('___TUTOR_NAME___', '___STUDENT_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOCATION___', '___LOGINLINK___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					} else {

						$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'> ".get_languageword('Login Here')."</a> ".get_languageword('to view the booking details');
						$msg .= "<p>".get_languageword('Thank you')."</p>";
					}

					sendEmail($from, $to, $sub, $msg);
				}*/
			//Email Alert to Tutor - End

			//$this->prepare_flashmessage(get_languageword('your_slot_with_the_tutor_booked_successfully_Once_tutor_approved_your_booking and_initiated_the_session_you_can_start_the_course_on_the_booked_date'), 0);
			
			//redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug);
			
				return $ref;

		} else {

			$this->prepare_flashmessage(get_languageword('your_slot_with_the_tutor_not_booked_you_can_send_message_to_the_tutor'), 2);
			redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug);
		}


	}
    function ChangeStatus()
    {
        if (!$this->ion_auth->logged_in()) {
			echo "failure";
		}

		if(!$this->ion_auth->is_student()) {
			echo "failure";
		}
        else{
            $bookingId = $_REQUEST["bookingId"];
            $inputdata = array();
            $inputdata['status'] = 'running';
            $this->base_model->update_operation($inputdata, 'bookings', array('booking_id' => $bookingId));
            echo "success";
        }
    }
    function receipt()
    {
        
        foreach($_REQUEST as $name => $value) {
            $params[$name] = $value;
        }
        if (strcmp($params["signature"], sign($params))==0) {
        if(strtolower($_REQUEST["reason_code"])=="100")
                {
            //payment succeeded
            $reference_number = $params["req_reference_number"];
            $booking_det = $this->base_model->fetch_records_from('bookings', array('reference_number' => $reference_number));
            if(!empty($booking_det)) {
                $booking_det = $booking_det[0];
                $booking_det->status = 'approved';
                $inputdata = array();	
                $inputdata['status'] = $booking_det->status;
                $student_id 		= $this->ion_auth->get_user_id();
                
                $tutor_id = $booking_det->tutor_id; 
                $this->base_model->update_operation($inputdata, 'bookings', array('reference_number' => $reference_number));
                $email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '5'));
                if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];

					$student_rec = getUserRec($student_id);
					$tutor_rec 	 = getUserRec($tutor_id);


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from 	= get_system_settings('Portal_Email');
					}

					$to 	= $tutor_rec->email;

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					} else {

						$sub = get_languageword("Booking Request From Student");
					}

					if(!empty($email_tpl->template_content)) {
                    $course_category = $this->db->get_where('course_categories',array('id' => $booking_det->course_id))->row();
                    $course = $this->db->get_where('categories',array('id' => $course_category->category_id))->row();
                    
                    $course_name = $course->name;//$this->base_model->fetch_value('categories', 'name', array('id' => $booking_det->course_id));
						$original_vars  = array($tutor_rec->username, $student_rec->username, $course_name, $booking_det->start_date." & ".$booking_det->time_slot, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
						$temp_vars		= array('___TUTOR_NAME___', '___STUDENT_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOGINLINK___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					} else {

						$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'> ".get_languageword('Login Here')."</a> ".get_languageword('to view the booking details');
						$msg .= "<p>".get_languageword('Thank you')."</p>";
					}

					sendEmail($from, $to, $sub, $msg);
                    
				}
                //send to student
                $email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '19'));
                if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];

					$student_rec = getUserRec($student_id);
					$tutor_rec 	 = getUserRec($tutor_id);


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from 	= get_system_settings('Portal_Email');
					}

					$to 	= $student_rec->email;

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					} else {

						$sub = get_languageword("Thank you for your booking with us.");
					}

					if(!empty($email_tpl->template_content)) {
                    $course_category = $this->db->get_where('course_categories',array('id' => $booking_det->course_id))->row();
                    $course = $this->db->get_where('categories',array('id' => $course_category->category_id))->row();
                    
                    $course_name = $course->name;//$this->base_model->fetch_value('categories', 'name', array('id' => $booking_det->course_id));
						$original_vars  = array($tutor_rec->username, $student_rec->username, $course_name, $booking_det->start_date." & ".$booking_det->time_slot, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
						$temp_vars		= array('___TUTOR_NAME___', '___STUDENT_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOGINLINK___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					} else {

						$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'> ".get_languageword('Login Here')."</a> ".get_languageword('to view the booking details');
						$msg .= "<p>".get_languageword('Thank you')."</p>";
					}

					sendEmail($from, $to, $sub, $msg);
                    
				}
                $this->prepare_flashmessage(get_languageword('your_slot_with_the_tutor_booked_successfully_and_have_been_approved. You_can_start_the_course_on_the_booked_date'), 0);
			
			redirect(URL_STUDENT_ENQUIRIES);
            }
            }
            else if(strtolower($_REQUEST["reason_code"])=="481")
            {
            //payment succeeded
            $reference_number = $params["req_reference_number"];
            $booking_det = $this->base_model->fetch_records_from('bookings', array('reference_number' => $reference_number));
            if(!empty($booking_det)) {
                $booking_det = $booking_det[0];
                $booking_det->status = 'approved';
                $inputdata = array();	
                $inputdata['status'] = $booking_det->status;
                $student_id 		= $this->ion_auth->get_user_id();
                $tutor_id = $booking_det->tutor_id; 
                $this->base_model->update_operation($inputdata, 'bookings', array('reference_number' => $reference_number));
                $email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '5'));
                if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];

					$student_rec = getUserRec($student_id);
					$tutor_rec 	 = getUserRec($tutor_id);


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from 	= get_system_settings('Portal_Email');
					}

					$to 	= $tutor_rec->email;

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					} else {

						$sub = get_languageword("Booking Request From Student");
					}

					if(!empty($email_tpl->template_content)) {
                    $course_category = $this->db->get_where('course_categories',array('id' => $booking_det->course_id))->row();
                    $course = $this->db->get_where('categories',array('id' => $course_category->category_id))->row();
                    
                    $course_name = $course->name;//$this->base_model->fetch_value('categories', 'name', array('id' => $booking_det->course_id));
						$original_vars  = array($tutor_rec->username, $student_rec->username, $course_name, $booking_det->start_date." & ".$booking_det->time_slot, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
						$temp_vars		= array('___TUTOR_NAME___', '___STUDENT_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOGINLINK___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					} else {

						$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'> ".get_languageword('Login Here')."</a> ".get_languageword('to view the booking details');
						$msg .= "<p>".get_languageword('Thank you')."</p>";
					}

					sendEmail($from, $to, $sub, $msg);
                    sendEmail($from, "info@maizonpub.com", "Odemy.net Payment should be setteled with Subject:" + $sub, $msg);
				}
                $this->prepare_flashmessage(get_languageword('your_slot_with_the_tutor_booked_successfully_and_have_been_approved. You_can_start_the_course_on_the_booked_date'), 0);
			
			redirect(URL_STUDENT_ENQUIRIES);
            }
            }
            else
        {
        $reference_number = $params["req_reference_number"];
            $booking_det = $this->base_model->fetch_records_from('bookings', array('reference_number' => $reference_number));
            if(!empty($booking_det)) {
                $booking_det = $booking_det[0];
                $booking_det->status = 'pending';
                $inputdata = array();	
                $inputdata['status'] = $booking_det->status;
                $this->base_model->update_operation($inputdata, 'bookings', array('reference_number' => $reference_number));
                $this->prepare_flashmessage(get_languageword('Payment_unsuccessfull, Please_try_again_later'), 1);
			
			    redirect(URL_STUDENT_ENQUIRIES);
            }
        }
        }
        else
        {
        $reference_number = $params["req_reference_number"];
            $booking_det = $this->base_model->fetch_records_from('bookings', array('reference_number' => $reference_number));
            if(!empty($booking_det)) {
                $booking_det = $booking_det[0];
                $booking_det->status = 'pending';
                $inputdata = array();	
                $inputdata['status'] = $booking_det->status;
                $this->base_model->update_operation($inputdata, 'bookings', array('reference_number' => $reference_number));
                $this->prepare_flashmessage(get_languageword('Payment_unsuccessfull, Please_try_again_later'), 1);
			
			    redirect(URL_STUDENT_ENQUIRIES);
            }
        }
    }
	function faqs()
	{
		$faqs = $this->home_model->get_faqs();
		$this->data['faqs']	=  $faqs;
		
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			);
		$seo = get_seo( 'faqs', $seo_variables );
		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
			$this->data['meta_description'] = $seo['seo_description'];
			$this->data['meta_keywords'] = $seo['seo_keywords'];
		}

		$this->data['activemenu'] 	= "blog";		
		$this->data['content'] 		= 'faqs';
		$this->_render_page('template/site/site-template', $this->data);
	}


	function terms_and_conditions()
	{

		$this->load->model('base_model');
		$terms_and_conditions = $this->base_model->get_page_terms_and_conditions();
		$this->data['pageTermsAndCondtions'] 	= $terms_and_conditions;

		$privacy_and_policy= $this->base_model->get_page_privacy_and_policy();
		$this->data['privacy_and_policy'] = $privacy_and_policy;
	
		$this->data['activemenu'] 	= "terms_conditions";		
		$this->data['content'] 		= 'terms_conditions';
		$this->_render_page('template/site/site-template', $this->data);
	}

    function course($slug)
    {
        $slug = str_replace('_', '-', $slug);
        $this->data['course'] 	  = $this->home_model->get_course($slug);
        $seo_variables = array(
				'__COURSES__' => tutor_get_config('global_courses'),
				'__CATEGORIES__' => tutor_get_config('global_categories'),
				'__LOCATIONS__' => tutor_get_config('global_locations'),
				'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),				
				'__COURSE_NAME__' => $slug,
			);
			
			$seo = get_seo( 'courses_single', $seo_variables );
			if( ! empty( $seo ) ) {
				$this->data['pagetitle'] = $seo['seo_title'];
				$this->data['meta_description'] = $seo['seo_description'];
				$this->data['meta_keywords'] = $seo['seo_keywords'];
			}
            $this->data['activemenu'] 	 = "courses";
            $this->data['content'] 		 = 'course';
		$this->_render_page('template/site/site-template', $this->data);
    }

	/*** Displays All Courses **/
	function all_courses($category_slug = '')
	{

		$category_slug = str_replace('_', '-', $category_slug);

		$this->data['categories'] = get_categories();
		$params = array(
							'limit' 		=> LIMIT_COURSE_LIST, 
							'category_slug' => $category_slug
						);
		$this->data['courses'] 	  = $this->home_model->get_courses($params);



		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_courses($params));


		$active_cat = 'all_courses';
		$heading1   = get_languageword('all_courses').' ('.$total_records.')';

		if(!empty($category_slug)) {

			$active_cat = $category_slug;
			$heading1	= get_languageword('courses_in').' '.$this->home_model->get_categoryname_by_slug($category_slug).' ('.$total_records.')';
		}


		$this->data['total_records'] = $total_records;
		$this->data['active_cat']	 = (!empty($category_slug)) ? $category_slug : "all_courses";
		$this->data['category_slug'] = $category_slug;
		
		// SEO
		$courses = $categories = array();
		if( ! empty( $this->data['courses'] ) ) {				
			foreach( $this->data['courses'] as $course ) {
				$courses[] = $course->name;
			}
		}
		
		if( ! empty( $this->data['categories'] ) ) {				
			foreach( $this->data['categories'] as $category ) {
				$categories[] = $category->name;
			}
		}
		if( ! empty( $category_slug ) ) {
			$seo_variables = array(
				'__COURSES__' => tutor_get_config('global_courses'),
				'__CATEGORIES__' => tutor_get_config('global_categories'),
				'__LOCATIONS__' => tutor_get_config('global_locations'),
				'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),				
				'__COURSE_NAME__' => $category_slug,
			);
			
			$seo = get_seo( 'courses_single', $seo_variables );
			if( ! empty( $seo ) ) {
				$this->data['pagetitle'] = $seo['seo_title'];
				$this->data['meta_description'] = $seo['seo_description'];
				$this->data['meta_keywords'] = $seo['seo_keywords'];
			}
		} else {
			$seo_variables = array(
				'__COURSES__' => tutor_get_config('global_courses'),
				'__CATEGORIES__' => tutor_get_config('global_categories'),
				'__LOCATIONS__' => tutor_get_config('global_locations'),
				'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			);
			$seo = get_seo( 'courses', $seo_variables );
			if( ! empty( $seo ) ) {
				$this->data['pagetitle'] = $seo['seo_title'];
				$this->data['meta_description'] = $seo['seo_description'];
				$this->data['meta_keywords'] = $seo['seo_keywords'];
			}
		}

		$this->data['activemenu'] 	 = "courses";
		$this->data['heading1'] 	 = $heading1;
		$this->data['content'] 		 = 'all_courses';
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_courses()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$category_slug = str_replace('_', '-', $this->input->post('category_slug'));

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'category_slug' => $category_slug
						);

		$courses  		= $this->home_model->get_courses($params);
		$result 		= $this->load->view('sections/course_section', array('courses' => $courses), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }



    /* SEARCH TUTOR */
    function search_tutor($course_slug = '', $country_slug = '', $teaching_language_slug = '', $teacher_name = '')
	{

		$course_slug = (!empty($course_slug)) ? array($course_slug) : $this->input->post('course_slug');

		$country_slug = (!empty($country_slug)) ? array($country_slug) : $this->input->post('country_slug');

		$teaching_language_slug = (!empty($teaching_language_slug)) ? array($teaching_language_slug) : $this->input->post('teaching_language_slug');
        $teacher_name = $this->input->post('teacher_name');

		/*if(!empty($course_slug[0]) && $course_slug[0] == "by_location")
			$course_slug = '';
		if(!empty($course_slug[0]) && $course_slug[0] == "by_teaching_type") {
			$teaching_type_slug = $location_slug;
			$course_slug   = '';
			$location_slug = '';
		}*/

        $course_slug = str_replace('_', '-', $course_slug);
		$country_slug = str_replace('_', '-', $country_slug);
        $teacher_name = str_replace('_', '-', $teacher_name);
		$teaching_language_slug = str_replace('_', '-', $teaching_language_slug);
        

		$params = array(
							'limit' 	  	=> LIMIT_PROFILES_LIST, 
							'course_slug' 	=> $course_slug, 
							'country_slug' => $country_slug, 
							'teaching_language_slug' => $teaching_language_slug,
                            'teacher_name'=>$teacher_name
						);

		$tutor_list = $this->home_model->get_tutors($params);
		$this->data['tutor_list'] = $tutor_list;
		
			
		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_tutors($params));


		$this->data['total_records'] = $total_records;
		$this->data['course_slug'] 	 = $course_slug;
		$this->data['country_slug'] = $country_slug;
        $this->data['teacher_name'] = $teacher_name;
		$this->data['teaching_language_slug'] = $teaching_language_slug;


		/*** Drop-down Options - Start ***/
		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";
		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('course_slug'=>$value->slug, 'country_slug'=>$country_slug, 'teaching_language_slug'=>$teaching_language_slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;
        $countries = $this->base_model->fetch_records_from('country');
		$country_opts[''] = get_languageword('select_Nationality');
        $country_opts[' '] = get_languageword('all_nationalities');
		if(!empty($countries)) {
			foreach ($countries as $key => $value) {
				$country_opts[$value->nicename] = $value->nicename;
			}
		}
        $this->data['country_opts'] = $country_opts;
        $lng_opts = $this->db->get_where('languages',array('status' => 'Active'))->result();
		$options = array();
        $options[' '] = get_languageword('all_languages');
		if(!empty($lng_opts))
		{
			foreach($lng_opts as $row):
				$options[$row->name] = $row->name;
			endforeach;
		}

		$this->data['lng_opts'] = $options;
		//Location Options
		/*$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('location_slug'=>$value->slug, 'course_slug'=>$course_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;
		*/

		//Teaching type Options
		/*$teaching_types = $this->home_model->get_teaching_types();
		$teaching_type_opts[''] = get_languageword('select');
		foreach ($teaching_types as $key => $value) {
			if($show_records_count_in_search_filters == "Yes") {

				$avail_records_cnt = " (".count($this->home_model->get_tutors(array('teaching_type_slug'=>$value->slug, 'course_slug'=>$course_slug, 'location_slug'=>$location_slug))).")";
			}
			$teaching_type_opts[$value->slug] = $value->teaching_type.$avail_records_cnt;
		}
		$this->data['teaching_type_opts'] = $teaching_type_opts;*/
		/*** Drop-down Options - End ***/
		
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			
			'__COURSES__' => $course_slug, 
			'__LOCATIONS__' => $country_slug, 
			'__TEACHING_TYPES__' => $teaching_language_slug,
		);
		$seo = get_seo( 'findtutor', $seo_variables );
		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
			$this->data['meta_description'] = $seo['seo_description'];
			$this->data['meta_keywords'] = $seo['seo_keywords'];
		}
		
		$this->data['activemenu'] 	= "search_tutor";		
		$this->data['content'] 		= 'search_tutor';
		
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_tutors()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$course_slug  	= ($this->input->post('course_slug')) ? explode(',', $this->input->post('course_slug')) : '';
		$country_slug  = ($this->input->post('country_slug')) ? explode(',', $this->input->post('country_slug')) : '';
		$teaching_language_slug  = ($this->input->post('teaching_language_slug')) ? explode(',', $this->input->post('teaching_language_slug')) : '';
        $teacher_name   		= $this->input->post('teacher_name');

		$course_slug = str_replace('_', '-', $course_slug);
		$country_slug = str_replace('_', '-', $country_slug);
		$teaching_language_slug = str_replace('_', '-', $teaching_language_slug);
        $teacher_name = str_replace('_', '-', $teacher_name);

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'course_slug' 	=> $course_slug, 
							'country_slug' => $country_slug,
                            'teaching_language_slug' => $teaching_language_slug,
                            'teacher_name' => $teacher_name
						);

		$tutor_list  	= $this->home_model->get_tutors($params);
		$result 		= $this->load->view('sections/tutor_list_section', array('tutor_list' => $tutor_list), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }


    /* SEARCH INSTITUTE */
    function search_institute($course_slug = '', $location_slug = '', $teaching_type_slug = '', $inst_slug = '')
	{

		$course_slug = (!empty($course_slug)) ? array($course_slug) : $this->input->post('course_slug');

		$location_slug = (!empty($location_slug)) ? array($location_slug) : $this->input->post('location_slug');

		$teaching_type_slug = (!empty($teaching_type_slug)) ? array($teaching_type_slug) : $this->input->post('teaching_type_slug');

		$inst_slug = (!empty($inst_slug)) ? array($inst_slug) : $this->input->post('inst_slug');


		if(!empty($course_slug[0]) && $course_slug[0] == "by_location")
			$course_slug = '';
		if(!empty($course_slug[0]) && $course_slug[0] == "by_teaching_type") {
			$teaching_type_slug = $location_slug;
			$course_slug   = '';
			$location_slug = '';
		}


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);
		$inst_slug = str_replace('_', '-', $inst_slug);


		$params = array(
							'limit' 	  	=> LIMIT_PROFILES_LIST, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug, 
							'inst_slug' 	=> $inst_slug
						);

		$this->data['institute_list'] = $this->home_model->get_institutes($params);


		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_institutes($params));


		$this->data['total_records'] = $total_records;
		$this->data['course_slug'] 	 = $course_slug;
		$this->data['location_slug'] = $location_slug;
		$this->data['teaching_type_slug'] = $teaching_type_slug;
		$this->data['inst_slug'] = $inst_slug;


		/*** Drop-down Options - Start ***/
		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";
		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_institutes(array('course_slug'=>$value->slug, 'location_slug'=>$location_slug, 'inst_slug'=>$inst_slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_institutes(array('location_slug'=>$value->slug, 'course_slug'=>$course_slug, 'inst_slug'=>$inst_slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Institute Options
		$insts = $this->home_model->get_institutes();
		$inst_opts[''] = get_languageword('select');
		if(!empty($insts)) {
			foreach ($insts as $key => $value) {
				$inst_opts[$value->slug] = $value->username;
			}
		}
		$this->data['inst_opts'] = $inst_opts;

		/*** Drop-down Options - End ***/
		
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			
			'__LOCATIONS__' => $location_slug,
			'__INSTITUTES__' => $inst_slug
			);
		$seo = get_seo( 'findinstitute', $seo_variables );
		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
			$this->data['meta_description'] = $seo['seo_description'];
			$this->data['meta_keywords'] = $seo['seo_keywords'];
		}


		$this->data['activemenu'] 	= "search_institute";		
		$this->data['content'] 		= 'search_institute';
		$this->_render_page('template/site/site-template', $this->data);
	}

	function load_more_institutes()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$course_slug  	= ($this->input->post('course_slug')) ? explode(',', $this->input->post('course_slug')) : '';
		$location_slug  = ($this->input->post('location_slug')) ? explode(',', $this->input->post('location_slug')) : '';
		$teaching_type_slug  = ($this->input->post('teaching_type_slug')) ? explode(',', $this->input->post('teaching_type_slug')) : '';


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$institute_list  = $this->home_model->get_institutes($params);
		$result 		= $this->load->view('sections/institute_list_section', array('institute_list' => $institute_list), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }



    /* SEARCH STUDENT LEADS */
    function search_student_leads($course_slug = '', $location_slug = '', $teaching_type_slug = '')
	{

		$course_slug = (!empty($course_slug)) ? array($course_slug) : $this->input->post('course_slug');

		$location_slug = (!empty($location_slug)) ? array($location_slug) : $this->input->post('location_slug');

		$teaching_type_slug = (!empty($teaching_type_slug)) ? array($teaching_type_slug) : $this->input->post('teaching_type_slug');


		if(!empty($course_slug[0]) && $course_slug[0] == "by_location")
			$course_slug = '';
		if(!empty($course_slug[0]) && $course_slug[0] == "by_teaching_type") {
			$teaching_type_slug = $location_slug;
			$course_slug   = '';
			$location_slug = '';
		}

		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'limit' 	  	=> LIMIT_PROFILES_LIST, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$this->data['student_leads_list'] = $this->home_model->get_student_leads($params);


		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_student_leads($params));


		$this->data['total_records'] = $total_records;
		$this->data['course_slug'] 	 = $course_slug;
		$this->data['location_slug'] = $location_slug;
		$this->data['teaching_type_slug'] = $teaching_type_slug;


		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";

		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_student_leads(array('course_slug'=>$value->slug, 'location_slug'=>$location_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_student_leads(array('location_slug'=>$value->slug, 'course_slug'=>$course_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Teaching type Options
		$teaching_types = $this->home_model->get_teaching_types();
		$teaching_type_opts[''] = get_languageword('select');
		foreach ($teaching_types as $key => $value) {
			if($show_records_count_in_search_filters == "Yes") {

				$avail_records_cnt = " (".count($this->home_model->get_student_leads(array('teaching_type_slug'=>$value->slug, 'course_slug'=>$course_slug, 'location_slug'=>$location_slug))).")";
			}
			$teaching_type_opts[$value->slug] = $value->teaching_type.$avail_records_cnt;
		}
		$this->data['teaching_type_opts'] = $teaching_type_opts;


		$this->data['activemenu'] 	= "search_student_leads";		
		$this->data['content'] 		= 'search_student_leads';

		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_student_leads()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$course_slug  	= ($this->input->post('course_slug')) ? explode(',', $this->input->post('course_slug')) : '';
		$location_slug  = ($this->input->post('location_slug')) ? explode(',', $this->input->post('location_slug')) : '';
		$teaching_type_slug  = ($this->input->post('teaching_type_slug')) ? explode(',', $this->input->post('teaching_type_slug')) : '';


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$student_leads_list  = $this->home_model->get_student_leads($params);
		$result 		= $this->load->view('sections/student_leads_list_section', array('student_leads_list' => $student_leads_list), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }




    //TUTOR PROFILE
    function tutor_profile($tutor_slug = '')
	{
		$tutor_slug = ($this->input->post('tutor_slug')) ? $this->input->post('tutor_slug') : $tutor_slug;

		if(empty($tutor_slug)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_TUTOR);
		}

		$tutor_slug = str_replace('_', '-', $tutor_slug);

		$tutor_details = $this->home_model->get_tutor_profile($tutor_slug);

		if(empty($tutor_details)) {

			$this->prepare_flashmessage(get_languageword('no_details_available'), 2);
			redirect(URL_HOME_SEARCH_TUTOR);
		}

		$this->data['tutor_details'] = $tutor_details;


		//Send Message to Tutor
		if($this->input->post()) {

			if(!$this->ion_auth->logged_in()) {

				$this->prepare_flashmessage(get_languageword('please_login_to_send_message'), 2);
				redirect(URL_AUTH_LOGIN, 'refresh');
			}

			$inputdata['from_user_id'] 	= $this->ion_auth->get_user_id();
			$credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;

			//Check Whether student is premium user or not
			if(!is_premium($inputdata['from_user_id'])) {

				$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_send_message_to_tutor'), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Check If student has sufficient credits to send message to tutor
			if(!is_eligible_to_make_booking($inputdata['from_user_id'], $credits_for_sending_message)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_send_message_to_the_tutor_Please_get_required_credits_here"), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Form Validations
			$this->form_validation->set_rules('name',get_languageword('name'),'trim|required|xss_clean');
			//$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			//$this->form_validation->set_rules('phone',get_languageword('phone'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {


				$course_name = $this->base_model->fetch_value('categories', 'name', array('slug' => $this->input->post('course_slug1')));

				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['course_slug']	= $course_name;
				$inputdata['email'] 		= $this->input->post('email');
				$inputdata['phone'] 		= $this->input->post('phone');
				$inputdata['message'] 		= $this->input->post('msg');

				$to_user_type   = $this->input->post('to_user_type');
				$inputdata['to_user_id']   = $this->input->post('to_user_id');				

				$inputdata['created_at']	= date('Y-m-d H:i:s');
				$inputdata['updated_at']	= $inputdata['created_at'];

				$ref = $this->base_model->insert_operation($inputdata, 'messages');
				if($ref) {

					//Send message details to Tutor Email
					//Email Alert to Tutor - Start
					//Get Send Message Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '17'));

					$tutor_rec = getUserRec($inputdata['to_user_id']);

					$from 	= $inputdata['email'];
					$to 	= $tutor_rec->email;
					$sub 	= get_languageword("Message Received From Student");
					$msg 	= '<p>
										'.get_languageword('Hi ').$tutor_rec->username.',</p>
									<p>
										'.get_languageword('You got a message from Student Below are the details').'</p>
									<p>
										<strong>'.get_languageword('name').':</strong> '.$inputdata['name'].'</p>
									<p>
										<strong>'.get_languageword('email').':</strong> '.$inputdata['email'].'</p>
									<p>
										<strong>'.get_languageword('phone').':</strong> '.$inputdata['phone'].'</p>
									<p>
										<strong>'.get_languageword('course_seeking').':</strong> '.$inputdata['course_slug'].'</p>
									<p>
										<strong>'.get_languageword('message').':</strong> '.$inputdata['message'].'</p>
									<p>
										&nbsp;</p>
									';
					$msg 	.= "<p>".get_languageword('Thank you')."</p>";

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						}

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject.get_languageword(' Student');

						}

						if(!empty($email_tpl->template_content)) {

							$msg = "";
							$original_vars  = array($tutor_rec->username, get_languageword('Student'), $inputdata['name'], $inputdata['email'], $inputdata['phone'], $inputdata['course_slug'], $inputdata['message']);
							$temp_vars		= array('___TO_NAME___','___USER_TYPE___','___NAME___', '___EMAIL___', '___PHONE___', '___COURSE___', '___MESSAGE___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						}

					}

					if(sendEmail($from, $to, $sub, $msg)) {

						//Log Credits transaction data & update user net credits - Start
						$per_credit_value = $this->config->item('site_settings')->per_credit_value;
						$log_data = array(
										'user_id' => $inputdata['from_user_id'],
										'credits' => $credits_for_sending_message,
										'per_credit_value' => $per_credit_value,
										'action'  => 'debited',
										'purpose' => 'For Sending Message To Tutor "'.$tutor_slug.'" ',
										'date_of_action	' => date('Y-m-d H:i:s'),
										'reference_table' => 'messages',
										'reference_id' => $ref,
									);

						log_user_credits_transaction($log_data);

						update_user_credits($inputdata['from_user_id'], $credits_for_sending_message, 'debit');
						//Log Credits transaction data & update user net credits - End


						$this->prepare_flashmessage(get_languageword('Your message sent to Tutor successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('Your message not sent due to some technical issue Please send message after some time Thankyou'), 2);
					}

					redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug);
				}
				//Email Alert to Tutor - End

			}

		}


		//Tutor Course Options
		$tutor_courses = $this->home_model->get_tutor_courses($tutor_slug);
		if(!empty($tutor_courses)) {
			$tutor_course_opts[''] = get_languageword('select');
			foreach ($tutor_courses as $key => $value) {
				$tutor_course_opts[$value->slug] = $value->name;
			}
		} else {
			$tutor_course_opts = "";
		}
		$this->data['tutor_course_opts'] = $tutor_course_opts;


		//Tutor Location Options
		$tutor_locations = $this->home_model->get_tutor_locations($tutor_slug);
		if(!empty($tutor_locations)) {
			$tutor_location_opts[''] = get_languageword('select_location');
			foreach ($tutor_locations as $key => $value) {
				$tutor_location_opts[$value->slug] = $value->location_name;
			}
		} else {
			$tutor_location_opts = "";
		}
		$this->data['tutor_location_opts'] = $tutor_location_opts;
		
		//User Meta Data
		$this->data['meta_description'] = $tutor_details[0]->meta_desc;
		$this->data['meta_keywords'] = $tutor_details[0]->seo_keywords;
		//Tutor Teaching types
		$tutor_teaching_types = $this->home_model->get_tutor_teaching_types($tutor_slug);
		$this->data['tutor_teaching_types'] = $tutor_teaching_types;

		//Tutor Reviews
		$tutor_reviews = $this->home_model->get_tutor_reviews($tutor_slug);
		$this->data['tutor_reviews'] = $tutor_reviews;
		
		//Tutor ratings
		$tutor_rating	= $this->home_model->get_tutor_rating($tutor_slug);
		$this->data['tutor_raing'] = $tutor_rating;
		
		// SEO
		$seo_variables = array(			
			'__CATEGORIES__' => tutor_get_config('global_categories'),			
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			
			'__TUTOR_NAME__' => $tutor_slug, 
			'__COURSES__' => $tutor_course_opts, 
			'__LOCATIONS__' => $tutor_location_opts
			);
		$seo = get_seo( 'tutor_single', array( ) );
		//User Meta Data
		if( $tutor_details[0]->meta_desc != '' ) {
			$this->data['meta_description'] = $tutor_details[0]->meta_desc;
		} else {			
			if( ! empty( $seo ) ) {				
				$this->data['meta_description'] = $seo['seo_description'];
			}
		}
		
		if( $tutor_details[0]->seo_keywords != '' ) {
			$this->data['meta_keywords'] = $tutor_details[0]->seo_keywords;
		} else {
			if( ! empty( $seo ) ) {
				$this->data['meta_keywords'] = $seo['seo_keywords'];
			}
		}

		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
		}
		
		$this->data['activemenu'] 	= "search_tutor";		
		$this->data['content'] 		= 'tutor_profile';
		$this->_render_page('template/site/site-template', $this->data);
	}


	function ajax_get_tutor_course_details()
	{
		$avail_time_slots = array();
		$course_slug = $this->input->post('course_slug');
		$tutor_id = $this->input->post('tutor_id');
		$selected_date = $this->input->post('selected_date');

		if(empty($course_slug) || empty($tutor_id) || empty($selected_date)) {
			echo ''; die();
		}

		$row =  $this->home_model->get_tutor_course_details($course_slug, $tutor_id);

		if(empty($row)) {
			echo NULL; die();
		}

		$tutor_time_slots = explode(',', $row->time_slots);

		$booked_slots = $this->home_model->get_booked_slots($tutor_id, $row->course_id, $selected_date);

		if(!empty($booked_slots)) {

			foreach ($tutor_time_slots as $slot) {
				if(!in_array($slot, $booked_slots))
					$avail_time_slots[] = $slot;
			}

		} else {

			$avail_time_slots = $tutor_time_slots;
		}

		if(!empty($row))
            echo $row->fee."~".$row->duration_value." ".$row->duration_type."~".$row->content."~".implode(',', $avail_time_slots);
        	//echo $row->fee."~".$row->duration_value." ".$row->duration_type."~".$row->content."~".implode(',', $avail_time_slots)."~".$row->days_off;

	}



	//INSTITUTE PROFILE
    function institute_profile($inst_slug = '')
	{
		$inst_slug = ($this->input->post('inst_slug')) ? $this->input->post('inst_slug') : $inst_slug;

		if(empty($inst_slug)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_INSTITUTE);
		}

		$inst_slug = str_replace('_', '-', $inst_slug);


		$inst_details = $this->home_model->get_inst_profile($inst_slug);

		if(empty($inst_details)) {

			$this->prepare_flashmessage(get_languageword('no_details_available'), 2);
			redirect(URL_HOME_SEARCH_INSTITUTE);
		}


		//Send Message to Institute
		if($this->input->post()) {

			$inputdata['from_user_id'] 	= $this->ion_auth->get_user_id();
			$credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;

			//Check Whether student is premium user or not
			if(!is_premium($inputdata['from_user_id'])) {

				$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_send_message_to_institute'), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Check If student has sufficient credits to send message to institute
			if(!is_eligible_to_make_booking($inputdata['from_user_id'], $credits_for_sending_message)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_send_message_to_the_institute_Please_get_required_credits_here"), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Form Validations
			$this->form_validation->set_rules('name',get_languageword('name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('phone',get_languageword('phone'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$course_name = $this->base_model->fetch_value('categories', 'name', array('slug' => $this->input->post('course_slug1')));

				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['course_slug']	= $course_name;
				$inputdata['email'] 		= $this->input->post('email');
				$inputdata['phone'] 		= $this->input->post('phone');
				$inputdata['message'] 		= $this->input->post('msg');

				$to_user_type   = $this->input->post('to_user_type');
				$inputdata['to_user_id']   = $this->input->post('to_user_id');				

				$inputdata['created_at']	= date('Y-m-d H:i:s');
				$inputdata['updated_at']	= $inputdata['created_at'];

				$ref = $this->base_model->insert_operation($inputdata, 'messages');
				if($ref) {

					//Email Alert to Institute - Start
					//Get Send Message Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '17'));

					$inst_rec = getUserRec($inputdata['to_user_id']);

					$from 	= $inputdata['email'];
					$to 	= $inst_rec->email;
					$sub 	= get_languageword("Message Received From Student");
					$msg 	= '<p>
										'.get_languageword('Hi ').$inst_rec->username.',</p>
									<p>
										'.get_languageword('You got a message from Student Below are the details').'</p>
									<p>
										<strong>'.get_languageword('name').':</strong> '.$inputdata['name'].'</p>
									<p>
										<strong>'.get_languageword('email').':</strong> '.$inputdata['email'].'</p>
									<p>
										<strong>'.get_languageword('phone').':</strong> '.$inputdata['phone'].'</p>
									<p>
										<strong>'.get_languageword('course_seeking').':</strong> '.$inputdata['course_slug'].'</p>
									<p>
										<strong>'.get_languageword('message').':</strong> '.$inputdata['message'].'</p>
									<p>
										&nbsp;</p>
									';
					$msg 	.= "<p>".get_languageword('Thank you')."</p>";

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						}

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject.get_languageword(' Student');

						}

						if(!empty($email_tpl->template_content)) {

							$msg = "";
							$original_vars  = array($inst_rec->username, get_languageword('Student'), $inputdata['name'], $inputdata['email'], $inputdata['phone'], $inputdata['course_slug'], $inputdata['message']);
							$temp_vars		= array('___TO_NAME___','___USER_TYPE___','___NAME___', '___EMAIL___', '___PHONE___', '___COURSE___', '___MESSAGE___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						}

					}

					if(sendEmail($from, $to, $sub, $msg)) {

						//Log Credits transaction data & update user net credits - Start
						$per_credit_value = $this->config->item('site_settings')->per_credit_value;
						$log_data = array(
										'user_id' => $inputdata['from_user_id'],
										'credits' => $credits_for_sending_message,
										'per_credit_value' => $per_credit_value,
										'action'  => 'debited',
										'purpose' => 'For Sending Message To Institute "'.$inst_slug.'" ',
										'date_of_action	' => date('Y-m-d H:i:s'),
										'reference_table' => 'messages',
										'reference_id' => $ref,
									);

						log_user_credits_transaction($log_data);

						update_user_credits($inputdata['from_user_id'], $credits_for_sending_message, 'debit');
						//Log Credits transaction data & update user net credits - End


						$this->prepare_flashmessage(get_languageword('Your message sent to Institute successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('Your message not sent due to some technical issue Please send message after some time Thankyou'), 2);
					}

					redirect(URL_HOME_INSTITUTE_PROFILE.'/'.$inst_slug);
				}
				//Email Alert to Institute - End

			}

		}


		$this->data['inst_details'] = $inst_details;
		//Inst meta data
		$this->data['meta_description'] = $inst_details[0]->meta_desc;
		$this->data['meta_keywords'] = $inst_details[0]->seo_keywords;
		
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),			
			'__INSTITUTE_NAME__' => $inst_slug
		);
		$seo = get_seo( 'institute_single', $seo_variables );
		//User Meta Data
		if( $inst_details[0]->meta_desc != '' ) {
			$this->data['meta_description'] = $inst_details[0]->meta_desc;
		} else {			
			if( ! empty( $seo ) ) {				
				$this->data['meta_description'] = $seo['seo_description'];
			}
		}
		
		if( $inst_details[0]->seo_keywords != '' ) {
			$this->data['meta_keywords'] = $inst_details[0]->seo_keywords;
		} else {
			if( ! empty( $seo ) ) {
				$this->data['meta_keywords'] = $seo['seo_keywords'];
			}
		}

		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
		}
		
		$this->data['activemenu'] 	= "search_institute";		
		$this->data['content'] 		= 'institute_profile';
		$this->_render_page('template/site/site-template', $this->data);
	}



	//STUDENT PROFILE
    function student_profile($student_slug = '', $student_lead_id = '')
	{

		if(!$this->ion_auth->logged_in()) {

			$this->prepare_flashmessage(get_languageword('please_login_to_continue'), 2);
			redirect(URL_AUTH_LOGIN);
		}

		if ($this->ion_auth->is_student() || $this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect(URL_AUTH_LOGIN);
		}

		$student_slug = ($this->input->post('student_slug')) ? $this->input->post('student_slug') : $student_slug;

		if(empty($student_slug)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_STUDENT_LEADS);
		}

		$student_slug = str_replace('_', '-', $student_slug);

		$student_lead_id = ($this->input->post('lead_id')) ? $this->input->post('lead_id') : $student_lead_id;

		$stduent_details = $this->home_model->get_student_profile($student_slug,$student_lead_id);

		if(empty($stduent_details)) {

			$this->prepare_flashmessage(get_languageword('no_details_available'), 2);
			redirect(URL_HOME_SEARCH_STUDENT_LEADS);
		}


		//Send Message to Student
		if($this->input->post()) {

			$from_user_type = "";

			if($this->ion_auth->is_tutor())
				$from_user_type = 'tutor';
			else if($this->ion_auth->is_institute())
				$from_user_type = 'institute';

			$inputdata['from_user_id'] 	= $this->ion_auth->get_user_id();
			$credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;

			//Check Whether student is premium user or not
			if(!is_premium($inputdata['from_user_id'])) {

				$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_send_message_to_student'), 2);
				if($from_user_type == "tutor")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else if($from_user_type == "institute")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else
					redirect(URL_AUTH_INDEX);
			}

			//Check If student has sufficient credits to send message to institute
			if(!is_eligible_to_make_booking($inputdata['from_user_id'], $credits_for_sending_message)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_send_message_to_the_student_Please_get_required_credits_here"), 2);
				if($from_user_type == "tutor")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else if($from_user_type == "institute")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else
					redirect(URL_AUTH_INDEX);
			}

			//Form Validations
			$this->form_validation->set_rules('name',get_languageword('name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('phone',get_languageword('phone'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['course_slug']	= $this->input->post('course_slug1');
				$inputdata['email'] 		= $this->input->post('email');
				$inputdata['phone'] 		= $this->input->post('phone');
				$inputdata['message'] 		= $this->input->post('msg');

				$to_user_type   = $this->input->post('to_user_type');
				$inputdata['to_user_id']   = $this->input->post('to_user_id');				

				$inputdata['created_at']	= date('Y-m-d H:i:s');
				$inputdata['updated_at']	= $inputdata['created_at'];

				$ref = $this->base_model->insert_operation($inputdata, 'messages');
				if($ref) {

					//Email Alert to Student - Start
					//Get Send Message Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '17'));

					$student_rec = getUserRec($inputdata['to_user_id']);

					$from 	= $inputdata['email'];
					$to 	= $student_rec->email;
					$sub 	= get_languageword("Message Received From ")." ".get_languageword(ucfirst($from_user_type));
					$msg 	= '<p>
										'.get_languageword('Hi ').$student_rec->username.',</p>
									<p>
										'.get_languageword('You got a message from '.ucfirst($from_user_type).' Below are the details').'</p>
									<p>
										<strong>'.get_languageword('name').':</strong> '.$inputdata['name'].'</p>
									<p>
										<strong>'.get_languageword('email').':</strong> '.$inputdata['email'].'</p>
									<p>
										<strong>'.get_languageword('phone').':</strong> '.$inputdata['phone'].'</p>
									<p>
										<strong>'.get_languageword('message').':</strong> '.$inputdata['message'].'</p>
									<p>
										&nbsp;</p>
									';
					$msg 	.= "<p>".get_languageword('Thank you')."</p>";

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						}

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject." ".get_languageword(ucfirst($from_user_type));

						}

						if(!empty($email_tpl->template_content)) {

							$msg = "";

							$original_vars  = array($student_rec->username, get_languageword(ucfirst($from_user_type)), $inputdata['name'], $inputdata['email'], $inputdata['phone'], $inputdata['course_slug'], $inputdata['message']);
							$temp_vars		= array('___TO_NAME___','___USER_TYPE___','___NAME___', '___EMAIL___', '___PHONE___', '___COURSE___', '___MESSAGE___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						}

					}

					if(sendEmail($from, $to, $sub, $msg)) {

						//Log Credits transaction data & update user net credits - Start
						$per_credit_value = $this->config->item('site_settings')->per_credit_value;
						$log_data = array(
										'user_id' => $inputdata['from_user_id'],
										'credits' => $credits_for_sending_message,
										'per_credit_value' => $per_credit_value,
										'action'  => 'debited',
										'purpose' => 'For Sending Message To Student "'.$student_slug.'" ',
										'date_of_action	' => date('Y-m-d H:i:s'),
										'reference_table' => 'messages',
										'reference_id' => $ref,
									);

						log_user_credits_transaction($log_data);

						update_user_credits($inputdata['from_user_id'], $credits_for_sending_message, 'debit');
						//Log Credits transaction data & update user net credits - End


						$this->prepare_flashmessage(get_languageword('Your message sent to Student successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('Your message not sent due to some technical issue Please send message after some time Thankyou'), 2);
					}

					redirect(URL_VIEW_STUDENT_PROFILE.'/'.$student_slug.'/'.$student_lead_id);
				}
				//Email Alert to Student - End

			}

		}


		$this->data['stduent_details'] = $stduent_details;
		//Student Meta Data
		$this->data['meta_description'] = $stduent_details[0]->meta_desc;
		$this->data['meta_keywords'] = $stduent_details[0]->seo_keywords;
		
		// SEO
		$seo_variables = array(
			'__COURSES__' => tutor_get_config('global_courses'),
			'__CATEGORIES__' => tutor_get_config('global_categories'),
			'__LOCATIONS__' => tutor_get_config('global_locations'),
			'__TEACHING_TYPES__' => tutor_get_config('global_teaching_types'),
			
			'__STUDENT_NAME__' => $student_slug,
		);
		$seo = get_seo( 'student_single', $seo_variables );
		//User Meta Data
		if( $stduent_details[0]->meta_desc != '' ) {
			$this->data['meta_description'] = $stduent_details[0]->meta_desc;
		} else {			
			if( ! empty( $seo ) ) {				
				$this->data['meta_description'] = $seo['seo_description'];
			}
		}
		
		if( $stduent_details[0]->seo_keywords != '' ) {
			$this->data['meta_keywords'] = $stduent_details[0]->seo_keywords;
		} else {
			if( ! empty( $seo ) ) {
				$this->data['meta_keywords'] = $seo['seo_keywords'];
			}
		}

		if( ! empty( $seo ) ) {
			$this->data['pagetitle'] = $seo['seo_title'];
		}


		$this->data['activemenu'] 	= "search_student_leads";
		$this->data['content'] 		= 'student_profile';
		$this->_render_page('template/site/site-template', $this->data);
	}

	function ajax_get_institute_batches()
	{
		$course_id = $this->input->post('course_id');
		$inst_id = $this->input->post('inst_id');
		$this->load->model('institute/institute_model');
		$batches = $this->institute_model->get_batches_by_course($course_id, $inst_id);

		$batch_opts = '';

		if(!empty($batches)) {

			$batch_opts .= '<option value="">'.get_languageword('select_batch').'</option>';

			foreach ($batches as $key => $value) {
				$batch_opts .= '<option value="'.$value->batch_id.'">'.$value->batch_name.'</option>';
			}

		} else {

			$batch_opts = '<option value="">'.get_languageword('no_batches_available').'</option>';
		}

		echo $batch_opts;
	}
	
	function ajax_get_institute_batches_info()
	{
		$course_id = $this->input->post('course_id');
		$inst_id = $this->input->post('inst_id');
		$batch_id = $this->input->post('batch_id');

		
		$batch_status = "";

		$batche_info = $this->home_model->get_institute_batches_info_by_course($course_id, $inst_id,$batch_id);
		$total_enrolled = $this->home_model->total_enrolled_students_in_batch($batch_id);
		$available_slots = "";

			$html = "";
			
			foreach ($batche_info as  $row) {
					
			$available_slots = $row->batch_max_strength - $total_enrolled;

			$today = date('Y-m-d');
			if($row->batch_start_date >= $today)
				$batch_status = get_languageword('not_yet_started');
			else
				$batch_status = get_languageword('running');

			$html.='<div class="dashboard-panel">
					<h2>Batch Details</h2>
						<div class="table-responsive">
                           	<table class="report-table row-border">
                            	<thead>
		                            <tr>
		                              	<th>'.get_languageword('batch_code').'</th>
			                            <th>'.get_languageword('tutor_name').'</th>
			                            <th>'.get_languageword('course_content').'</th>
			                            <th>'.get_languageword('time_slot').'</th>
			                            <th>'.get_languageword('course_offering_location').'</th>
			                            <th>'.get_languageword('batch_start_date').'</th>
			                            <th>'.get_languageword('batch_end_date').'</th>
			                            
			                            <th>'.get_languageword('fee').' ('.get_languageword('in_credits').')</th>
			                            <th>'.get_languageword('batch_max_strength').'</th>
			                            <th>'.get_languageword('slots_available').'</th>
			                            <th>'.get_languageword('batch_status').'</th>
			                        </tr>
                            	</thead>
                           		<tbody>
		                            <tr>

		                                <td>'.$row->batch_code.'</td>
		                                <td>'.$row->tutorname.'</td>
		                                <td><div class="message more">'.strip_tags($row->course_content).'</div></td>
		                                <td>'.$row->time_slot.'</td>
		                                <td>'.$row->course_offering_location.'</td>
		                                <td>'.$row->batch_start_date.'</td>
		                                <td>'.$row->batch_end_date.'</td>
		                                 
		                                <td>'.$row->fee.'</td>
		                                <td>'.$row->batch_max_strength.'</td>
		                                <td>'.$available_slots.'</td>
		                                <td>'.$batch_status.'</td>

		                            </tr>
	                        	</tbody>
                        	</table>
                		</div>
                    </div>';

				}	
		
		echo $html;

		
	}




	/*** Displays All Selling Courses **/
	function buy_courses()
	{

		$params = array(
							'limit' 		=> LIMIT_COURSE_LIST
						);
		$this->data['selling_courses'] 	  = $this->home_model->get_selling_courses($params);


		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_selling_courses($params));

        $total_records = ($total_records > 1) ? $total_records : 0;

		$heading1   = get_languageword('selling_courses').' ('.$total_records.')';

		$this->data['total_records'] = $total_records;

		$this->data['activemenu'] 	 = "buy_courses";
		$this->data['heading1'] 	 = $heading1;
		$this->data['content'] 		 = 'selling_courses';
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_selling_courses()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit
						);

		$selling_courses= $this->home_model->get_selling_courses($params);
		$result 		= $this->load->view('sections/selling_course_section', array('selling_courses' => $selling_courses), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }



	function buy_course($selling_course_slug = "")
	{

		if(empty($selling_course_slug)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}
		$selling_course_slug = str_replace('_', '-', $selling_course_slug);
		$sc_id = $this->base_model->fetch_value('tutor_selling_courses', 'sc_id', array('slug' => $selling_course_slug));

		if(!($sc_id > 0)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}

		$record = get_tutor_sellingcourse_info($sc_id);

		if(empty($record)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
			redirect(URL_HOME_BUY_COURSES);

		}

		$this->data['record'] = $record;


		if($this->ion_auth->logged_in()) {

			$user_id = $this->ion_auth->get_user_id();
			$this->data['is_purchased'] = $this->base_model->get_query_row("SELECT max_downloads FROM ".TBL_PREFIX."course_purchases WHERE sc_id=".$sc_id." AND user_id=".$user_id." ORDER BY max_downloads DESC LIMIT 1 ");
		}


		//More From this Tutor
		$params = array(
							'limit' 		=> 4,
							'tutor_slug'	=> $record->tutor_id
						);
		$this->data['more_selling_courses'] = $this->home_model->get_selling_courses($params);


		$this->data['activemenu'] 	= "buy_courses";
		$this->data['content'] 		= 'buy_course';
		$this->data['pagetitle'] 	= get_languageword('buy_course');
		$this->_render_page('template/site/site-template', $this->data);
	}



	function checkout($selling_course_slug = "", $payment_gateway = "")
	{

		if(empty($selling_course_slug)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}

		$selling_course_slug = str_replace('_', '-', $selling_course_slug);

		$sc_id = $this->base_model->fetch_value('tutor_selling_courses', 'sc_id', array('slug' => $selling_course_slug));

		if(!($sc_id > 0)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}


		if(!$this->ion_auth->logged_in()) {

			$this->session->set_userdata('req_from', 'buy_course');
			$this->session->set_userdata('selling_course_slug', $selling_course_slug);
			$this->prepare_flashmessage(get_languageword('please_login_to_continue'), 2);
			redirect(URL_AUTH_LOGIN);
		}


		$record = get_tutor_sellingcourse_info($sc_id);

		if(empty($record)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
			redirect(URL_HOME_BUY_COURSES);

		}


		if(!empty($payment_gateway)) {

			$gateway_details = $this->session->userdata('gateway_details');

			$user_info = $this->base_model->get_user_details( $this->ion_auth->get_user_id() );
			$user_info = $user_info[0];
			$this->data['user_info'] = $user_info;

			$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $payment_gateway))->result();

			$razorpay_key_id 			= 'rzp_test_tjwMzd8bqhZkMr';
			$razorpay_key_secret 		= 'EWI9VQiMH43p6LDCbpsgvvHZ';
			$razorpay_payment_action 	= 'capture';
			$razorpay_mode 				= 'sandbox';

			foreach($field_values as $value) {
				if( $value->field_key == 'razorpay_key_id' ) {
					$razorpay_key_id = $value->field_output_value;
				}
				if( $value->field_key == 'razorpay_key_secret' ) {
					$razorpay_key_secret = $value->field_output_value;
				}
				if( $value->field_key == 'razorpay_payment_action' ) {
					$razorpay_payment_action = $value->field_output_value;
				}
				if( $value->field_key == 'razorpay_mode' ) {
					$razorpay_mode = $value->field_output_value;
				}
			}

			$course_name  = $record->course_name;
			$course_title = $record->course_title;
			$total_amount = $record->course_price;


			$config = array(
							'razorpay_key_id' 			=> $razorpay_key_id,
							'razorpay_key_secret' 		=> $razorpay_key_secret,
							'razorpay_payment_action' 	=> $razorpay_payment_action,
							'razorpay_mode' 			=> $razorpay_mode,
							'total_amount' 				=> $total_amount * 100, //As Razorpay accepts amount in paise

							'product_name' 				=> $course_name,
							'product_desc' 				=> $course_title,

							'firstname' 				=> $user_info->first_name,
							'lastname' 					=> $user_info->last_name,
							'email' 					=> $user_info->email,
							'phone' 					=> $user_info->phone,

							'success_url' 	=> base_url() . 'pay/payment_success',
							'cancel_url' 	=> base_url() . 'pay/payment_cancel',
							'failed_url' 	=> base_url() . 'pay/payment_success',
						);

			$site_logo = get_system_settings('Logo');

			if($site_logo != '' && file_exists(URL_PUBLIC_UPLOADS.'settings/thumbs/'.$site_logo)) {
				$config['image'] = URL_PUBLIC_UPLOADS2.'settings/thumbs/'.$site_logo;
			}

			$this->data['razorpay'] = $config;

			$content 	= 'checkout_razorpay';

			$pagetitle 	= get_languageword('checkout_with_Razorpay');

		} else {

			$gateway_details = $this->base_model->get_payment_gateways('', 'Active');

			$content 	= 'checkout';

			$pagetitle 	= get_languageword('checkout');
		}



		$this->data['record'] = $record;
		$this->data['payment_gateways'] = $gateway_details;


		$this->data['activemenu'] 	= "buy_courses";
		$this->data['content'] 		= $content;
		$this->data['pagetitle'] 	= $pagetitle;
		$this->_render_page('template/site/site-template', $this->data);
	}











}
?>