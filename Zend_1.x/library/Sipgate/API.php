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
	 * Add a label to the given call event
	 *
	 * @param $eventId the event ID of the call event to be labelled
	 * @param $labelName the name of the label to attach
	 */
	public function events_calls_put($eventId, $labelName)
	{
		$this->_request('/my/events/calls/' . $eventId . '/','put',array("value"=>$labelName));
	}

	/**
	 * Get the call event's content (e.g. Voicemail recording)
	 *
	 * @param $eventId the event ID of the call event
	 * @param $dataId the data ID of the content to be retrieved
	 */
	public function events_calls_content_get($eventId, $dataId)
	{
		return $this->_request('/my/events/calls/' . $eventId . '/' . $dataId . '/content/','get');
	}

	/**
	 * Get the call event's 'read' property
	 *
	 * @param $eventId the event ID of the call event
	 */
	public function events_calls_read_get($eventId)
	{
		return $this->_request('/my/events/calls/' . $eventId . '/read/','get');
	}

	/**
	 * Set the call event's 'read' property
	 *
	 * @param $eventId the event ID of the call event
	 * @param $value the new 'read' value (boolean true/false)
	 */
	public function events_calls_read_put($eventId, $value)
	{
		$this->_request('/my/events/calls/' . $eventId . '/read/','put',array("value"=>$value));
	}

	/**
	 * Get the call event's 'starred' property
	 *
	 * @param $eventId the event ID of the call event
	 */
	public function events_calls_starred_get($eventId)
	{
		return $this->_request('/my/events/calls/' . $eventId . '/starred/','get');
	}

	/**
	 * Set the call event's 'starred' property
	 *
	 * @param $eventId the event ID of the call event
	 * @param $value the new 'starred' value (boolean true/false)
	 */
	public function events_calls_starred_put($eventId, $value)
	{
		$this->_request('/my/events/calls/' . $eventId . '/starred/','put',array("value"=>$value));
	}
		
	/**
	 * Get either all fax events or only a specific one
	 *
	 * @param $eventId the event ID of a single fax event (optional)
	 */
	public function events_faxes_get($eventId = null)
	{
		if(isset($eventId)) {
			// Will return only the given fax event
			return $this->_request('/my/events/faxes/' . $eventId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all call events
			return $this->_request('/my/events/faxes/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Delete the given fax event
	 *
	 * @param $eventId the event ID of the fax to be deleted
	 */
	public function events_faxes_delete($eventId)
	{
		$this->_request('/my/events/faxes/' . $eventId . '/','delete');
	}

	/**
	 * Add a label to the given fax event
	 *
	 * @param $eventId the event ID of the fax event to be labelled
	 * @param $labelName the name of the label to attach
	 */
	public function events_faxes_put($eventId, $labelName)
	{
		$this->_request('/my/events/faxes/' . $eventId . '/','put',array("value"=>$labelName));
	}

	/**
	 * Get the fax event's content (e.g. PDF fax data)
	 *
	 * @param $eventId the event ID of the fax event
	 * @param $dataId the data ID of the content to be retrieved
	 */
	public function events_faxes_content_get($eventId, $dataId)
	{
		return $this->_request('/my/events/faxes/' . $eventId . '/' . $dataId . '/content/','get');
	}

	/**
	 * Get the fax event's 'read' property
	 *
	 * @param $eventId the event ID of the fax event
	 */
	public function events_faxes_read_get($eventId)
	{
		return $this->_request('/my/events/faxes/' . $eventId . '/read/','get');
	}

	/**
	 * Set the fax event's 'read' property
	 *
	 * @param $eventId the event ID of the fax event
	 * @param $value the new 'read' value (boolean true/false)
	 */
	public function events_faxes_read_put($eventId, $value)
	{
		$this->_request('/my/events/faxes/' . $eventId . '/read/','put',array("value"=>$value));
	}

	/**
	 * Get the fax event's 'starred' property
	 *
	 * @param $eventId the event ID of the fax event
	 */
	public function events_faxes_starred_get($eventId)
	{
		return $this->_request('/my/events/faxes/' . $eventId . '/starred/','get');
	}

	/**
	 * Set the fax event's 'starred' property
	 *
	 * @param $eventId the event ID of the fax event
	 * @param $value the new 'starred' value (boolean true/false)
	 */
	public function events_faxes_starred_put($eventId, $value)
	{
		$this->_request('/my/events/faxes/' . $eventId . '/starred/','put',array("value"=>$value));
	}

	/**
	 * Get either all sms events or only a specific one
	 *
	 * @param $eventId the event ID of a single sms event (optional)
	 */
	public function events_sms_get($eventId = null)
	{
		if(isset($eventId)) {
			// Will return only the given sms event
			return $this->_request('/my/events/sms/' . $eventId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all sms events
			return $this->_request('/my/events/sms/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Delete the given sms event
	 *
	 * @param $eventId the event ID of the sms to be deleted
	 */
	public function events_sms_delete($eventId)
	{
		$this->_request('/my/events/sms/' . $eventId . '/','delete');
	}

	/**
	 * Add a label to the given sms event
	 *
	 * @param $eventId the event ID of the sms event to be labelled
	 * @param $labelName the name of the label to attach
	 */
	public function events_sms_put($eventId, $labelName)
	{
		$this->_request('/my/events/sms/' . $eventId . '/','put',array("value"=>$labelName));
	}

	/**
	 * Get the sms event's content (e.g. TXT representation)
	 *
	 * @param $eventId the event ID of the sms event
	 * @param $dataId the data ID of the content to be retrieved
	 */
	public function events_sms_content_get($eventId, $dataId)
	{
		return $this->_request('/my/events/sms/' . $eventId . '/' . $dataId . '/content/','get');
	}

	/**
	 * Get the sms event's 'read' property
	 *
	 * @param $eventId the event ID of the sms event
	 */
	public function events_sms_read_get($eventId)
	{
		return $this->_request('/my/events/sms/' . $eventId . '/read/','get');
	}

	/**
	 * Set the sms event's 'read' property
	 *
	 * @param $eventId the event ID of the sms event
	 * @param $value the new 'read' value (boolean true/false)
	 */
	public function events_sms_read_put($eventId, $value)
	{
		$this->_request('/my/events/sms/' . $eventId . '/read/','put',array("value"=>$value));
	}

	/**
	 * Get the sms event's 'starred' property
	 *
	 * @param $eventId the event ID of the sms event
	 */
	public function events_sms_starred_get($eventId)
	{
		return $this->_request('/my/events/sms/' . $eventId . '/starred/','get');
	}

	/**
	 * Set the sms event's 'starred' property
	 *
	 * @param $eventId the event ID of the sms event
	 * @param $value the new 'starred' value (boolean true/false)
	 */
	public function events_sms_starred_put($eventId, $value)
	{
		$this->_request('/my/events/sms/' . $eventId . '/starred/','put',array("value"=>$value));
	}






	/**
	 * Get either all voicemail events or only a specific one
	 *
	 * @param $eventId the event ID of a single voicemail event (optional)
	 */
	public function events_voicemails_get($eventId = null)
	{
		if(isset($eventId)) {
			// Will return only the given voicemail event
			return $this->_request('/my/voicemails/sms/' . $eventId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all voicemail events
			return $this->_request('/my/voicemails/sms/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Delete the given voicemail event
	 *
	 * @param $eventId the event ID of the voicemail to be deleted
	 */
	public function events_voicemails_delete($eventId)
	{
		$this->_request('/my/voicemails/sms/' . $eventId . '/','delete');
	}

	/**
	 * Add a label to the given voicemail event
	 *
	 * @param $eventId the event ID of the voicemail event to be labelled
	 * @param $labelName the name of the label to attach
	 */
	public function events_voicemails_put($eventId, $labelName)
	{
		$this->_request('/my/events/voicemails/' . $eventId . '/','put',array("value"=>$labelName));
	}

	/**
	 * Get the voicemail event's content (e.g. MP3 Recording)
	 *
	 * @param $eventId the event ID of the voicemail event
	 * @param $dataId the data ID of the content to be retrieved
	 */
	public function events_voicemails_content_get($eventId, $dataId)
	{
		return $this->_request('/my/events/voicemails/' . $eventId . '/' . $dataId . '/content/','get');
	}

	/**
	 * Get the voicemail event's 'read' property
	 *
	 * @param $eventId the event ID of the sms event
	 */
	public function events_voicemails_read_get($eventId)
	{
		return $this->_request('/my/events/voicemails/' . $eventId . '/read/','get');
	}

	/**
	 * Set the voicemail event's 'read' property
	 *
	 * @param $eventId the event ID of the voicemail event
	 * @param $value the new 'read' value (boolean true/false)
	 */
	public function events_voicemails_read_put($eventId, $value)
	{
		$this->_request('/my/events/voicemails/' . $eventId . '/read/','put',array("value"=>$value));
	}

	/**
	 * Get the voicemail event's 'starred' property
	 *
	 * @param $eventId the event ID of the voicemail event
	 */
	public function events_voicemails_starred_get($eventId)
	{
		return $this->_request('/my/events/voicemails/' . $eventId . '/starred/','get');
	}

	/**
	 * Set the voicemail event's 'starred' property
	 *
	 * @param $eventId the event ID of the voicemail event
	 * @param $value the new 'starred' value (boolean true/false)
	 */
	public function events_voicemails_starred_put($eventId, $value)
	{
		$this->_request('/my/events/voicemails/' . $eventId . '/starred/','put',array("value"=>$value));
	}


	/**
	 * Get all extensions or just the given extension
	 * 
	 * @param $extensionSipId the SipId to retrieve detailed information about (optional)
	 */
	public function settings_extensions_get($extensionSipId = null)
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
	public function settings_extensions_dnd_get($extensionSipId)	
	{
		return $this->_request('/my/settings/extensions/'.$extensionSipId.'/dnd/','get');
	}

	/**
	 * Set the DND Status for a given extension
	 *
	 * @param $extensionSipId the SipId to set the DND status for
	 */
	public function settings_extensions_dnd_set($extensionSipId,$value)
	{
		$this->_request('/my/settings/extensions/'.$extensionSipId.'/dnd/','post',array("value"=>(string)$value));
	
		$extension = $this->extensions_dnd_get($extensionSipId);
		return (bool)$extension->dnd->value;
	}

	/**
	 * Get the Base Product Type for the current user
	 */
	public function settings_baseproducttype_get()
	{
		return $this->_request('/my/settings/baseproducttype/','get');
	}

	/**
	 * Get the Registered Mobile Devices for the current user
	 */
	public function settings_registeredmobiledevices_get()
	{
		return $this->_request('/my/settings/registeredmobiledevices/','get');
	}

	/**
	 * Get all mobile extensions for the current user
	 */
	public function settings_mobile_extensions_get()
	{
		return $this->_request('/my/settings/mobile/extensions/','get');
	}

	/**
	 * Create a mobile extension for the current user
	 *
	 * @param $phoneNumber the external phone number of the mobile extension (e.g. 491701234567)
	 * @param $model the Model of the external mobile phone (e.g. "iPhone 4S")
	 * @param $vendor the Vendor of the external mobile phone (e.g. "Apple Inc.")
	 * @param $firmware the firmware version of the external mobile phone (e.g. "4.0.2")
	 */
	public function settings_mobile_extensions_post($phoneNumber,$model,$vendor,$firmware)
	{
		$this->_request('/my/settings/mobile/extensions/','post',array("phoneNumber"=>$phoneNumber,"model"=>$model,"vendor"=>$vendor,"firmware"=>$firmware));
	}

	/**
	 * Request a mobile GSM code for voicemail redirection
	 */
	public function settings_mobile_extensions_unifiedvoicemailgsmcode_get()
	{
		return $this->_request('/my/settings/mobile/extensions/unifiedvoicemailgsmcode/','get');
	}

	/**
	 * Request all numbers for the account
	 */
	public function settings_numbers_get()
	{
		return $this->_request('/my/settings/numbers/','get',array("complexity"=>"full"));
	}
	
	/**
	 * Get all currently running call sessions
	 *
	 * @param $sessionId the Session ID of the call you want details for (optional)
	 */
	public function sessions_calls_get($sessionId = null)
	{
		if(isset($sessionId)) {
			// Will return only the given call
			return $this->_request('/my/sessions/calls/' . $sessionId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all currently running calls
			return $this->_request('/my/sessions/calls/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Initiate a new click2dial call
	 *
	 * @param $callee the number you want to call to (the PARTNER'S phone number)
	 * @param $caller the number you want to call from (YOUR phone number)
	 * @param $registerSip the SipId of the phone extension you want to use for billing purposes (e.g. 1234567e0)
	 */
	public function sessions_calls_post($callee,$caller,$registerSip)
	{
		$this->_request('/my/sessions/calls/','post',array("callee"=>$callee,"caller"=>$caller,"registerSip"=>$registerSip));
	}
	
	/**
	 * Terminate ( = hang up) the given session
	 * 
	 * @param $sessionId the Session ID of the call you want to terminate
	 */
	public function sessions_calls_delete($sessionId)
	{
		$this->_request('/my/sessions/calls/' . $sessionId . '/','delete');
	}

	/**
	 * Record the given session
	 * 
	 * @param $sessionId the Session ID of the call you want to record
	 * @param $value enable/disable recording (true to start, false to stop recording)
	 */
	public function sessions_calls_recording_put($sessionId,$value)
	{
		$this->_request('/my/sessions/calls/' . $sessionId . '/recording/','put',array("value"=>$value));
	}

	/**
	 * Park the given call
	 * 
	 * @param $sessionId the Session ID of the call you want to record
	 * @param $memberId the member ID of the call end you want to park
	 * @param $value enable/disable parking (true to park, false to unpark)
	 */
	public function sessions_calls_member_parking_put($sessionId,$memberId,$value)
	{
		$this->_request('/my/sessions/calls/' . $sessionId . '/member/' . $memberId . '/parking/','put',array("value"=>$value));
	}	

	/**
	 * Set temporary callthrough routing
	 * 
	 * @param $sourceNumber The source number (YOUR number in E164 Format)
	 * @param $targetNumber The target number (your PARTNER'S number in E164 Format)
	 */
	public function sessions_calls_member_parking_put($sourceNumber,$targetNumber)
	{
		$this->_request('/my/sessions/callthrough/','post',array("sourceNumber"=>$sourceNumber,"targetNumber"=>$targetNumber));
	}

	/**
	 * Get contacts
	 *
	 * @param $contactId the contact ID to retrieve a single contact (optional)
	 */
	public function contacts_get($contactId = null)
	{
		if(isset($contactId)) {
			// Will return only the given contact
			return $this->_request('/my/contacts/' . $contactId . '/','get',array("complexity"=>"full"));
		} else {
			// Will return all contacts
			return $this->_request('/my/contacts/','get',array("complexity"=>"full"));
		}
	}

	/**
	 * Get phonebook XML for a given phone
	 *
	 * @param $vendorName the name of the phone's vendor (currently supported: 'snom' and 'grandstream')
	 */
	public function contacts_phonebook_get($vendorName)
	{
		return $this->_request('/my/contacts/phonebook/' . $vendorName .'/','get',array("complexity"=>"full"));
	}

	/**
	 * Get the account's balance
	 */
	public function billing_balance_get()
	{
		return $this->_request('/my/billing/balance/','get',array("complexity"=>"full"));
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
