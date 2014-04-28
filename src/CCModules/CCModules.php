<?php
/**
* To manage and analyse all modules of Lydia.
*
* @package PrettoCore
*/
class CCModules extends CObject implements IController 
{
	public function __construct() 
	{ 
		parent::__construct(); 
	}


	/**
	* Show an example index-page and display what can be done through this controller.
	*/
	public function index() 
	{
		$modules = new CMModules();
		$controllers = $modules->availableControllers();
		$allModules = $modules->readAndAnalyse();
		$this->views->setTitle('Manage Modules');
		
		$this->views->addInclude(__DIR__ . '/index.tpl.php', array('controllers'=>$controllers), 'primary')
					->addInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
	}

	/**
	* Show a index-page and display what can be done through this controller.
	*/
	public function install() 
	{
	$modules = new CMModules();
	$results = $modules->install();
	$allModules = $modules->readAndAnalyse();
	$this->views->setTitle('Install Modules');
	
	$this->views->addInclude(__DIR__ . '/install.tpl.php', array('modules' => $results), 'primary')
				->addInclude(__DIR__ . '/sidebar.tpl.php', array('modules' => $allModules), 'sidebar');
	}


	/**
	* Show a module and its parts.
	*/
	public function view($module) 
	{
		if(!preg_match('/^C[a-zA-Z]+$/', $module)) 
			throw new Exception('Invalid characters in module name.');
			
		$modules		= new CMModules();
		$controllers	= $modules->availableControllers();
		$allModules		= $modules->readAndAnalyse();
		$aModule		= $modules->readAndAnalyseModule($module);
		$this->views->setTitle('Manage Modules');
		
		$this->views->addInclude
		(
			__DIR__ . '/view.tpl.php', 
			array
			(
				'module'=>$aModule
			), 
			'primary'
		);
		
		$this->views->addInclude
		(
			__DIR__ . '/sidebar.tpl.php', 
			array
			(
				'modules'=>$allModules
			), 
			'sidebar'
		);
	}	
}