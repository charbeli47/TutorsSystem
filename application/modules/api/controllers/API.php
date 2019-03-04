<?php
//require(APPPATH'.libraries/REST_Controller.php');


class API extends REST_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
	}
	function me_get()
    {
        if($this->ion_auth->logged_in())
		{
			$userID = intval($this->ion_auth->get_user_id());
			$this->response($userID, 200);
		}
		else
		{
			$this->response(0, 200);
		}
    }
	function user_get()
    {
		if(!$this->get('token'))
        {
            $this->response(NULL, 404);
        }
		else
		{
			if($this->ion_auth->logged_in())
			{
				$userID = intval($this->ion_auth->get_user_id());
				$token = $this->get('token');
				$data = array(
					'push_token' => $token
				);
				$this->db->update('users',$data,array('id' => $userID));
				$this->response($userID, 200);
			}
			else
			{
				$this->response(NULL, 400);
			}
		}
	}
	
}