<?php
class CFormElementSubmit extends CFormElement 
{
	public function __construct($name, $attributes=array()) 
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'submit';
		$this->useNameAsDefaultValue();
	}
}