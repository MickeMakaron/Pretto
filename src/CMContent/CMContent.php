<?php
/**
* A model for content stored in database.
*
* @package PrettoCore
*/
class CMContent extends CObject implements ISQL, ArrayAccess, IModule
{
	public $data;

	public function __construct($id = null) 
	{
		parent::__construct();
		
		if($id)
			$this->loadById($id);
		else
			$this->data = array();
	}


	/**
	* Implementing ArrayAccess for $this->data
	*/
	public function offsetSet($offset, $value) 
	{ 
		if (is_null($offset)) 
			$this->data[] = $value;
		else 
			$this->data[$offset] = $value;
	}
	
	public function offsetExists($offset) 
	{ 
		return isset($this->data[$offset]); 
	}
	
	public function offsetUnset($offset) 
	{ 
		unset($this->data[$offset]); 
	}
	
	public function offsetGet($offset) 
	{ 
		return isset($this->data[$offset]) ? $this->data[$offset] : null; 
	}


	/**
	* Implementing interface ISQL. Encapsulate all SQL used by this class.
	*
	* @param string $key the string that is the key of the wanted SQL-entry in the array.
	*/
	public static function sql($key = null, $args = null)
	{
		$order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
		$order_by = isset($args['order-by']) ? $args['order-by'] : 'id'; 
		$queries = array
		(
			'drop table content' => "DROP TABLE IF EXISTS Content;",
<<<<<<< HEAD
			'create table content' => "CREATE TABLE IF NOT EXISTS Content (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `key` TEXT, `type` TEXT, `title` TEXT, `data` TEXT, `filter` TEXT, `idUser` INT, `created` TIMESTAMP default NOW(), `updated` TIMESTAMP, `deleted` TIMESTAMP, FOREIGN KEY(`idUser`) REFERENCES User(`id`));",
			'insert content' => 'INSERT INTO Content (`key`,`type`,`title`,`data`,`filter`,`idUser`) VALUES (?,?,?,?,?,?);',
=======
			'create table content' => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
			'insert content' => 'INSERT INTO Content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
			'select * by id' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
			'select * by key' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;',
			'select * by type' => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",
			'select *' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
<<<<<<< HEAD
			'update content' => "UPDATE Content SET `key`=?, `type`=?, `title`=?, `data`=?, `filter`=?, `updated`=NOW() WHERE `id`=?;",
			'delete content' => "DELETE FROM Content WHERE `id`=?;",
=======
			'update content' => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
			'delete content' => "DELETE FROM Content WHERE id=?;",
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
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
					$this->db->ExecuteQuery(self::SQL('drop table content'));
					$this->db->ExecuteQuery(self::SQL('create table content'));
					$this->db->executeQuery(self::sql('insert content'), array('hello-world', 'post', 'Hello World', 'This is a demo post.', 'plain', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('page1', 'page', 'Page1', 'pagepagepagepage', 'plain', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('hellow aouwrld 2', 'post', 'Hi thar', 'huhuhuhhuh', 'plain', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('a', 'post', 'A', 'huhuhuhhuh', 'plain', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('b', 'post', 'B', 'huhuhuhhuh', 'plain', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('c', 'post', 'C', 'huhuhuhhuh', 'plain', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('BBCODE', 'post', 'BBCODE', "[b]bababababold[/b]\n[i]kikikikikikursiv[/i]", 'bbcode', $this->user['id']));
					$this->db->executeQuery(self::sql('insert content'), array('HTMLPurify', 'post', 'HTMLPurify', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", 'htmlpurify', $this->user['id']));
			
					return array('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
				} 
				catch(Exception$e) 
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
	* Save content. If it has a id, use it to update current entry or else insert new entry.
	*
	* @returns boolean true if success else false.
	*/
	public function save()
	{
		$msg = null;
		if($this['id']) 
		{
			$this->db->executeQuery(self::sql('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
			$msg = 'update';
		} 
		else 
		{
			$this->db->executeQuery(self::sql('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user['id']));
			$this['id'] = $this->db->lastInsertId();
			$msg = 'created';
		}
		
		$rowcount = $this->db->rowCount();
		if($rowcount) 
			$this->addMessage('success', "Successfully {$msg} content '{$this['key']}'.");
		else
			$this->addMessage('error', "Failed to {$msg} content '{$this['key']}'.");

		return $rowcount === 1;
	}

	/** 
	* Delete content specified by id.
	*
	* $returns boolean true if success else false.
	*/
	public function delete()
	{
		$this->db->executeQuery(self::sql('delete content'), array($this['id']));
		
		$rowcount = $this->db->rowCount();
		if($rowcount) 
			$this->addMessage('success', "Successfully deleted content '{$this['key']}'.");
		else
			$this->addMessage('error', "Failed to delete content '{$this['key']}'.");

		return $rowcount === 1;
	}
	
	

	/**
	* Load content by id.
	*
	* @param id integer the id of the content.
	* @returns boolean true if success else false.
	*/
	public function loadById($id) 
	{
		$res = $this->db->selectAndFetchAll(self::sql('select * by id'), array($id));
		if(empty($res)) 
		{
			$this->addMessage('error', "Failed to load content with id '$id'.");
			return false;
		} 
		else 
			$this->data = $res[0];

		return true;
	}


	/**
	* List all content.
	*
	* @returns array with listing or null if empty.
	*/
	public function listAll($args = null) 
	{
		try 
		{
			if(isset($args) && isset($args['type']))
				return $this->db->selectAndFetchAll(self::sql('select * by type', $args), array($args['type']));
			else
				return $this->db->selectAndFetchAll(self::sql('select *', $args));
		} 
		catch(Exception$e) 
		{	
			echo "Exception catch at CMContent::listAll()";
			return null;
		}
	}

	/**
	* Filter content according to a filter.
	*
	* @param $data string of text to filter and format according its filter settings.
	* @returns string with the filtered data.
	*/
	public static function filter($data, $filter) 
	{
		switch($filter) 
		{
		/*	case 'php': 
				$data = nl2br(makeClickable(eval('?>'.$data))); 
				break;
			case 'html': 
				$data = nl2br(makeClickable($data)); 
				break; */
			case 'htmlpurify': 
				$data = nl2br(CHTMLPurifier::Purify($data)); 
				break;
			case 'bbcode': 
				$data = nl2br(bbcode2html(htmlEnt($data))); 
				break;
			case 'plain':
			default: 
				$data = nl2br(makeClickable(htmlEnt($data))); 
				break;
		}
		return $data;
	}


	/**
	* Get the filtered content.
	*
	* @returns string with the filtered data.
	*/
	public function getFilteredData() 
	{
		return $this->filter($this['data'], $this['filter']);
	}
}