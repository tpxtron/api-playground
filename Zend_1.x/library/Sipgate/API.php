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
	 * Get all extensions or just the given extension
	 * 
	 * @param $extensionSipId the SipId to retrieve detailed information about (optional)
	 */
	public function extensions_get($extensionSipId = null)
	{
		if(isset($extensionSipId)) {
			return $this->_request('/my/settings/extensions/' . $extensionSipId . '/','get');
		} else {
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
		}
	}

}
