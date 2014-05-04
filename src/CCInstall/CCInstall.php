<?php
/**
* Install Pretto
*
* @package PrettoCore
*/
class CCInstall extends CObject implements IController 
{
	private $install;
	
	public function __construct() 
	{
		parent::__construct();;
		$this->install = new CMInstall();
	}

	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function index() 
	{        
		
		$notifications = $this->install->checkReadiness();
		
		$form = new CForm();		
		$form	->addElement(new CFormElementText('driver'))
				->addElement(new CFormElementText('host'))
				->addElement(new CFormElementText('databasename'))
				->addElement(new CFormElementText('username'))
				->addElement(new CFormElementPassword('password'))
				->addElement(new CFormElementSubmit('save', array('callback'=>array($this, 'doSave'))));
				
		$form->check();
		

		$defaultDatabaseInfo = array
		(
			'driver' => array('value' => $this->config['database']['default']['driver']),
			'host' => array('value' => $this->config['database']['default']['host']),
			'databasename' => array('value' => $this->config['database']['default']['db']),
			'username' => array('value' => $this->config['database']['default']['user']),
			'password' => array('value' => $this->config['database']['default']['password']),
		);
		
		$simpleform = new CForm();
		$simpleform->addElement(	new CFormElementSubmit
									(
										'Magic', 
										array
										(
											'callback'=>array($this, 'doSave'), 
											'callback-args' => array($defaultDatabaseInfo)
										)
									)
								);

		$simpleform->check();
		
		
		$initForm = new CForm();
		$initForm->addElement(	new CFormElementSubmit
									(
										'Initialize', 
										array
										(
											'callback'=>array($this, 'initializeModules'), 
											'callback-args' => array($defaultDatabaseInfo)
										)
									)
								);

		$initForm->check();
		
		
		$this->views->setTitle('Install Pretto');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array('form' => $form->getHTML(), 'simpleform' => $simpleform->getHTML(), 'initForm' => $initForm->getHTML()),
			'primary'
		)
		->addInclude
		(
			__DIR__ . '/sidebar.tpl.php',
			array('notifications' => $notifications),
			'sidebar'
		);
		
    }
	
	public function doSave($form, $defaultDatabaseInfo = null)
	{	
		if($defaultDatabaseInfo)
			$form = $defaultDatabaseInfo;
		
		$driver = $form['driver']['value'];
		$host = $form['host']['value'];
		$db = $form['databasename']['value'];
		$user = $form['username']['value'];
		$password = $form['password']['value'];
		
		if(is_writeable($this->config['database']['config']))
		{	
			if($this->install->tryDatabaseConnection($driver, $host, $db, $user, $password))
			{
					$this->install->editConfig($driver, $host, $db, $user, $password);
					$this->createMessage('success', 'Database is up and running! Continue the installation instructions to initialize data.');
			}
			else
			{
				$this->createMessage('error', "Could not connect to database. Please double-check your input.");
			}
		}
		else
		{
			$this->createMessage('error', 'db.php is not writeable. Please allow writing (666).');
		}

	}
	
	public function initializeModules($form)
	{
		if(!$this->db)
		{
			$this->createMessage('error', "Database connection is missing. You must set up a database before initializing one. You can't initialize something you don't have, ya dingus!");
			return false;
		}
	
		$initTable = __DIR__ . '/inittable.tpl.php';
		
		$modules = new CMModules();
		$results = $modules->install();
		
		$this->views->addVariable('primary', array('initTable' => htmlspecialchars($initTable), 'modules' => $results));
		
	}
} 