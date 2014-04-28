<?php
/**
* Sample controller for a site builder.
*/
class CCMycontroller extends CObject implements IController 
{
	public function __construct() 
	{ 
		parent::__construct(); 
	}


	/**
	* The page about me
	*/
	public function index() 
	{
		$content = new CMContent(5);
		$this->views->setTitle('About me'.htmlEnt($content['title']));
		$this->views->addInclude
		(
			__DIR__ . '/page.tpl.php', 
			array
			(
				'content' => $content,
			)
		);
	}


	/**
	* The blog.
	*/
	public function blog() 
	{
		$content = new CMContent();
		$this->views->setTitle('My blog');
		$this->views->addInclude
		(
			__DIR__ . '/blog.tpl.php', 
			array
			(
				'contents' => $content->listAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),
			)
		);
	}


	/**
	* The guestbook.
	*/
	public function guestbook() 
	{
		$guestbook = new CMGuestbook();
		$form = new CFormMyGuestbook($guestbook);
		$status = $form->check();
		if($status['validates'] === false) 
		{
			$this->addMessage('notice', 'The form could not be processed.');
			$this->redirectToControllerMethod();
		}
		else if($status['validates'] === true)
			$this->redirectToControllerMethod();


		$this->views->setTitle('My Guestbook');
		$this->views->addInclude
		(
			__DIR__ . '/guestbook.tpl.php', 
			array
			(
				'entries'=>$guestbook->readAll(),
				'form'=>$form,
			)
		);
	}


}
