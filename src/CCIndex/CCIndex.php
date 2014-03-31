<?php
/**
* Standard controller layout.
*
* @package PrettoCore
*/
class CCIndex implements IController 
{
	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function Index() 
	{   
		global $pr;
		$pr->data['title'] = "The Index Controller";
	}

} 