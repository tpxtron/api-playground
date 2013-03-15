<?php
/**
 * sipgate API Class for Zend Framework 1.x
 *
 * (c) 2013 Tobias Niepel
 * 
 * A basic implementation of the sipgate REST API
 * API Information: http://www.live.sipgate.de/api/rest
 */

class Sipgate_API
{
	// This is where we store the API connection...
	private $_api;

	/**
	 * sipgate API Class Constructor
	 *
	 * @param $user the Username to use for authentication
	 * @param $pass the Password to use for authentication
	 */
	public function __construct($user = null, $pass = null)
	{
		if(isset($user) && isset($pass)) {
			Zend_Rest_Client::getHttpClient()->setAuth($user, $pass);
		}
		$this->_api = new Zend_Rest_Client('https://api.sipgate.net');
	}

	/**
	 * Authenticate with username/password
	 *
	 * In case you didn't supply username/password when instanciating the class
	 */
	public function authenticate($user, $pass)
	{
		Zend_Rest_Client::getHttpClient()->setAuth($user, $pass);
		$this->_api = new Zend_Rest_Client('https://api.sipgate.net');
	}

	/**
	 * Get all events of the currently logged-in user
	 *
	 * Returns all Events (calls, faxes, etc.)
	 */
	public function events_get()
	{
		return $this->_request('/my/events/','get');
	}

	/**
	 * Get either all call events or only a specific one
	 *
	 * @param $eventId the event ID of a single call event (optional)
	 */
	public function events_calls_get($eventId = null)
	{
		if(isset($eventId)) {
			// Will return only the given call event
			return $this->_request('/my/events/calls/' . $eventId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all call events
			return $this->_request('/my/events/calls/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Delete the given call event
	 *
	 * @param $eventId the event ID of the call to be deleted
	 */
	public function events_calls_delete($eventId)
	{
		$this->_request('/my/events/calls/' . $eventId . '/','delete');
	}

	/**
	 * Get all extensions or just the given extension
	 * 
	 * @param $extensionSipId the SipId to retrieve detailed information about (optional)
	 */
	public function extensions_get($extensionSipId = null)
	{
		if(isset($extensionSipId)) {
			// Will return only the given extension
			return $this->_request('/my/settings/extensions/' . $extensionSipId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all extensions
			return $this->_request('/my/settings/extensions/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Get the DND Status for a given extension
	 *
	 * @param $extensionSipId the SipId to receive the DND status for
	 */
	public function extensions_dnd_get($extensionSipId)	
	{
		return $this->_request('/my/settings/extensions/'.$extensionSipId.'/dnd/','get');
	}

	/**
	 * Set the DND Status for a given extension
	 *
	 * @param $extensionSipId the SipId to set the DND status for
	 */
	public function extensions_dnd_set($extensionSipId,$value)
	{
		$this->_request('/my/settings/extensions/'.$extensionSipId.'/dnd/','post',array("value"=>(string)$value));
	
		$extension = $this->extensions_dnd_get($extensionSipId);
		return (bool)$extension->dnd->value;
	}

	/**
	 * Generic Request sending method
	 *
	 * Sends/receives data from/to sipgate API
	 *
	 * @param $url the URL to send the request to
	 * @param $method the Method to use (get/post)
	 * @param $params additional params to send to the request URI
	 */
	protected function _request($url,$method,$params = array())
	{
		$defaultParams = array(
			"version"		=> "2.41.0",
		);

		// Merge the given parameters with our default parameters
		$requestParams = array_merge($params,$defaultParams);

		switch($method) {
			case "get":
				$xmlResult = $this->_api->restGet($url,$requestParams)->getBody();
				$result = simplexml_load_string($xmlResult);
				return $result;
				break;
			case "post":
				$this->_api->restPost($url,$requestParams);
			case "delete":
				$this->_api->restDelete($url,$requestParams);
			case "put":
				$this->_api->restPut($url,$requestParams);
		}
	}

}
