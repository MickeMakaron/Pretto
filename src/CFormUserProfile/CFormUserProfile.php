<?php 
/**
* A form for editing the user profile.
*
* @package PrettoCore
*/
class CFormUserProfile extends CForm 
{
	public function __construct($object, $user) 
	{
		parent::__construct();
		$this	->addElement(new CFormElementText('acronym', array('readonly'=>true, 'value'=>$user['acronym'])))
				->addElement(new CFormElementPassword('password'))
				->addElement(new CFormElementPassword('password1', array('label'=>'Password again:')))
				->addElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'doChangePassword'))))
				->addElement(new CFormElementText('name', array('value'=>$user['name'], 'required'=>true)))
				->addElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
				->addElement(new CFormElementSubmit('save', array('callback'=>array($object, 'doProfileSave'))))
				->addElement(new CFormElementDelete('delete', array('callback'=>array($object, 'doProfileDelete'))));
				
		$this['save']['appendNewline'] = false;
		$this['delete']['prependNewline'] = false;
				
		$this	->setValidation('name', array('not_empty'))
				->setValidation('email', array('not_empty'));
	}

}
