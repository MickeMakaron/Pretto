<?php
/**
* Standard controller layout.
*
* @package PrettoCore
*/
class CCIndex extends CObject implements IController 
{
	public function __construct() 
	{
		parent::__construct();;
	}

	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function index() 
	{         
		$this->views->setTitle('Index Controller');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'menu'=>$this->menu()
			)
		);
    }
	  
	  
	/**
	* A menu that shows all available controllers/methods
	*/
	private function menu() 
	{   
		$items = array();
		foreach($this->config['controllers'] as $key => $val) 
			if($val['enabled']) 
			{
				$rc = new ReflectionClass($val['class']);
				$items[$key] = array();
				$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
				foreach($methods as $method) 
					if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'index')
						$items[$key][] = "$key/" . mb_strtolower($method->name);
			}
			
		return $items;
	}
} 