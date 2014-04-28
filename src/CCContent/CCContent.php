<?php
/**
* A user controller to manage content.
*
* @package PrettoCore
*/
class CCContent extends CObject implements IController 
{
	public function __construct() 
	{ 
		parent::__construct(); 
	}


	/**
	* Show a listing of all content.
	*/
	public function index() 
	{
		$content = new CMContent();
		$this->views->setTitle('Content Controller');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'contents' => $content->listAll(),
			)
		);
	}


	/**
	* Edit a selected content, or prepare to create new content if argument is missing.
	*
	* @param id integer the id of the content.
	*/
	public function edit($id = null) 
	{
		$content = new CMContent($id);
		$form = new CFormContent($content);
		$status = $form->check();
		

		if($status['callback'] == 'doSave')
		{
			if($status['validates'] === false) 
			{
				$this->addMessage('notice', 'The form could not be processed.');
				$this->redirectToController('edit', $id);
			} 
			else if($status['validates'] === true)
				$this->redirectToController('edit', $content['id']);
		}
		elseif($status['callback'] == 'doDelete')
		{
			if($status['validates'] === false) 
			{
				$this->addMessage('notice', 'The form could not be processed.');
				$this->redirectToController('edit', $id);
			} 
			else if($status['validates'] === true)
				$this->redirectToController();
		}


		$title = isset($id) ? 'Edit' : 'Create';
		$this->views->setTitle("$title content: $id");
		$this->views->addInclude
		(
			__DIR__ . '/edit.tpl.php', 
			array
			(
				'user'=>$this->user,
				'content'=>$content,
				'form'=>$form,
			)
		);
	}


	/**
	* Create new content.
	*/
	public function create() 
	{
		$this->edit();
	}


	/**
	* Init the content database.
	*/
	public function init() 
	{
		$content = new CMContent();
		$content->init();
		$this->redirectToController();
	}


} 