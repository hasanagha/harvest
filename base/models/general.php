<?php
class General extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function loggedIn(){
		
		return $this->session->userdata(USER_SESSION_KEY);
		
	}

    // Saving all user's data into session. 
    function saveUserDataInSession($data, $account, $email, $password){

        // We need this for each api call to harvest.
        $encrypted_code = base64_encode($email . ':' . $password);

        $data = json_decode($data);
        $user_data = (array) $data->user;
        $user_data['account'] = $account;
        $user_data['account_harvest_url'] = sprintf('https://%s.harvestapp.com', $account);
        $user_data['encrypted_authentication_code'] = $encrypted_code;
        
        // Saving all clients in session to save multiple api calls.
        // better to save them while logging so we can use them directly from session
        $user_data['clients'] = $this->harvest->getAllClients();

        $this->session->set_userdata(USER_SESSION_KEY, $user_data);

    }

    function deleteUserDataFromSession(){
        $this->session->unset_userdata(USER_SESSION_KEY);
    }
}