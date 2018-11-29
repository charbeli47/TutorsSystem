<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Quiz extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		
		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	
	/** Displays the Index Page**/
	function index()
	{
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('quizquestions'));
		//$crud->where('is_parent',1);
		//$crud->set_subject(get_languageword('category'));
		$crud->columns('question');
		//$crud->add_fields(array('name', 'slug', 'description', 'code', 'sort_order','is_popular', 'status', 'is_parent'));
		//$crud->edit_fields(array('name', 'slug', 'description', 'code', 'sort_order','is_popular', 'status', 'is_parent'));
		//$crud->required_fields(array('name', 'slug', 'code', 'sort_order', 'status'));
		//$crud->unique_fields('name', 'code');
		//$crud->set_field_upload('image','assets/uploads/categories');
		
		//Field Types
		//$crud->field_type('is_popular', 'dropdown', array('1' => get_languageword('yes'), '0' => get_languageword('no')));
		//$crud->field_type('is_parent', 'hidden', 1); //1-category, 0-course
		
		//Rules
		//$crud->set_rules('sort_order',get_languageword('sort_order'),'trim|required|integer');

		//$crud->order_by('id','desc');

		$crud->callback_before_insert(array($this,'callback_cat_before_insert'));
		$crud->callback_before_update(array($this,'callback_cat_before_update'));

		$output = $crud->render();
		
		if($crud_state == 'read')
			$crud_state ='View';

		$this->data['activemenu'] = 'Quiz test';
		
		$this->data['activesubmenu'] = 'list-questions';
		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'quizquestions-add';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('Quizquestions');
			$this->data['maintitle'] = get_languageword('Quizquestions');
			$this->data['maintitle_link'] = URL_QUIZ_INDEX;
		}
		//else
		//{
			//$this->data['activesubmenu'] = 'categories';
			//$this->data['pagetitle'] = get_languageword('categories');
		//}
		
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}


	function callback_cat_before_insert($post_array) {

		$post_array['slug'] = prepare_slug($post_array['slug'], 'slug', 'categories');

		return $post_array;
	}

	function callback_cat_before_update($post_array, $primary_key) {

		$prev_name = $this->base_model->fetch_value('categories', 'slug', array('id' => $primary_key));

		//If updates the name
		if($prev_name != $post_array['slug']) {
			$post_array['slug'] = prepare_slug($post_array['slug'], 'slug', 'categories');
		}
		return $post_array;
	}


	/** Displays the Index Page**/
	function options()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('Please login to access this area'));
			redirect('auth/login');
		}
				
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('questionoptions'));
		//$crud->where('is_parent',0);
		$crud->set_subject('Questions Options');
		$crud->columns('questionid','optiontext', 'correct');
		$crud->add_fields(array('questions','optiontext', 'correct'));
	//	$crud->edit_fields(array('categories','name', 'slug', 'description', 'code', 'sort_order', 'image','video', 'is_popular', 'status', 'is_parent', 'pdf_file'));
		
		//$crud->required_fields(array('name', 'slug', 'code', 'sort_order', 'status'));
		//$crud->unique_fields('name', 'code');
		//$crud->set_field_upload('image','assets/uploads/courses');
		//$crud->set_field_upload('pdf_file','assets/uploads/courses');
		//Field Types
		/* This is not working as expected. We need to work on it*/
		//$crud->field_type('is_parent', 'hidden', '0'); //1-category, 0-course
		//$crud->field_type('description', 'text'); 
		//$crud->field_type('is_popular', 'dropdown', array('1' => get_languageword('yes'), '0' => get_languageword('no')));
		
		$categories = $this->base_model->fetch_records_from('quizquestions',null);
		$categories_arr = array('' => get_languageword('noquestionavailable'));
		if(!empty($categories))
		{
			foreach($categories as $cat)
			{
				$categories_arr[$cat->idquestion] = $cat->question;
			}
		}
		$crud->field_type('questions', 'multiselect', $categories_arr);		
		
		//Rules
		$crud->set_rules('sort_order',get_languageword('sort_order'),'trim|required|integer');
		//$crud->order_by('id','desc');
		$crud->callback_insert(array($this,'course_insert_callback'));
		$crud->callback_update(array($this,'course_update_callback'));
		//$crud->callback_before_insert(array($this,'callback_cat_before_insert'));
		//$crud->callback_before_update(array($this,'callback_cat_before_update'));
		$output = $crud->render();
		
		$this->data['activemenu'] = 'quiz';		
		$this->data['activesubmenu'] = 'add_options';		

		if($crud_state == 'read')
			$crud_state ='View';

		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'add_options';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('option');
			$this->data['maintitle'] = get_languageword('options');
			$this->data['maintitle_link'] = URL_QUIZ_INDEX;
		}
		else
		{
			$this->data['activesubmenu'] = 'courses';
			$this->data['pagetitle'] = get_languageword('courses');
		}
		
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	function course_insert_callback( $post_array )
	{
		//$data = array(
			//'is_parent' => 0,
			//'name' => $post_array['name'],
			//'description' => $post_array['description'],
			//'code'	=> $post_array['code'],
			//'image' => $post_array['image'],
            //'pdf_file' => $post_array['pdf_file'],
			//'is_popular' => $post_array['is_popular'],
			//'slug' => prepare_slug($post_array['slug'], 'slug', 'categories'),
			//'status' => $post_array['status'],
			//'sort_order' => $post_array['sort_order'],
			//'created_at' => date('Y-m-d H:i:s'),
            //'video' =>$post_array['video'],
			//'categories' => implode(',', $post_array['categories']),
		//);

		$data = array(
			'is_parent' => 0,
			'name' => $post_array['name'],
			'description' => $post_array['description'],
			'code'	=> $post_array['code'],
			'image' => $post_array['image'],
            'pdf_file' => $post_array['pdf_file'],
			'is_popular' => $post_array['is_popular'],
			'slug' => prepare_slug($post_array['slug'], 'slug', 'categories'),
			'status' => $post_array['status'],
			'sort_order' => $post_array['sort_order'],
			'created_at' => date('Y-m-d H:i:s'),
            'video' =>$post_array['video'],
			'categories' => implode(',', $post_array['categories']),
		);
		$this->db->insert('categories', $data);
		$insert_id = $this->db->insert_id();
		$this->base_model->delete_record_new($this->db->dbprefix('course_categories'), array('course_id' => $insert_id));
		$categories = $post_array['categories'];
		if(!empty($categories))
		{
			$cats_courses = array();
			foreach($categories as $cat)
			{
				$cats_courses[] = array('course_id' => $insert_id, 'category_id' => $cat);
			}
			if(!empty($cats_courses))
			{
				$this->db->insert_batch('course_categories', $cats_courses);
			}
		}
		return TRUE;
	}
	
	function course_update_callback( $post_array, $primary_key )
	{

		$data = array(
			'is_parent' => 0,
			'name' => $post_array['name'],
			'description' => $post_array['description'],
			'code'	=> $post_array['code'],
			'image' => $post_array['image'],
            'pdf_file' => $post_array['pdf_file'],
			'is_popular' => $post_array['is_popular'],
			'status' => $post_array['status'],
			'sort_order' => $post_array['sort_order'],
			'updated_at' => date('Y-m-d H:i:s'),
            'video' =>$post_array['video'],
			'categories' => implode(',', $post_array['categories']),
		);

		$prev_name = $this->base_model->fetch_value('categories', 'slug', array('id' => $primary_key));

		//If updates the name
		if($prev_name != $post_array['slug']) {
			$data['slug'] = prepare_slug($post_array['slug'], 'slug', 'categories');
		}


		$this->db->update('categories',$data,array('id' => $primary_key));
		
		$this->base_model->delete_record_new($this->db->dbprefix('course_categories'), array('course_id' => $primary_key));
		$categories = $post_array['categories'];
		if(!empty($categories))
		{
			$cats_courses = array();
			foreach($categories as $cat)
			{
				$cats_courses[] = array('course_id' => $primary_key, 'category_id' => $cat);
			}
			if(!empty($cats_courses))
			{
				$this->db->insert_batch('course_categories', $cats_courses);
			}
		}
		return TRUE;
	}
}