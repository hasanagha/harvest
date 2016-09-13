<?php
/**
 * Harvest library to communicate between harvest api's endpoints and CI application
 **/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Harvest {
	/*
	 * Variable to hold an insatance of CodeIgniter so we can access CodeIgniter features
	 */
	protected $CI;

	/*
	 * Create an array of the urls to be used in api calls
	 * The urls contain conversion specifications that will be replaced by sprintf in the functions
	 * @var string
	 */
	protected $api_urls = array(
		'authentication'			=> 'https://%s.harvestapp.com/account/who_am_i',
        'clients'                   => 'https://%s.harvestapp.com/clients',
 
        'projects'                  => 'https://%s.harvestapp.com/projects',
        'projects_single'           => 'https://%s.harvestapp.com/projects/%s',
        'projects_status'			=> 'https://%s.harvestapp.com/projects/%s/toggle',

        'timesheets'                => 'https://%s.harvestapp.com/daily?slim=1',
        'timesheets_add'            => 'https://%s.harvestapp.com/daily/add',
        'timesheets_update'         => 'https://%s.harvestapp.com/daily/update/%s',
        'timesheets_single'         => 'https://%s.harvestapp.com/daily/show/%s',
        'timesheets_delete'         => 'https://%s.harvestapp.com/daily/delete/%s',
        'timesheets_timer'          => 'https://%s.harvestapp.com/daily/timer/%s',
    );

    private $account;
    private $enc_auth_code;

    /*
     * The api call function is used by all other functions
     * It accepts a parameter of the url to use
     * @param string api url
     * @return std_class data returned form curl call
     */
    function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());

        return $ch;
    }

    // Setter function
    function setVars($account, $enc_auth_code) {
        $this->account = $account;
        $this->enc_auth_code = $enc_auth_code;
    }

    // Common header used in all api requests
    function getHeaders()
    {
        return array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic (' . $this->enc_auth_code . ')'
        );
    }

    // To authenticate user by a curl request to harvest
    function authenticate()
    {
        $url = sprintf($this->api_urls['authentication'], $this->account);
        $ch = $this->getCurl($url);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return $code == 200? $content : FALSE;
    }
    /******* CLIENTS Methods *******/
    function getAllClients() 
    {
        $url = sprintf($this->api_urls['clients'], $this->account);
        $ch = $this->getCurl($url);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return $code == 200? $content : FALSE;
    }
    ///// ENDS

    /******* PROJECTS *******/

    // method to get a single project by id
    // @param: $project_id => int: project_id
    // method: POST {create}/PUT {update}
    function getProject($project_id)
    {
        $url = sprintf($this->api_urls['projects_single'], $this->account, $project_id);
        $ch = $this->getCurl($url);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return $code == 200? $content : FALSE;
    }

    // method to create or update projects
    // @param: $data => array: posted to harvest
    // @param: $project_id => int: project_id
    // method: POST {create}/PUT {update}
    function createUpdateProject($data, $project_id=False)
    {
        if($project_id) {
            $url = sprintf($this->api_urls['projects_single'], $this->account, $project_id);
        }else{
            $url = sprintf($this->api_urls['projects'], $this->account);
        }

        $ch = $this->getCurl($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // If project id, request is for update
        if($project_id) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200 || $code == 201){ // if 200, project updated. If 201 projects created success
            $response = TRUE;
        } else {
            $response = json_decode($content);
            $response = $response->message;
        }

        return $response;
    }

    // Get all projects from harvest
    function getAllProjects()
    {
        $url = sprintf($this->api_urls['projects'], $this->account);
        $ch = $this->getCurl($url);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return $code == 200? $content : FALSE;
    }

    // To delete an existing project
    // @param: project_id => int: project id
    // method: DELETE
    function deleteProject($project_id)
    {
        $url = sprintf($this->api_urls['projects_single'], $this->account, $project_id);
        $ch = $this->getCurl($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (!$content && $code == 200) {
            $content = TRUE;
        }


        return $code == 200? $content : FALSE;
    }

    // To change project's status
    // @param: project_id => int: project id
    // method: PUT
    function changeProjectStatus($project_id)
    {
        $url = sprintf($this->api_urls['projects_status'], $this->account, $project_id);
        $ch = $this->getCurl($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (!$content && $code == 200) {
            $content = TRUE;
        }

        return $code == 200? $content : FALSE;
    }

    //// Ends

    /**** TIMESHEETS *****/
    // Get all projects from harvest
    function getAllTimeSheets()
    {
        $url = sprintf($this->api_urls['timesheets'], $this->account);
        $ch = $this->getCurl($url);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return $code == 200? $content : FALSE;
    }

    // method to get a single timesheet entry by id
    // @param: $project_id => int: project_id
    // method: POST {create}/PUT {update}
    function getTimesheet($entry_id)
    {
        $url = sprintf($this->api_urls['timesheets_single'], $this->account, $entry_id);
        $ch = $this->getCurl($url);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return $code == 200? $content : FALSE;
    }

    // method to create or update timesheet entry
    // @param: $data => array: posted to harvest
    // @param: $project_id => int: project_id
    // method: POST {create}/PUT {update}
    function createUpdateTimesheet($data, $timesheet_id=False)
    {
        if($timesheet_id) {
            $url = sprintf($this->api_urls['timesheets_update'], $this->account, $timesheet_id);
        }else{
            $url = sprintf($this->api_urls['timesheets_add'], $this->account);
        }

        $ch = $this->getCurl($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200 || $code == 201){ // if 200, project updated. If 201 project created success
            $response = TRUE;
        } else {
            $response = json_decode($content);
            $response = $response->message;
        }

        return $response;
    }

    // To toggle an existing timesheet entry timer
    // @param: timesheet_id => int: entry id
    // method: GET
    function toggleTimesheetEntry($timesheet_id)
    {
        $url = sprintf($this->api_urls['timesheets_timer'], $this->account, $timesheet_id);
        $ch = $this->getCurl($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (!$content && $code == 200) {
            $content = TRUE;
        }

        return $code == 200? $content : FALSE;
    }

    // To delete an existing timesheet entry
    // @param: timesheet_id => int: entry id
    // method: DELETE
    function deleteTimesheetEntry($timesheet_id)
    {
        $url = sprintf($this->api_urls['timesheets_delete'], $this->account, $timesheet_id);
        $ch = $this->getCurl($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (!$content && $code == 200) {
            $content = TRUE;
        }

        return $code == 200? $content : FALSE;
    }
}