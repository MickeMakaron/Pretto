<?php
/**
* A page controller to display a page, for example an about-page, displays content labelled as "page".
*
* @package PrettoCore
*/
class CCPage extends CObject implements IController 
{
	public function __construct() 
	{
		parent::__construct();
	}


	/**
	* Display an empty page.
	*/
	public function index() 
	{
		$content = new CMContent();
		$this->views->setTitle('Page');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'content' => null,
			)
		);
	}


	/**
	* Display a page.
	*
	* @param $id integer the id of the page.
	*/
	public function view($id = null) 
	{
		$content = new CMContent($id);
		$this->views->setTitle('Page: '.htmlEnt($content['title']));
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'content' => $content,
			)
		);
	}


} 