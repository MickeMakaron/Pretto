<?php
/**
* Save session data
*/
class CSession
{
	private $key;
	private $flash = null;
	private $data = array();

	
	public function __construct($key)
	{
		$this->key = $key;
	}
	
	
	
	/**
	* Set values
	*/
	public function __set($key, $value) 
	{
		$this->data[$key] = $value;
	}


	/**
	* Get values
	*/
	public function __get($key) 
	{
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	

	
	/**
	* Set flash values, to be remembered one page request
	*/
	public function setFlash($key, $value) 
	{
		$this->data['flash'][$key] = $value;
	}

	/**
	* Get flash values, if any.
	*/
	public function getFlash($key) 
	{
		return isset($this->flash[$key]) ? $this->flash[$key] : null;
	}
	
	
	
	/**
	* Store values into session.
	*/
	public function storeInSession() 
	{
		$_SESSION[$this->key] = $this->data;
	}
	
	/**
	* Store values from this object into the session.
	*/
	public function populateFromSession() 
	{
		if(isset($_SESSION[$this->key])) 
		{
			$this->data = $_SESSION[$this->key];
			if(isset($this->data['flash'])) 
			{
				$this->flash = $this->data['flash'];
				unset($this->data['flash']);
			}
		}
	}
	
	
	
	/**
	* Add message to be displayed to user on next pageload. Store in flash.
	*
	* @param $type string the type of message, for example: notice, info, success, warning, error.
	* @param $message string the message.
	*/
	public function addMessage($type, $message) 
	{
		$this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
	}
	
	
	
	/**
	* Get messages, if any. Each message is composed of a key and value. Use the key for styling.
	*
	* @returns array of messages. Each array-item contains a key and value.
	*/
	public function getMessages() 
	{
		return isset($this->flash['messages']) ? $this->flash['messages'] : null;
	}

	
	
	/**
	* Get, Set or Unset the authenticated user
	*/
	public function setAuthenticatedUser($profile) 
	{ 
		$this->data['authenticated_user'] = $profile; 
	}
	
	public function unsetAuthenticatedUser() 
	{ 
		unset($this->data['authenticated_user']); 
	}
	
	public function getAuthenticatedUser() 
	{ 
		return isset($this->data['authenticated_user']) ? $this->data['authenticated_user'] : null; 
	}
}