<?php
/**
* Standard yes/no dialogue.
*
* @package PrettoCore
*/
class CFormDialogue extends CForm 
{
	private $buttons;

	public function __construct($buttons) 
	{
		parent::__construct();
		
		$buttons = (array)$buttons;
		$this->buttons = $buttons;
		
		$size = count($buttons);
		if($size > 1)
		{
			for($i = 0; $i < $size; $i++)
			{
				if($i != $size - 1)
					$this[$buttons[$i]]['appendNewline'] = false;
					
				if($i != 0)
					$this[$buttons[$i]]['prependNewline'] = false;
			}
		}
			
		foreach($buttons as $button)
			$this->addElement(new CFormElementSubmit($button));
	}


	/**
	* Override CForm::check().
	*
	* $return $button name if $button is clicked, false if none is clicked
	*/
	public function check()
	{
		foreach($this->buttons as $button)
			if(isset($_POST[$button]))
				return $button;
				
		return false;
	}


}