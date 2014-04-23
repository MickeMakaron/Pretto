<?php
/**
* A test controller for themes.
*
* @package PrettoCore
*/
class CCTheme extends CObject implements IController 
{

	public function __construct() 
	{ 
		parent::__construct(); 
		$this->views->addStyle('body:hover{background:#fff url('.$this->request->base_url.'themes/grid/grid_12_60_20.png) repeat-y center top;}');
	}

	/**
	* Display what can be done with this controller.
	*/
	public function index() 
	{
		$this->views->setTitle('Theme');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'theme_name' => $this->config['theme']['name'],
			)
		);
	}

	/**
	* Put content in some regions.
	*/
	public function someRegions() 
	{
		$this->views->setTitle('Theme display content for some regions');
		$this->views->addString('This is the primary region', array(), 'primary');
		$this->views->addInclude
		(
			__DIR__ . '/some.tpl.php',
			array
			(
				'regions' => $this->config['theme']['regions'],
			),
			'primary'
		);
		
		if(func_num_args()) 
		{
			$args = array_unique(func_get_args());
			foreach($args as $val) 
			{
				$this->views->addString("This is region: $val", array(), $val);
				$this->views->addStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
			}
		}
		
	}

	/**
	* Put content in all regions.
	*/
	public function allRegions() 
	{
		$this->views->setTitle('Theme display content for all regions');
		
		foreach($this->config['theme']['regions'] as $val)
		{
			$this->views->addString("This is region: $val", array(), $val);
			$this->views->addStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
		}
	}
	
	/**
	* Show test page
	*/
	public function h1h6() 
	{
		$this->views->setTitle('Testing styling');
		
		$this->views->addInclude
		(
			__DIR__ . '/h1h6.tpl.php', 
			array()
		);
	}
} 