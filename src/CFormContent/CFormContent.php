<?php
/**
* A form to manage content.
*
* @package PrettoCore
*/
class CFormContent extends CForm 
{
	private $content;

	public function __construct($content) 
	{
		parent::__construct();
		$this->content = $content;
		$save = isset($content['id']) ? 'save' : 'create';
		$this	->addElement(new CFormElementHidden('id', array('value'=>$content['id'])))
				->addElement(new CFormElementText('title', array('value'=>$content['title'])))
				->addElement(new CFormElementText('key', array('value'=>$content['key'])))
				->addElement(new CFormElementTextarea('data', array('label'=>'Content:', 'value'=>$content['data'])))
				->addElement(new CFormElementText('type', array('value'=>$content['type'])))
				->addElement(new CFormElementText('filter', array('value'=>$content['filter'])))
				->addElement(new CFormElementSubmit($save, array('callback'=>array($this, 'doSave'), 'callback-args'=>array($content))));
				
		if($save == 'save')
		{
			$this->addElement(new CFormElementDelete('delete', array('callback'=>array($this, 'doDelete'), 'callback-args'=>array($content))));
			$this['save']['appendNewline'] = false;
			$this['delete']['prependNewline'] = false;
		}

		$this	->setValidation('title', array('not_empty'))
				->setValidation('key', array('not_empty'));
	}


	/**
	* Callback to save the form content to database.
	*/
	public function doSave($form, $content) 
	{
		$content['id']    = $form['id']['value'];
		$content['title'] = $form['title']['value'];
		$content['key']   = $form['key']['value'];
		$content['data']  = $form['data']['value'];
		$content['type']  = $form['type']['value'];
		$content['filter'] = $form['filter']['value'];
		return $content->save();
	}
	
	/**
	* Callback to delete content specified by id
	*/
	public function doDelete($form, $content)
	{
		$content['id'] = $form['id']['value'];
		return $content->delete();
	}


}