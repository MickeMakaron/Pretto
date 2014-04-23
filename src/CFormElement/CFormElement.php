<?php
/**
* A utility class to easy creating and handling of forms
*
* @package PrettoCore
*/
class CFormElement implements ArrayAccess
{
	public $attributes;
	public $characterEndoding;

	public function __construct($name, $attributes = array()) 
	{
		$this->attributes = $attributes;   
		$this['name'] = $name;
		
		if(is_callable('CPretto::instance()'))
			$this->characterEncoding = CPretto::instance()->config['character_encoding'];
		else
			$this->characterEncoding = 'UTF-8';
	}


	/**
	* Implementing ArrayAccess for this->attributes
	*/
	public function offsetSet($offset, $value) 
	{ 
		if (is_null($offset)) 
			$this->attributes[] = $value;
		else
			$this->attributes[$offset] = $value;
	}
	
	public function offsetExists($offset) 
	{ 
		return isset($this->attributes[$offset]); 
	}
	
	public function offsetUnset($offset) 
	{
		unset($this->attributes[$offset]); 
	}
	
	public function offsetGet($offset) 
	{ 
		return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; 
	}


	/**
	* Get HTML code for a element.
	*
	* @returns HTML code for the element.
	*/
	public function getHTML() 
	{
		$id 			= isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
		$class 			= isset($this['class']) ? " {$this['class']}" : null;
		$validates		= (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
		$class			= (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
		$name			= " name='{$this['name']}'";
		$label			= isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
		$autofocus		= isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;
		$readonly		= isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;
		$type			= isset($this['type']) ? " type='{$this['type']}'" : null;
		$value			= isset($this['value']) ? " value='{$this['value']}'" : null;
		$onlyValue 		= isset($this['value']) ? htmlentities($this['value'], ENT_COMPAT, $this->characterEncoding) : null;
		$appendNewline	= isset($this['appendNewline']) ? ($this['appendNewline'] ? "</p>\n" : null) : "</p>\n"; 
		$prependNewline	= isset($this['prependNewline']) ? ($this['prependNewline'] ? "<p>" : null) : "<p>";

		$messages = null;
		if(isset($this['validation_messages'])) 
		{
			$message = null;
			foreach($this['validation_messages'] as $val) 
				$message .= "<li>{$val}</li>\n";
				
			$messages = "<ul class='validation-message'>\n{$message}</ul>\n";
		}
		
		if($type && $this['type'] == 'submit')
			return "{$prependNewline}<input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$appendNewline}";
		elseif($type && $this['type'] == 'textarea')
			return "{$prependNewline}<label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$onlyValue}</textarea>{$appendNewline}";
		elseif($type && $this['type'] == 'hidden')
			return "<input id='$id'{$type}{$class}{$name}{$value} />\n";
		else
			return "{$prependNewline}<label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}{$appendNewline}";	    
	}


	/**
	* Use the element name as label if label is not set.
	*/
	public function useNameAsDefaultLabel() 
	{
		if(!isset($this['label']))
			$this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
	}


	/**
	* Use the element name as value if value is not set.
	*/
	public function useNameAsDefaultValue() 
	{
		if(!isset($this['value']))
			$this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
	}

	/**
	* Validate the form element value according a ruleset.
	*
	* @param $rules array of validation rules.
	* returns boolean true if all rules pass, else false.
	*/
	public function validate($rules) 
	{
		$tests = array
		(
			'fail' => array
			(
				'message' => 'Will always fail.',
				'test' => 'return false;',
			),
			
			'pass' => array
			(
				'message' => 'Will always pass.',
				'test' => 'return true;',
			),
			
			'not_empty' => array
			(
				'message' => 'Can not be empty.',
				'test' => 'return $value != "";',
			),
		);
		
		$pass 		= true;
		$messages 	= array();
		$value		= $this['value'];
		foreach($rules as $key => $val) 
		{
			$rule = is_numeric($key) ? $val : $key;
			if(!isset($tests[$rule])) 
				throw new Exception('Validation of form element failed, no such validation rule exists.');
				
			if(eval($tests[$rule]['test']) === false) 
			{
				$messages[] = $tests[$rule]['message'];
				$pass = false;
			}
		}
		
		if(!empty($messages)) 
			$this['validation_messages'] = $messages;
			
		return $pass;
	}
}