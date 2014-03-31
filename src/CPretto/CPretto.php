<?php
/**
* Main class for Pretto, holds everything.
*
* @package PrettoCore
*/
class CPretto implements ISingleton 
{
	private static $instance 	= null;
	public  $request 			= null;

	/**
	* Constructor
	*/
	protected function __construct() 
	{
		// include the site specific config.php and create a ref to $ly to be used by config.php
		$pr = &$this;
		require(PRETTO_SITE_PATH.'/config.php');
	}
	
	
	/**
	* Singleton pattern. Get the instance of the latest created object or create a new one.
	* @return CPretto The instance of this class.
	*/
	public static function Instance() 
	{
		if(self::$instance == null)
			self::$instance = new CPretto();

		return self::$instance;
	}
	
	/**
	* Frontcontroller, check url and route to controllers.
	*/
	public function FrontControllerRoute() 
	{
		$this->data['debug']  = "REQUEST_URI - {$_SERVER['REQUEST_URI']}\n";
		$this->data['debug'] .= "SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}\n";
		
		// Step 1
		// Take current url and divide it in controller, method and parameters
		echo $this->config['url_type'];
		$this->request = new CRequest($this->config['url_type']);
		$this->request->Init($this->config['base_url']);
		
		$controller = $this->request->controller;
		$method     = $this->request->method;
		$arguments  = $this->request->arguments;
		
		// Is the controller enabled in config.php?
		$controllerExists	= isset($this->config['controllers'][$controller]);
		$controllerEnabled	= false;
		$className			= false;
		$classExists		= false;

		if($controllerExists) 
		{
			$controllerEnabled	= ($this->config['controllers'][$controller]['enabled'] == true);
			$className			= $this->config['controllers'][$controller]['class'];
			$classExists		= class_exists($className);
		}
		
		// Step 2
		// Check if there is a callable method in the controller class, if then call it
		if($controllerExists && $controllerEnabled && $classExists) 
		{
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) 
			{
				if($rc->hasMethod($method)) 
				{
					$controllerObj	= $rc->newInstance();
					$methodObj		= $rc->getMethod($method);
					
					$methodObj->invokeArgs($controllerObj, $arguments);
				} 
				else 
					die("404. " . get_class() . ' error: Controller does not contain method.');
			} 
			else
				die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
		}
		else
			die('404. Page is not found.');

	}
	
	
	/**
	* Theme Engine Render, renders the views using the selected theme.
	*/
	public function ThemeEngineRender() 
	{
		// Get the paths and settings for the theme
		$themeName	= $this->config['theme']['name'];
		$themePath	= PRETTO_INSTALL_PATH . "/themes/{$themeName}";
		$themeUrl 	= $this->request->base_url . "themes/{$themeName}";

		// Add stylesheet path to the $pr->data array
		$this->data['stylesheet'] = "{$themeUrl}/style.css";

		// Include the global functions.php and the functions.php that are part of the theme
		$pr = &$this;
		$functionsPath = "{$themePath}/functions.php";
		if(is_file($functionsPath))
			include $functionsPath;
		
		// Extract $pr->data to own variables and handover to the template file
		extract($this->data);     
		include("{$themePath}/default.tpl.php");
	}
	
	
}