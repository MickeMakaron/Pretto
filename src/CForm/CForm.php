<?php
/**
* A utility class to easy creating and handling of forms
*
* @package PrettoCore
*/
class CForm implements ArrayAccess
{
	public $form		= array();     // array with settings for the form
	public $elements	= array(); 	// array with all form elements

	public function __construct($form = array(), $elements = array()) 
	{
		$this->form = $form;
		$this->elements = $elements;
	}

	/**
	* Implementing ArrayAccess for this->attributes
	*/
	public function offsetSet($offset, $value) 
	{ 
		if (is_null($offset)) 
			$this->elements[] = $value;
		else 
			$this->elements[$offset] = $value;
	}
	public function offsetExists($offset) 
	{ 
		return isset($this->elements[$offset]); 
	}
	public function offsetUnset($offset) 
	{ 
		unset($this->elements[$offset]); 
	}
	public function offsetGet($offset) 
	{ 
		return isset($this->elements[$offset]) ? $this->elements[$offset] : null; 
	}

	
	/**
	* Add a form element
	*/
	public function addElement($element) 
	{
		$this[$element['name']] = $element;
		return $this;
	}

	/**
	* Return HTML for the elements
	*/
	public function getHTMLForElements() 
	{
		$html = null;
		foreach($this->elements as $element)
			$html .= $element->getHTML();
 
		return $html;
	}

	/**
	* Return HTML for the form
	*/
	public function getHTML($type = null) 
	{
		$id 		= isset($this->form['id']) ? " id='{$this->form['id']}'" : null;
		$class 		= isset($this->form['class']) ? " class='{$this->form['class']}'" : null;
		$name		= isset($this->form['name']) ? " name='{$this->form['name']}'" : null;
		$action		= isset($this->form['action']) ? " action='{$this->form['action']}'" : null;
		$method		= " method='post'";
		
		if($type == 'form')
			return "<form{$id}{$class}{$name}{$action}{$method}>";		
		
		$elements	= $this->getHTMLForElements();
		$html		= <<< EOD
			\n<form{$id}{$class}{$name}{$action}{$method}>
				<fieldset>
					{$elements}
				</fieldset>
			</form>
EOD;
		return $html;
	}
	
	/**
	* Get the value of a element
	*/
	public function getValue($key) 
	{
		return (isset($_POST[$key])) ? $_POST[$key] : null;
	}

	
	/**
	* Check if a form was submitted and perform validation and call callbacks.
	*
	* The form is stored in the session if validation fails. The page should then be redirected
	* to the original form page, the form will populate from the session and should then be
	* rendered again.
	*
	* @returns boolean true if validates, false if not validate, null if not submitted.
	*/
	public function check() 
	{
		$res = null;
		$values = array();
		$callbackStatus = null;
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			unset($_SESSION['form-failed']);
			$res['validates'] = true;
			
			foreach($this->elements as $element) 
			{
				if(isset($_POST[$element['name']])) 
				{
					$values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
					if(isset($element['validation'])) 
					{
						$element['validation-pass'] = $element->validate($element['validation']);
						if($element['validation-pass'] === false) 
						{
							$values[$element['name']] = array('value'=>$element['value'], 'validation_messages'=>$element['validation_messages']);
							$res['validates'] = false;
						}
					}
					if(isset($element['callback']) && $res['validates']) 
					{
						$res['callback'] = $element['callback'][1];
						if(isset($element['callback-args'])) 
						{
							
							if(call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args'])) === false) 
								$callbackStatus = false;
						} 
						else 
						{
							if(call_user_func($element['callback'], $this) === false)
								$callbackStatus = false;
						}
					}
				}
			}
		} 
		else if(isset($_SESSION['form-failed'])) 
		{
			foreach($_SESSION['form-failed'] as $key => $val) 
			{	
				$this[$key]['value'] = $val['value'];
				if(isset($val['validation_messages'])) 
				{
					$this[$key]['validation_messages'] = $val['validation_messages'];
					$this[$key]['validation-pass'] = false;
				}
			}
			unset($_SESSION['form-failed']);
		}
		if($res['validates'] === false)
			$_SESSION['form-failed'] = $values;

		return $res;
	}
	
	/**
	* Set validation to a form element
	*
	* @param $element string the name of the form element to add validation rules to.
	* @param $rules array of validation rules.
	* @returns $this CForm
	*/
	public function setValidation($element, $rules) 
	{
		$this[$element]['validation'] = $rules;
		return $this;
	}
}