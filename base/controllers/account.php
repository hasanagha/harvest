<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
	//Constructor
	function __construct() {
        parent::__construct();

        // Check if user is not logged in, redirect to login page
		if(!$this->general->loggedIn()){
            redirect("login");
        }
    }

	public function index() {
		$data['page_name'] = 'account';
		$this->load->view('base', $data);
	}

	// Method to logout
	public function logout() {
		//model call to delete all user related data from session
		$this->general->deleteUserDataFromSession();

		// send user to the login page
		redirect('login');
	}

}

/* End of file account.php */
/* Location: ./application/controllers/account.php */