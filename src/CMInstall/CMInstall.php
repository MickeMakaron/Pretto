<?php
/**
* A model for installing Pretto.
*
* @package PrettoCore
*/
class CMInstall extends CObject
{
	public $db;
	
	public function __construct() 
	{ 
		parent::__construct(); 
	}
	
	
	
	/**
	* Creates notifications for how ready the server is to install Pretto.
	* Checks the following :
	*	- PHP version
	*	- PDO extension installed
	*	- /data, /data/.ht.sqlite and /themes/grid writeability.
	*/
	public function checkReadiness()
	{
		$notifications = array();
		
		if(version_compare(phpversion(), '5.5.11', '<'))
			$notifications[] = $this->createMessageHTML('error', "Your web server's PHP is not up-to-date. Pretto requires at least 5.5.11.");
			
		if(!extension_loaded('PDO'))
			$notifications[] = $this->createMessageHTML('error', "PDO extension is not installed. Please install PDO for your PHP.");
			
		
			
		if(!$notifications)
		{
			$notifications[] = $this->createMessageHTML('info', "Pretto supports only MySQL. It is recommended that you use MySQL, since Pretto has not been tested with other drivers.");

			if(!is_writable(PRETTO_INSTALL_PATH . '/db.php'))
				$notifications[] = $this->createMessageHTML('warning', "If you intend add database information through the form, db.php, located in the root of your website, must be writeable (666).");
		}
		
		return $notifications;
	}
	
	
	

	private function initializeDatabase($dsn, $user = null, $password = null)
	{
		
	
	}
	
	public function editConfig($driver, $host, $db, $user, $password)
	{
		if(is_writeable($this->config['database']['config']))
		{
			$driver 	= empty($driver)	? 'null' : $driver;
			$host	 	= empty($host)		? 'null' : $host;
			$db 		= empty($db)		? 'null' : $db;
			$user 		= empty($user)		? 'null' : $user;
			$password 	= empty($password)	? 'null' : $password;
			
			file_put_contents($this->config['database']['config'],
			'<?php
				$driver = '."'".$driver."'".';
				$host = '."'".$host."'".';
				$db = '."'".$db."'".';
				$user = '."'".$user."'".';
				$password = '."'".$password."'".';
			');
			
			return true;
		}
		
		return false;	
	}
	
	public function tryDatabaseConnection($driver, $host, $db, $user, $password)
	{
		$dsn = "{$driver}:host={$host};dbname={$db};";
		$db = CDatabase::instance("{$driver}:host={$host};dbname={$db}", $user, $password);
		
		if($db)
		{
			$this->db = $db;
			return true;
		}
		
		return false;
	}
	
}