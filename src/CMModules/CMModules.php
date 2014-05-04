<?php
/**
* A model for managing Pretto modules.
*
* @package PrettoCore
*/
class CMModules extends CObject 
{
	private $prettoCoreModules = array('CLydia', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject');
	private $prettoCMFModules = array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier');



	public function __construct() 
	{ 
		parent::__construct(); 
	}
	

	/**
	* A list of all available controllers/methods
	*
	* @returns array list of controllers (key) and an array of methods
	*/
	public function availableControllers() 
	{ 
		$controllers = array();
		
		foreach($this->config['controllers'] as $key => $val) 
		{
			if($val['enabled']) 
			{
				$rc = new ReflectionClass($val['class']);
				$controllers[$key] = array();
				$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
				foreach($methods as $method) 
				{
					if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'index') 
					{
						$methodName = mb_strtolower($method->name);
						$controllers[$key][] = $methodName;
					}
				}
				
				sort($controllers[$key], SORT_LOCALE_STRING);
			}
		}
		
		ksort($controllers, SORT_LOCALE_STRING);
		
		return $controllers;
	}
	
	/**
	* Read and analyse all modules.
	*
	* @returns array with an entry for each module with the module name as the key.
	* Returns boolean false if $src can not be opened.
	*/
	public function readAndAnalyse() 
	{
		$src = PRETTO_INSTALL_PATH.'/src';
		if(!$dir = dir($src)) 
			throw new Exception('Pretto src path invalid.');
			
		$modules = array();
		while(($module = $dir->read()) !== false)
		{
			if(is_dir("$src/$module")) 
			{
				if(class_exists($module)) 
				{
					$rc = new ReflectionClass($module);
					
					$modules[$module]['name']          = $rc->name;
					$modules[$module]['interface']     = $rc->getInterfaceNames();
					$modules[$module]['isController']  = $rc->implementsInterface('IController');
					$modules[$module]['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
					$modules[$module]['hasSQL']        = $rc->implementsInterface('ISQL');
					$modules[$module]['isPrettoCore']   = in_array($rc->name, $this->prettoCoreModules);
					$modules[$module]['isPrettoCMF']    = in_array($rc->name, $this->prettoCMFModules);
					$modules[$module]['isManageable']  = $rc->implementsInterface('IModule');
				}
			}
		}
		
		$dir->close();
		ksort($modules, SORT_LOCALE_STRING);
		
		return $modules;
	}
	
	
	/**
	* Install all modules.
	*
	* @returns array with a entry for each module and the result from installing it.
	*/
	public function install() 
	{
		$allModules = $this->readAndAnalyse();
		uksort
		(
			$allModules, 
			function($a, $b)
			{
				if($a == 'CMConfig')
					return -1;
				elseif($b == 'CMConfig')
					return 1;
					
			
				return ($a == 'CMUser' ? -1 : ($b == 'CMUser' ? 1 : 0));
			}
        );
		$installed = array();
		foreach($allModules as $module) 
		{
			if($module['isManageable']) 
			{
				$classname = $module['name'];
				$rc = new ReflectionClass($classname);
				$obj = $rc->newInstance();
				$method = $rc->getMethod('manage');
				
				$installed[$classname]['name']    = $classname;
				$installed[$classname]['result']  = $method->invoke($obj, 'install');
			}
		}
		
		return $installed;
	}

	
	/**
	* Get info and details about a module.
	*
	* @param $module string with the module name.
	* @returns array with information on the module.
	*/
	private function getModuleDetails($module) 
	{
		$details = array();
		if(class_exists($module)) 
		{
			$rc = new ReflectionClass($module);
			
			$details['name']				= $rc->name;
			$details['filename']			= $rc->getFileName();
			$details['doccomment']			= $rc->getDocComment();
			$details['interface']			= $rc->getInterfaceNames();
			$details['isController']		= $rc->implementsInterface('IController');
			$details['isModel']				= preg_match('/^CM[A-Z]/', $rc->name);
			$details['hasSQL']				= $rc->implementsInterface('ISQL');
			$details['isManageable']		= $rc->implementsInterface('IModule');
			$details['isPrettoCore']		= in_array($rc->name, $this->prettoCoreModules);
			$details['isPrettoCMF']			= in_array($rc->name, $this->prettoCMFModules);
			$details['publicMethods']		= $rc->getMethods(ReflectionMethod::IS_PUBLIC);
			$details['protectedMethods']	= $rc->getMethods(ReflectionMethod::IS_PROTECTED);
			$details['privateMethods']		= $rc->getMethods(ReflectionMethod::IS_PRIVATE);
			$details['staticMethods']		= $rc->getMethods(ReflectionMethod::IS_STATIC);
		}
		
		return $details;
	}
	
	/**
	* Get info and details about the methods of a module.
	*
	* @param $module string with the module name.
	* @returns array with information on the methods.
	*/
	private function getModuleMethodDetails($module) 
	{
		$methods = array();
		if(class_exists($module)) 
		{
			$rc = new ReflectionClass($module);
			$classMethods = $rc->getMethods();
			
			foreach($classMethods as $val) 
			{
				$methodName = $val->name;
				$rm = $rc->GetMethod($methodName);
				
				$methods[$methodName]['name']          = $rm->getName();
				$methods[$methodName]['doccomment']    = $rm->getDocComment();
				$methods[$methodName]['startline']     = $rm->getStartLine();
				$methods[$methodName]['endline']       = $rm->getEndLine();
				$methods[$methodName]['isPublic']      = $rm->isPublic();
				$methods[$methodName]['isProtected']   = $rm->isProtected();
				$methods[$methodName]['isPrivate']     = $rm->isPrivate();
				$methods[$methodName]['isStatic']      = $rm->isStatic();
			}
		}
		
		ksort($methods, SORT_LOCALE_STRING);
		
		return $methods;
	}
	
	/**
	* Get info and details about a module.
	*
	* @param $module string with the module name.
	* @returns array with information on the module.
	*/
	public function readAndAnalyseModule($module)
	{
		$details = $this->getModuleDetails($module);
		$details['methods'] = $this->getModuleMethodDetails($module);
		
		return $details;
	}
}