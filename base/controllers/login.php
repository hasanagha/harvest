<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    //Constructor
    function __construct() {
        parent::__construct();

        // Check if user is logged in, redirect to Dashboard
        if($this->general->loggedIn()){
            redirect("account");
        }
    }

    // Default method
	public function index()
	{
        $this->load->library('form_validation');

        // Form validation rules
        $this->form_validation->set_rules('txt_account', 'Account', 'required|trim');
        $this->form_validation->set_rules('txt_email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('txt_password', 'Password', 'required|trim');

        $data['message'] = '';

        // If validated
        if ($this->form_validation->run() == TRUE)
        {
            $account    = $this->input->post("txt_account");
            $email      = $this->input->post("txt_email");
            $password   = $this->input->post("txt_password");

            // settings vars for harvest library
            $this->harvest->setVars($account, base64_encode($email . ":" . $password));
            
            // Authenticate user's credentials
            $content = $this->harvest->authenticate();

            if ($content)  //if successfull, save important user's info in sessions. and redirect user to account's page
            {
                $this->general->saveUserDataInSession($content, $account, $email, $password);
                redirect('account');

            } else {
                $data['message'] = 'Authentication failed';
            }
        }

        $this->load->view('login', $data);
	}
		
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */