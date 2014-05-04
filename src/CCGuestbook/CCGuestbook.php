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
		$this->views->setTitle('Lydia Guestbook Example');
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
<<<<<<< HEAD
		{
			$res = $this->guestbookModel->manage('install');
			$this->addMessage($res[0], $res[1]);
		}
=======
			$this->guestbookModel->init();        
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
		$this->redirectTo('guestbook');
	}
}