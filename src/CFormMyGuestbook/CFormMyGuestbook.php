<?php
/**
* Form for the guestbook
*/
class CFormMyGuestbook extends CForm 
{
	private $object;


	public function __construct($object) 
	{
		parent::__construct();
		
		$this->object = $object;
		$this	->addElement(new CFormElementTextarea('data', array('label'=>'Add entry:')))
				->addElement(new CFormElementSubmit('add', array('callback'=>array($this, 'doAdd'), 'callback-args'=>array($object))));
	}


	/**
	* Callback to add the form content to database.
	*/
	public function doAdd($form, $object) 
	{
	return $object->add(strip_tags($form['data']['value']));
	}
}
