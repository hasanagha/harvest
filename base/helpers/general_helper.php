<?php

// method to return all user's data from session
function getUserData() {
	$CI = &get_instance();
	return $CI->session->userdata(USER_SESSION_KEY);
}

// method to return 'account' value from users's data in session
function getUserAccount() {
	$userdata = getUserData();
	return $userdata['account'];
}

// method to return 'user name' from users's data in session
function getUserName() {
	$userdata = getUserData();
	return $userdata['first_name']. " ". $userdata['last_name'];
}

// method to return list of 'clients' from users's data in session
function getUserClients() {
	$userdata = getUserData();
	return $userdata['clients'];
}

// method to return 'encrypted auth string' from users's data in session
function getUserEncAuthCode() {
	$userdata = getUserData();
	return $userdata['encrypted_authentication_code'];
}

// method to return 'user profile image' from users's data in session
function getUserProfileImage() {
	$userdata = getUserData();
	return $userdata['account_harvest_url'] . $userdata['avatar_url'];
}

// returns specific client name from clients data in session
// @param $id = int(client id)
function getClientNameFromSession($id) {
	if(is_numeric($id)) {
		$clients = getUserClients();
		if($clients){
			foreach(json_decode($clients) as $client) {
				if($id == $client->client->id)
					return $client->client->name;
			}
		}
	}
	return $id;
}