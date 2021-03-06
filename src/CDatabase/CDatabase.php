<?php
/**
* General database interface
*/
class CDatabase
{
<<<<<<< HEAD
	public $isConnected = null;

=======
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	private static $queries = array();
<<<<<<< HEAD
	


	protected function __construct($pdo) 
	{
		$this->db = $pdo;
	}

	
	public static function instance($dsn, $username = null, $password = null, $driver_options = null)
	{
		$pdo = null;
		
		try
		{
			$pdo = new PDO($dsn, $username, $password, $driver_options);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e)
		{	
			return null;
		}
		
		$db = new CDatabase($pdo);
		
		return $db;
	}
=======


	public function __construct($dsn, $username = null, $password = null, $driver_options = null) 
	{
		$this->db = new PDO($dsn, $username, $password, $driver_options);
		$this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5

	/**
	* Set an attribute on the database
	*/
	public function setAttribute($attribute, $value) 
	{
		return $this->db->setAttribute($attribute, $value);
	}


	/**
	* Getters
	*/
	public function getNumQueries() 
	{ 
		return self::$numQueries; 
	}
	public function getQueries() 
	{ 
		return self::$queries; 
	}


	/**
	* Execute a select-query with arguments and return the resultset.
	*/
	public function selectAndFetchAll($query, $params=array())
	{
		$this->stmt = $this->db->prepare($query);
		self::$queries[] = $query;
		self::$numQueries++;
		$this->stmt->execute($params);
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Execute a SQL-query and ignore the resultset.
	*/
	public function executeQuery($query, $params = array()) 
<<<<<<< HEAD
	{	
		$this->stmt = $this->db->prepare($query);
		self::$queries[] = $query;
		self::$numQueries++;

		return $this->stmt->execute($params);
	}

	
=======
	{
		$this->stmt = $this->db->prepare($query);
		self::$queries[] = $query;
		self::$numQueries++;
		return $this->stmt->execute($params);
	}


>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
	/**
	* Return last insert id.
	*/
	public function lastInsertId() 
	{
		return $this->db->lastInsertid();
	}


	/**
	* Return rows affected of last INSERT, UPDATE, DELETE
	*/
	public function rowCount() 
	{
		return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
	}
<<<<<<< HEAD
	
	/**
	* Execute a select-query with arguments and return the resultset.
	*/
	public function tryConnection()
	{
		if(!$this->db)
			return false;
			
		try
		{
			$this->db->prepare("SELECT @@version");
		}
		catch(Exception$e)
		{
			return false;
		};
	}
=======
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
}