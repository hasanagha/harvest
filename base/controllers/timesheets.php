<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timesheets extends CI_Controller {

	// Constructor
	function __construct() {
        parent::__construct();

        // Check if user is not logged in, redirect to login page
		if(!$this->general->loggedIn()){
            redirect("login");
        }

        // settings vars for harvest library
        $this->harvest->setVars(getUserAccount(), getUserEncAuthCode());
    }

	public function index() {
		$timesheets = $this->harvest->getAllTimesheets();

		$data['clients'] = json_decode(getUserClients());
		$data['timesheets'] = json_decode($timesheets);
		$data['projects'] = json_decode($this->harvest->getAllProjects());
		$data['page_name'] = 'timesheets';

		$this->load->view('base', $data);
	}

	// Method to add or update a project
	public function addedit() {

		$timesheet_id = $this->input->post('timesheet_id');

    	$post_data = array(
        	'project_id' => $this->input->post('project_id'),
        	'task_id' => $this->input->post('task_id'),
        	'hours' => $this->input->post('duration'),
        	'spent_at' => $this->input->post('spent_at'),
        	'notes' => $this->input->post('notes'),
        );

    	// Call to harvest library create/update method
        $entry = $this->harvest->createUpdateTimesheet(json_encode($post_data), $timesheet_id);

		if(gettype($entry) == 'string') { // Means error has been thrown from harvest's library
			$data['success'] = FALSE;
			$data['message'] = $entry;
		}else {
			$data['success'] = TRUE;
		}

		//Json response
		echo json_encode($data, TRUE);
	}

	// Get a single entry from harvest
	// @param $entry_id = int(timesheet entry id)
	public function get($entry_id="") {

		$response['success'] = FALSE;

		if($entry_id && is_numeric($entry_id)) {
				
			$entry = $this->harvest->getTimesheet($entry_id);

			if ($entry) {
				$response = array(
					'success'=>TRUE,
					'response'=>json_decode($entry) // to keep json format clean
				);
			}

		}

		echo json_encode($response, true);
	}

	// To delete an entry by providing its id
	// @param $entry_id = int(timesheet entry id)
	public function delete($entry_id="") {

		$response['success'] = FALSE;

		if($entry_id && is_numeric($entry_id)) {
				
			$entry = $this->harvest->deleteTimesheetEntry($entry_id);

			if ($entry) {
				$response = array(
					'success'=>TRUE
				);
			}
		}

		echo json_encode($response, true);
	}

	// To toggle timesheet's entry timer
	// @param $entry_id = int(timesheet entry id)
	public function timer($entry_id="") {

		$response['success'] = FALSE;

		if($entry_id && is_numeric($entry_id)) {
				
			$entry = $this->harvest->toggleTimesheetEntry($entry_id);

			if ($entry) {
				$response = array(
					'success'=>TRUE
				);
			}
		}

		echo json_encode($response, true);
	}
		
}

/* End of file timesheets.php */
/* Location: ./application/controllers/timesheets.php */