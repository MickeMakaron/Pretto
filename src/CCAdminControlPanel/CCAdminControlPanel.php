<?php
/**
* Admin Control Panel to manage admin stuff.
*
* @package LydiaCore
*/
class CCAdminControlPanel extends CObject implements IController 
{
	public function __construct() 
	{
		parent::__construct();
	}


	/**
	* Show profile information of the user.
	*/
	public function index() 
	{
		$this->views->setTitle('ACP: Admin Control Panel');
		$this->views->addInclude(__DIR__ . '/index.tpl.php');
	}


} 