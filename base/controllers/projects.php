<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

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

		// Call harvest library to get all projects
		$projects = $this->harvest->getAllProjects();

		// get clients from session data.
		$data['clients'] = json_decode(getUserClients());
		$data['projects'] = json_decode($projects);
		$data['page_name'] = 'projects';

		$this->load->view('base', $data);
	}

	// Method to add or update a project
	public function addedit() {

		$project_id = $this->input->post('project_id');

		// Collecting post data, making harvest's acceptable format.
    	$post_data = array('project' => array(
            	'client_id' => $this->input->post('client_id'),
            	'name' => $this->input->post('project_name'),
            	'Active' => $this->input->post('active')?TRUE:FALSE,
            	'Bill-By' => 'None',
            	'code' => $this->input->post('project_code'),
            	'starts on' => $this->input->post('starts_on'),
            	'ends on' => $this->input->post('ends_on'),
            	'notes' => $this->input->post('notes'),
            )
        );

    	// Call to harvest library's create/update method
        $project = $this->harvest->createUpdateProject(json_encode($post_data), $project_id);

		if(gettype($project) == 'string') { // Means error has been thrown from harvest's library
			$data['success'] = FALSE;
			$data['message'] = $project;
		}else {
			$data['success'] = TRUE;
		}

		//Json response
		echo json_encode($data, TRUE);
	}

	// Get a single project
	// @param $project_id = int(project id)
	public function get($project_id="") {

		$response['success'] = FALSE;

		if($project_id && is_numeric($project_id)) {
				
			$project = $this->harvest->getProject($project_id);

			if ($project) {
				$response = array(
					'success'=>TRUE,
					'response'=>json_decode($project) // to keep json format clean
				);
			}

		}

		echo json_encode($response, true);
	}

	// To delete a project
	// @param $project_id = int(project id)
	public function delete($project_id="") {

		$response['success'] = FALSE;

		if($project_id && is_numeric($project_id)) {
				
			$project = $this->harvest->deleteProject($project_id);

			if ($project) {
				$response = array(
					'success'=>TRUE
				);
			}
		}

		echo json_encode($response, true);
	}

	// To change project's status
	// @param $project_id = int(project id)
	public function status($project_id="") {

		$response['success'] = FALSE;

		if($project_id && is_numeric($project_id)) {
				
			$project = $this->harvest->changeProjectStatus($project_id);

			if ($project) {
				$response = array(
					'success'=>TRUE
				);
			}
		}

		echo json_encode($response, true);
	}
		
}

/* End of file projects.php */
/* Location: ./application/controllers/projects.php */