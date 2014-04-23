<?php
class CFormElementPassword extends CFormElement 
{
	public function __construct($name, $attributes=array()) 
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'password';
		$this->useNameAsDefaultLabel();
	}
}