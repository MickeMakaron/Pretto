<?php
/**
* A blog controller to display a blog-like list of all content labelled as "post".
*
* @package PrettoCore
*/
class CCBlog extends CObject implements IController 
{
	public function __construct() 
	{
		parent::__construct();
	}


	/**
	* Display all content of the type "post".
	*/
	public function index() 
	{
		$content = new CMContent();
		$this->views->setTitle('Blog');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'contents' => $content->listAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'ASC')),
			)
		);
	}


} 