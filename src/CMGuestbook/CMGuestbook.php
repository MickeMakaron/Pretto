<?php
/**
* Controller model for a guest book.
*
* @package PrettoCore
*/
class CMGuestbook extends CObject implements ISQL, IModule
{
	public function __construct() 
	{
		parent::__construct();
	}

	/**
	* Implementing interface ISQL. Encapsulate all SQL used by this class.
	*
	* @param string $key the string that is the key of the wanted SQL-entry in the array.
	*/
	public static function sql($key=null) 
	{
		$queries = array
		(
<<<<<<< HEAD
			'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `entry` TEXT, `created` TIMESTAMP default NOW());",
			'insert into guestbook'   => 'INSERT INTO Guestbook (`entry`) VALUES (?);',
			'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY `id` DESC;',
=======
			'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
			'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
			'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
			'delete from guestbook'   => 'DELETE FROM Guestbook;',
		);
		
		if(!isset($queries[$key])) 
			throw new Exception("No such SQL query, key '$key' was not found.");

		return $queries[$key];
	}


	/**
	* Implementing interface IModule. Manage install/update/deinstall and equal actions.
	*/
	public function manage($action = null) 
	{
		switch($action)
		{
			case 'install':
				try 
				{
					$this->db->executeQuery(self::sql('create table guestbook'));
					return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');
				} 
				catch(Exception $e) 
				{
					die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
				}
				break;
			default:
				throw new Exception('Unsupported action for this module.');
				break;
		}
	}

	
	/**
	* Add a new entry to the guest book and save to database.
	*/
	public function add($entry) 
	{
		$this->db->executeQuery(self::sql('insert into guestbook'), array($entry));
		$this->session->addMessage('success', 'Successfully inserted new message.');
		if($this->db->rowCount() != 1)
			die('Failed to insert new guest book item into database.');
	}


	/**
	* Delete all entries from the guest book and database.
	*/
	public function deleteAll() {
	$this->db->executeQuery(self::sql('delete from guestbook'));
	$this->session->addMessage('info', 'Removed all messages from the database table.');
	}


	/**
	* Read all entries from the guest book and database.
	*/
	public function readAll() 
	{
		try 
		{
			return $this->db->selectAndFetchAll(self::sql('select * from guestbook'));
		} 
		catch(Exception $e) 
		{
			return array();   
		}
	}


} 