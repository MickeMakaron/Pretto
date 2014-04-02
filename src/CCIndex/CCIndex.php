<?php
/**
* Standard controller layout.
*
* @package PrettoCore
*/
class CCindex implements IController 
{
	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function index() 
	{   
		global $pr;
		$pr->config['theme']['name'] = 'core';
		
		$pr->data['title'] = "The index Controller";
		
		$pr->data['above'] = null;
		$pr->data['main'] = <<<EOD
				<p><a href='developer'>developer</a></p>
				<p><a href='me'>me</a></p>
EOD;
	}

} 