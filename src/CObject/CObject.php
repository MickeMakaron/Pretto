<?php
/**
 * CObject is extended by class controllers to gain access to Pretto variables through $this.
 * 
 * @package PrettoCore
 */
class CObject
{
	public $config;
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;

	protected function __construct($pr = null) 
	{
		if(!$pr)
			$pr = CPretto::instance();
			
		$this->config   = &$pr->config;
		$this->request  = &$pr->request;
		$this->data     = &$pr->data;
		$this->db       = &$pr->db;
		$this->views    = &$pr->views;
		$this->session	= &$pr->session;
		$this->user		= &$pr->user;
	}

	/**
	* Redirect to another url and store the session
	*
	* @param $url string the relative url or the controller
	* @param $method string the method to use, $url is then the controller or empty for current controller
	* @param $arguments string the extra arguments to send to the method
	*/
	protected function redirectTo($urlOrController = null, $method = null, $args = null) 
	{
		if(isset($this->config['debug']['dbNumQueries']) && $this->config['debug']['dbNumQueries'] && isset($this->db))
			$this->session->setFlash('databaseNumQueries', $this->db->getNumQueries());
		if(isset($this->config['debug']['dbQueries']) && $this->config['debug']['dbQueries'] && isset($this->db))
			$this->session->setFlash('databaseQueries', $this->db->getQueries());
		if(isset($this->config['debug']['timer']) && $this->config['debug']['timer'])
			$this->session->setFlash('timer', $this->timer);

		$this->session->storeInSession();
		header('Location: ' . $this->request->createUrl($urlOrController, $method, $args));
	}	

	/**
	* Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
	*
	* @param string method name the method, default is index method.
	* @param $arguments string the extra arguments to send to the method
	*/
	protected function redirectToController($method = null, $args = null) 
	{
		$this->redirectTo($this->request->controller, $method, $args);
	}


	/**
	* Redirect to a controller and method. Uses RedirectTo().
	*
	* @param string controller name the controller or null for current controller.
	* @param string method name the method, default is current method.
	* @param $arguments string the extra arguments to send to the method
	*/
	protected function RedirectToControllerMethod($controller=null, $method=null, $args = null) 
	{
		$controller = is_null($controller) ? $this->request->controller : null;
		$method = is_null($method) ? $this->request->method : null;	
		
		$this->redirectTo($this->request->createUrl($controller, $method, $args));
	}
	
	/**
	* Save a message in the session. Uses $this->session->AddMessage()
	*
	* @param $type string the type of message, for example: notice, info, success, warning, error.
	* @param $message string the message.
	* @param $alternative string the message if the $type is set to false, defaults to null.
	*/
	protected function addMessage($type, $message, $alternative = null) 
	{
		if($type === false) 
		{
			$type = 'error';
			$message = $alternative;
		} 
		else if($type === true)
			$type = 'success';
			
		$this->session->addMessage($type, $message);
	}
}  
