<?php
class CFormElementText extends CFormElement 
{
	public function __construct($name, $attributes=array()) 
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'text';
		$this->useNameAsDefaultLabel();
	}
}