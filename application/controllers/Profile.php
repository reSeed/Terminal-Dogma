<?php

class Profile extends CI_Controller {

		public function __construct()
        {
                parent::__construct();
                $this->load->helper('url');
        }
        
	public function index()
	{
		
		$this->load->view('profile/profile');
	}
}

?>