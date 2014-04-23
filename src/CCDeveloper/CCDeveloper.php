<?php
/**
 * Controller for development and testing purpose, helpful methods for the developer.
 * 
 * @package PrettoCore
 */
class CCDeveloper extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
		unset($this->config['theme']['template_file']);
		$this->config['theme']['name'] = 'core';
		$this->config['theme']['stylesheet'] = 'style.css';
	}


	/**
	* Implementing interface IController. All controllers must have an index action.
	*/	
	public function index() 
	{  
		$this->menu();
	}

	/**
	* Display all items of the CObject.
	*/
	public function displayObject() 
	{   
		$this->menu();
		$this->data['main'] .= <<<EOD
		<h2>Dumping content of controller</h2>
		<p>Here is the content of the controller, including properties from CObject which holds access to common resources in CPretto.</p>
EOD;
		$this->data['main'] .= '<pre>' . htmlent(print_r($this, true)) . '</pre>';
	}

	/**
	* Create a list of links in the supported ways.
	*/
	public function links() 
	{  
		$this->menu();

		$url 		= 'developer/links';
		$current	= $this->request->createUrl($url);
   
		$this->request->defaultUrl		= true;
 		$this->request->querystringUrl	= false; 
		$default						= $this->request->createUrl($url);

		$this->request->defaultUrl		= false;
		$this->request->querystringUrl	= false; 
		$clean							= $this->request->createUrl($url);    

		$this->request->defaultUrl		= false;
		$this->request->querystringUrl	= true;    
		$querystring					= $this->request->createUrl($url);

		$this->data['main'] .= <<<EOD
		<h2>CRequest::createUrl()</h2>
		<p>Here is a list of urls created using above method with various settings. All links should lead to
		this same page.</p>
		<ul>
		<li><a href='$current'>This is the current setting</a>
		<li><a href='$default'>This would be the default url</a>
		<li><a href='$clean'>This should be a clean url</a>
		<li><a href='$querystring'>This should be a querystring like url</a>
		</ul>
		<p>Enables various and flexible url-strategy.</p>
EOD;
	}


	/**
	* Create a method that shows the menu, same for all methods
	*/
	private function menu() 
	{  
		$this->config['theme']['name'] = 'core';
		$menu = array('developer', 'developer/index', 'developer/links', 'developer/displayobject');

		$html = null;
		foreach($menu as $val) 
			$html .= "<li><a href='" . $this->request->createUrl($val) . "'>$val</a>";  


		$this->data['title'] = "The Developer Controller";
		$this->data['main'] = <<<EOD
		<h1>The Developer Controller</h1>
		<p>This is what you can do for now:</p>
		<ul>
		$html
		</ul>
EOD;
	}

}  