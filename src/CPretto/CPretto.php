<?php
/**
* Main class for Pretto, holds everything.
*
* @package PrettoCore
*/
class CPretto implements ISingleton 
{
	private static $instance 	= null;
	
	public $request 			= null;
	public $data				= null;
	public $db					= null;
	public $views				= null;
	public $session				= null;
	public $user				= null;

	/**
	* Constructor
	*/
	protected function __construct() 
	{
		// include the site specific config.php and create a ref to $ly to be used by config.php
		$pr = &$this;
		require(PRETTO_SITE_PATH.'/config.php');
<<<<<<< HEAD

		
		// Create a database object.
		if(isset($this->config['database'][0]['dsn']))
		{
			$user = isset($this->config['database'][0]['user']) ? $this->config['database'][0]['user'] : null;
			$password = isset($this->config['database'][0]['password']) ? $this->config['database'][0]['password'] : null;
			$driver_options = isset($this->config['database'][0]['driver_options']) ? $this->config['database'][0]['driver_options'] : null;
			

			$this->db = CDatabase::instance($this->config['database'][0]['dsn'], $user, $password, $driver_options);
		}
=======
		
		// Create a database object.
		if(isset($this->config['database'][0]['dsn']))
			$this->db = new CDatabase($this->config['database'][0]['dsn']);
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
			
		// Create a container for all views and theme data
		$this->views = new CViewContainer();
		
		// Start a named session
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->populateFromSession();
		
		// Create a object for the user
		$this->user = new CMUser($this);
	}

	
	
	/**
	* Singleton pattern. Get the instance of the latest created object or create a new one.
	* @return CPretto The instance of this class.
	*/
	public static function instance() 
	{
		if(self::$instance == null)
			self::$instance = new CPretto();

		return self::$instance;
	}
	
	/**
	* Frontcontroller, check url and route to controllers.
	*/
	public function frontControllerRoute() 
	{
		$this->data = array();
		// Override config settings with PrettoConfig table
		if($this->db)
		{
			$cmconfig = new CMConfig();			
			if($cmconfig->tableExists())
				$cmconfigData = $cmconfig->getConfigData();
				
			if(isset($cmconfigData['allow_browser_access']) && ($cmconfigData['allow_browser_access'] && $this->config['allow_browser_access']))
				$this->data = array_merge($this->data, $cmconfigData);
		}
		
		
		$this->data['debug']  = "REQUEST_URI - {$_SERVER['REQUEST_URI']}\n";
		$this->data['debug'] .= "SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}\n";
		
		// Step 1
		// Take current url and divide it in controller, method and parameters
		$this->request = new CRequest($this->config['url_type']);
		$this->request->init($this->config['base_url']);
		
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
			$controllerEnabled 	= isset($this->data['controllers'][$controller]['enabled']) ? $this->data['controllers'][$controller]['enabled'] : $controllerEnabled;
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
				$formattedMethod = str_replace(array('_', '-'), '', $method);
				if($rc->hasMethod($formattedMethod)) 
				{
					$controllerObj	= $rc->newinstance();
					$methodObj		= $rc->getMethod($formattedMethod);
					
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
	public function themeEngineRender() 
	{
		$this->session->storeInSession();
		
		// Is theme enabled?
		if(!isset($this->config['theme'])) 
			return;
		
<<<<<<< HEAD
		// Override config settings with PrettoConfig table
		if($this->db)
		{
			$cmconfig = new CMConfig();			
			if($cmconfig->tableExists())
			{
				$cmconfigData = $cmconfig->getConfigData();
				
			if(isset($cmconfigData['allow_browser_access']) && ($cmconfigData['allow_browser_access'] && $this->config['allow_browser_access']))
				$this->config = array_merge($this->config, $cmconfigData);
			}
		}
=======
		
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
		
		// Get the paths and settings for the theme
        $themePath  = PRETTO_INSTALL_PATH . '/' . $this->config['theme']['path'];
        $themeUrl   = $this->request->base_url . $this->config['theme']['path'];
		
		// Is there a parent theme?
		$parentPath = null;
		$parentUrl = null;
		if(isset($this->config['theme']['parent'])) 
		{
			$parentPath = PRETTO_INSTALL_PATH . '/' . $this->config['theme']['parent'];
			$parentUrl  = $this->request->base_url . $this->config['theme']['parent'];
		}

<<<<<<< HEAD
        // Add stylesheet name to the $pr->data array
=======
        // Add stylesheet name to the $ly->data array
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
        $this->data['stylesheet'] = $this->config['theme']['stylesheet'];
		
		
		// Make the theme urls available as part of $pr
        $this->themeUrl = $themeUrl;
        $this->themeParentUrl = $parentUrl;

		
		// Map menu to region if defined
		if(is_array($this->config['theme']['menu_to_region'])) 
			foreach($this->config['theme']['menu_to_region'] as $key => $val) 
				$this->views->addString($this->drawMenu($key), array(null), $val);
		
		
		
		
		
		// Include the global functions.php and the functions.php that are part of the theme
		$pr = &$this;
		
		// First the default Pretto themes/functions.php
		include(PRETTO_INSTALL_PATH . '/themes/functions.php');
		
		// Then the functions.php from the parent theme
		if($parentPath && is_file("{$parentPath}/functions.php"))
			include "{$parentPath}/functions.php";
		
		// And last the current theme functions.php
		if(is_file("{$themePath}/functions.php"))
			include "{$themePath}/functions.php";
		
		// Extract $pr->data and $pr->view->data to own variables and handover to the template file
		extract($this->data); //DEPRECATED - use $this->views->getData()
		extract($this->views->getData());
		
		// Extract additional data from config file, if set
		if(isset($this->config['theme']['data']))
			extract($this->config['theme']['data']);

<<<<<<< HEAD
=======
		
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
		// Execute the template file
		$templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
		
		if(is_file("{$themePath}/{$templateFile}")) 
			include("{$themePath}/{$templateFile}");
		elseif(is_file("{$parentPath}/{$templateFile}"))
			include("{$parentPath}/{$templateFile}");
		else 
<<<<<<< HEAD
			throw new Exception("No such template file. Neither {$themePath}/{$templateFile} and {$parentPath}/{$templateFile} is valid.");
=======
			throw new Exception('No such template file.');
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
	}
	
	/**
	* Draw HTML for a menu defined in $pr->config['menus'].
	*
	* @param $menu string then key to the menu in the config-array.
	* @returns string with the HTML representing the menu.
	*/
	public function drawMenu($menu) 
	{
<<<<<<< HEAD

=======
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
		$items = null;
		if(isset($this->config['menus'][$menu])) 
		{
			foreach($this->config['menus'][$menu] as $val) 
			{
				$selected = null;
				if($val['url'] == $this->request->query || (!is_null($this->request->routed_from) && $val['url'] == $this->request->routed_from))
					$selected = " class='selected'";

				$items .= "<li><a {$selected} href='" . $this->request->createUrl($val['url']) . "'>{$val['label']}</a></li>\n";
			}
		} 
		else 
			throw new Exception('No such menu.');
			
		return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
	}
}