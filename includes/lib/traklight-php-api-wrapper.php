<?php

require_once 'lib/class-traklight-api-wrapper-base.php';

class TraklightApiWrapper extends TraklightApiWrapperBase {

	protected $authKey;
	protected $subdomain;

	public function __construct($subdomain, $authKey) {
		$this->authKey = $authKey;
		$this->subdomain = $subdomain;
	}

	/**
	 * Gets all associated users of this subdomain
	 *
	 * @return array of Users and their attributes
	 */
	public function getUsers() {
		return $this->get('/api/v1/users/');
	}

	/**
	 * Gets associated user of this subdomain by ID
	 *
	 * @return array of user attributes
	 */
	public function getUserByID($id) {
		return $this->get('/api/v1/users/'.$id);
	}

	/**
	 * Creates a user on this subdomain
	 *
	 * @return array of user attributes
	 */
	public function createUser($attrs) {
		return $this->post('/api/v1/users/',$attrs);
	}

	/**
	 * Automatically log a user into their Traklight dashboard
	 *
	 * @return string | URL of the logged in session
	 */
	public function getSessionUrl($email) {
		$result = $this->get('/api/v1/login/', Array('email' => $email));
		return $this->baseUrl().'/api/v1/login/session/'.$result['sid'];
	}
}
