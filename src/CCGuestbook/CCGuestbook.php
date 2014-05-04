<?php
/**
* Example code of a guest book.
*
* @package PrettoCore
*/
class CCGuestbook extends CObject implements IController
{
	private $guestbookModel;

	public function __construct() 
	{
		parent::__construct();
		$this->guestbookModel = new CMGuestbook();
	}

	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function index() 
	{   
		$this->views->setTitle('Guestbook');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'entries'=>$this->guestbookModel->ReadAll(), 
				'formAction'=>$this->request->createUrl('guestbook/handleEvent')
			)
		);
	}

	/**
	* Handle post events.
	**/
	public function handleEvent()
	{
		if(isset($_POST['doAdd']))
			$this->guestbookModel->add(strip_tags($_POST['newEntry']));
		elseif(isset($_POST['doClear']))
			$this->guestbookModel->deleteAll();
		elseif(isset($_POST['doCreate']))
		{
			$res = $this->guestbookModel->manage('install');
			$this->addMessage($res[0], $res[1]);
		}
		$this->redirectTo('guestbook');
	}
}