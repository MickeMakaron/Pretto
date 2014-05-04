<?php
/**
* Admin Control Panel to manage admin stuff.
*
* @package LydiaCore
*/
class CCAdminControlPanel extends CObject implements IController 
{
	/**
	* A section can contain several config variables.
	* This array indicates where variables will be shown when edited.
	* Where {sectionName} and {variableName} is the name shown on the site.
	*/
	private $sections;
	
	/**
	* Array of form elements. The array has the same structure as config variables, with the exception that every entry (including arrays)
	* owns a key 'acpConfigElement' that holds the form element.
	*/
	private $elements;

	public function __construct() 
	{
		parent::__construct();
		

		
		
		/*$this->sections = array
		(
			'menus' => array('name' => 'Menus', 'variables' => array('menus' => arra),
			'controllers' => array('name' => 'Controllers', 'variables' => array('controllers' => 'Controllers')),
		);*/
	}


	/**
	* Show profile information of the user.
	*/
	public function index() 
	{
		$this->adminCheck();
		
		$config = new CMConfig();
		$var = $config->getValue('allow_browser_access');
		
		$form = new CForm();
		$form->addElement(new CFormElementHidden($var[0]['key'], array('value' => 0, 'var' => $var[0])));
		$form->addElement(new CFormElementSubmit('reset', array('callback'=>array($this, 'reset'))));
		$form->check();
		
		
		$this->views->setTitle('ACP: Admin Control Panel');
		$this->views->addInclude( __DIR__ . '/index.tpl.php', array('form' => $form));
		

		
		$this->addSidebar();
	}


	/*
	* If user is not authorized, show this page.
	*/
	public function error()
	{
		$this->views->setTitle('Authorization required');
		$this->views->addInclude(__DIR__ . '/error.tpl.php');
	}
	
	/*
	* If user is not authorized, redirect to error page.
	*/
	private function adminCheck()
	{
		if(!$this->user['isAdmin'])
		{
			$this->redirectToController('error');
			die();
		}
	}
	

	/**
	* Forms for editing PrettoConfig data.
	*/
	public function edit($variableKey = null)
	{
		$this->adminCheck();
		if($variableKey === null || !array_key_exists($variableKey, $this->config['editable_variables']))
			$this->redirectToController();
		
		$this->views->setTitle($variableKey);
		
		$config = new CMConfig();
		$keys = $config->getVariable($variableKey);

		$form = $this->createFormTextElements($keys, $variableKey);
		
		$form->addElement(new CFormElementSubmit('insert', array('callback'=>array($this, 'doInsert'), 'callback-args' => array($variableKey))));
		$form->addElement(new CFormElementSubmit('save', array('callback'=>array($this, 'doSave'))));
		$form->check();

		$this->views->addInclude
		(
			__DIR__ . '/edit.tpl.php',
			array('variableKey'=>$variableKey, 'elements'=>$this->elements, 'form' => $form, 'include' => __DIR__ . "/edit/" . $variableKey . ".php"),
			'primary'
		);
		$this->views->addStyle(file_get_contents(__DIR__ . "/style.css"));
		
		$this->addSidebar();
	}
	
	/**
	* Add sidebar for navigation
	*/
	private function addSidebar()
	{		
		$this->views->addInclude
		(
			__DIR__ . '/sidebar.tpl.php',
			array('vars' => $this->config['editable_variables']),
			'sidebar'
		);
	
	}
	
	/**
	* Print a variable defined by $key
	*
	* @param $key
	*/
	public function view($key)
	{

	}
	
	
	/**
	* Create custom form for editing config data.
	*/
	private function createFormTextElements($keys, $variableKey)
	{
		$form = new CForm();
	
		function setVar(&$var, $value)
		{
			foreach($var as &$v)
			{
				if(empty($v))
					$v['acpConfigElement'] = $value;
				else
					setVar($v, $value);
			}
		};
		$elements = array();
		foreach($keys as &$key)
		{
				
				$value = $key['type'] == 'array' ? $key['string'] : $key[$key['type']];
				
				if($key['type'] == 'boolean')
					$element = new CFormElementCheckbox($key['key'], array('value'=>$value, 'var' => $key));
				else
					$element = new CFormElementText($key['key'], array('value'=>$value, 'var' => $key));
				

				$config = new CMConfig();
				$basename = $config->keyBasename($key['key']);
				
				if(isset($this->config['editable_variables'][$variableKey]['variables']))
				{
					if(!in_array($basename, $this->config['editable_variables'][$variableKey]['variables']))
						$element['validation'] = array('not_empty');
				}
				else
					$element['validation'] = array('not_empty');
					
			
				$element['label'] = $config->keyBasename($key['key']);
				

				
					$form->addElement($element);
				
				
				$key['type'] = 'array';
				$var = $config->keyToVariable($key);			
				setVar($var, $element);
				
				$elements = array_merge_recursive($elements, $var);
		}
		
		$this->elements = $elements;
		return $form;
	}
	
	public function doInsert($form, $key)
	{
		$config = new CMConfig();
		
		$insertion = array();
		$insertion = $this->config['editable_variables'][$key]['insertion'];
		

		
		$keys = array();
		$config->variableToKey($key, $insertion, $keys);
		$insertionID = null;
		$id = PRETTO_SEPARATOR . "a" . PRETTO_SEPARATOR;

		foreach($keys as $needle => $var)
		{
			$check = strstr($needle, PRETTO_SEPARATOR . 'insertionID' . PRETTO_SEPARATOR, true);
			if($check !== false)
			{
				$insertionID = $check;
				break;
			}
		}
		assert($insertionID !== null);
		
		$id = "a";
		while(!empty($config->getValue($insertionID . PRETTO_SEPARATOR . $id . PRETTO_SEPARATOR . "%")))
			$id .= "a";
		

		findAndReplaceKey($insertion, 'insertionID', $id);

		$keys = array();
		$config->variableToKey($key, $insertion, $keys);


		foreach($keys as $key => $var)
		{
			$config->insert($key, $var);
		}
		

		$this->addMessage('success', 'Successfully inserted to database!');
		$this->redirectToControllerMethodArgs();
	}
	
	public function doSave($form)
	{
		$config = new CMConfig();
		$acp = 'controllers' . PRETTO_SEPARATOR . 'acp' . PRETTO_SEPARATOR . 'enabled';
		if(isset($form->elements[$acp]))
			$form->elements[$acp]->attributes['value'] = 'on';


		if($config->save($form))
			$this->addMessage('success', "Successfully updated config table.");
		else
			$this->addMessage('error', "Failed to update config table.");		

		$this->redirectToControllerMethodArgs();			
	}
	
	public function reset($form)
	{
		$config = new CMConfig();

		$config->save($form);
		if($config->save($form))
			$this->addMessage('success', "Successfully updated config table.");
		else
			$this->addMessage('error', "Failed to update config table.");

		
		$config->manage('install');
		
		$this->redirectToControllerMethod();
	}
	
} 