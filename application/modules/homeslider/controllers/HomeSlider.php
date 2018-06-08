<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class HomeSlider extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		
		$this->load->model('homeslider_model');
		$this->data['statistics'] = $this->homeslider_model->getHomeSliderStatistics();
		
		$group = array('admin','user');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	function isAdmin()
	{
		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}

	/** Displays the Index Page**/
	function index()
	{		
		$this->isAdmin();
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('homeslider'));
		$crud->set_subject('Home Slider');
		$crud->columns('id','slide_name','status');
		$crud->unset_fields(array('date_updated'));
		$crud->set_field_upload('image','assets/uploads/homeslider_logos');
		$crud->required_fields(array('slide_name', 'image', 'status'));


		$crud->unset_read();
		$output = $crud->render();
		
		$this->data['activemenu'] = 'homeslider';		
		$this->data['activesubmenu'] = 'list_slides';	

		if($crud_state == 'read')
			$crud_state ='View';

		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'add_slide';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('slide');
			$this->data['maintitle'] = get_languageword('home_slider');
			$this->data['maintitle_link'] = URL_HOME_SLIDER_INDEX;
		}
		else
		{
			$this->data['activesubmenu'] = 'list_slides';
			$this->data['pagetitle'] = get_languageword('home_slider');
		}
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}

	
}