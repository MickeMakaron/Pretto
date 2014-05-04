<?php
/**
* A supplement to the config file that stores configuration options in the database table PrettoConfig.
* If the same variable is defined both in config.php and PrettoConfig, the variable will be overridden by PrettoConfig.
*
* @package PrettoCore
*/
class CMConfig extends CObject implements ISQL, ArrayAccess, IModule
{
	public $data;

	public function __construct($id = null) 
	{
		parent::__construct();
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
		
			'drop table config' => "DROP TABLE IF EXISTS PrettoConfig;",
			'create table config' => "CREATE TABLE IF NOT EXISTS PrettoConfig 
										(
											`key` 		VARCHAR(100) 	NOT NULL,
											`integer` 	INTEGER(80) 	default NULL,
											`string` 	TEXT 				default NULL,
											`boolean` 	BOOLEAN			default NULL,
											`type`		VARCHAR(800)	NOT NULL,
											`index`		INTEGER(80)		default NULL,
											PRIMARY KEY(`key`)
										) DEFAULT CHARSET=latin1;",
			'insert integer' => "INSERT INTO PrettoConfig (`key`,`integer`,`type`,`index`) VALUES (?,?,?,?);",
			'insert array' => "INSERT INTO PrettoConfig(`key`,`string`,`type`,`index`) VALUES (?,?,?,?);",
			'insert string' => "INSERT INTO PrettoConfig (`key`,`string`,`type`,`index`) VALUES (?,?,?,?);",
			'insert boolean' => "INSERT INTO PrettoConfig (`key`,`boolean`,`type`,`index`) VALUES (?,?,?,?);",
			'select * by key' => 'SELECT * FROM PrettoConfig WHERE `key` LIKE ? ORDER BY `index`;',
			'select *' => 'SELECT * FROM PrettoConfig ORDER BY `index`;',
			'update integer' => "UPDATE PrettoConfig SET `integer`=?,`index`=? WHERE `key`=?;",
			'update array' => "UPDATE PrettoConfig SET `string`=?,`index`=? WHERE `key`=?;",
			'update string' => "UPDATE PrettoConfig SET `string`=?,`index`=? WHERE `key`=?;",
			'update boolean' => "UPDATE PrettoConfig SET `boolean`=?,`index`=? WHERE `key`=?;",
			'delete config' => "DELETE FROM PrettoConfig WHERE `key` LIKE ?;",
			'table exists' => "SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_name = 'PrettoConfig';",
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
					$this->db->executeQuery(self::SQL('drop table config'));
					$this->db->executeQuery(self::SQL('create table config'));
					$this->initialize();

					return array('success', "Successfully created database 'PrettoConfig' from config values.");
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
	* Insert variables from config.php into database.
	*
	*/
	public function initialize()
	{
		$this->runDefault();
		$variables = $this->getVariables();
		foreach($variables as $key => $var)
		{
			$this->insert($key, $var);
		}
	}
	
	public function insert($key, $var)
	{	
		$params = array($key, $var['value'], $var['type'], $var['index']);

		if(empty($this->getValue($key)))
		{
			try
			{
				$this->db->executeQuery(self::SQL("insert {$var['type']}"), $params);
			}
			catch(Exception$e)
			{
				return false;
			}
		}
		else
			return false;
		return true;
	}
	
	private function runDefault()
	{
		
		
		$config['menus'] = array
		(
			'navbar' => array
				(
					'blog' => array('label' => 'Blog', 'url' => 'blog'),
					'guestbook' => array('label' => 'Guest book', 'url' => 'guestbook'),
					'pages'		=> array('label' => 'Pages', 'url' => 'content')
				),
		);
		$config['controllers'] = array
		(
			'index'     => array('enabled' => true,'class' => 'CCIndex'),
			'acp'		=> array('enabled' => true,'class' => 'CCAdminControlPanel'),
			'modules'	=> array('enabled' => true,'class' => 'CCModules'),
			'install'	=> array('enabled' => true,'class' => 'CCInstall'),
			'user'		=> array('enabled' => true,'class' => 'CCUser'),
			'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
			'blog'		=> array('enabled' => true,'class' => 'CCBlog'),
			'page'		=> array('enabled' => true,'class' => 'CCPage')
		);
		
		$config['theme'] = array
		(
			'data' => array
				(
					'header' => 'Pretto',
					'slogan' => 'MVC: Mobile Vehicle Construction',
					'favicon' => 'pretto.jpg',
					'logo' => 'pretto.jpg',
					'logo_width'  => 80,
					'logo_height' => 80,
					'footer' => "&copy; Pretto, self | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a>",
				),
		);
		foreach($config as $key => $variable)
		{

			$keys = array();
			$this->variableToKey($key, $variable, $keys);
			foreach($keys as $key => $var)
			{
				$this->insert($key, $var);
			}
		}
	}
	
	
	
	/**
	* Save config. If it has a id, use it to update current entry or else insert new entry.
	*
	* @returns boolean true if success else false.
	*/
	public function save($form)
	{
		$form = $form->elements;
		
		$success = true;

		foreach($form as $key => $element)
		{
			$attributes = $element->attributes;
			
			if($attributes['type'] == 'checkbox')
			{
				$attributes['value'] = $attributes['value'] == 'on' ? true : false;
			}

			if($attributes['type'] != 'submit')
			{			
				$res = $this->getValue($key);
				if(empty($res))
				{
					$success = false;
				}
				else
					$this->db->executeQuery(self::SQL("update {$attributes['var']['type']}"), array($attributes['value'], $attributes['var']['index'], "{$key}"));				
			}
		}
		return $success;
	}

	/** 
	* Delete config specified by id.
	*
	* $returns boolean true if success else false.
	*/
	public function delete()
	{
		$this->db->executeQuery(self::sql('delete config'), array($this['id']));
		
		$rowcount = $this->db->rowCount();
		if($rowcount) 
			$this->addMessage('success', "Successfully deleted config '{$this['key']}'.");
		else
			$this->addMessage('error', "Failed to delete config '{$this['key']}'.");

		return $rowcount === 1;
	}
	
	

	/**
	* Load variable by its key.
	*
	* @param string the key of the variable.
	* @returns array matching results from database.
	*/
	public function getVariable($key) 
	{
		$res = $this->db->selectAndFetchAll(self::sql('select * by key'), array($key . '%'));

		return $res;
	}

	/**
	* Search for a value that matches the key. Wildcards are handy. 
	*
	* @param string the key of the variable.
	* @returns array matching results from database.
	*/
	public function getValue($key) 
	{
		$res = $this->db->selectAndFetchAll(self::sql('select * by key'), array($key));

		return $res;
	}
	
	

	/**
	* List all variables.
	*
	* @returns array with listing or null if empty.
	*/
	public function getAllVariables() 
	{
		$res = null;
		try 
		{
			$res = $this->db->selectAndFetchAll(self::sql('select *'));
		} 
		catch(Exception$e) 
		{
			return array();
		}
		return $res;
	}

	/**
	* Filter config according to a filter.
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
	* Get the filtered config.
	*
	* @returns string with the filtered data.
	*/
	public function getFilteredData() 
	{
		return $this->filter($this['data'], $this['filter']);
	}
	
	
	/**
	* Do the same thing as CMConfig::keyToVariable, but for several keys and merge them into one variable, back into their original structure as they are in config.php.
	* 
	* @param $values - array of arrays fetched from the database.
	* @returns restructured array from database structure to config.php structure. See CMConfig::keyToVariable for details.
	*/
	public function keysToVariable($values, $appendIndex = false)
	{
		if(empty($values))
			return null;
		foreach($values as &$value)
			$value = $this->keyToVariable($value, $appendIndex);			
		

		return call_user_func_array('array_merge_recursive', $values);
	}

	/**
	* Reformat the database structure to an array structure by exploding the key and restructuring the resulting one-dimensional array into a multi-dimensional array.
	*
	* @param $value - array fetched from a single value from the database. Structure: $value = array('key' =>, 'integer', 'string' =>, 'boolean' =>, 'type' =>, 'index' =>).
	* @returns array with the following structure: $variable = array
													('
														a' => array
															(
																'b' => array
																	(
																		'c' => value
																	)
															)
													) 
	  converted from the key structure "a_b_c", where "_" represents PRETTO_SEPARATOR. 
	*/
	public function keyToVariable($value, $appendIndex  = false)
	{
		$key = $value['key'];
		
		// Split the key into a one-dimensional array.
		$parts = explode(PRETTO_SEPARATOR, $key);
		
		$count = count($parts);
		if($count == 0)
			return null;
		
		$variable = array();
		
		// If the key represents an array, set its value to array().
		// If the key represents a non-array (i.e. integer, boolean, string), set its value to the value it holds in the database.
		if($value['type'] == 'boolean')
			$value['boolean'] = $value['boolean'] ? true : false;
		$variable[$parts[$count - 1]] = $value['type'] == 'array' ? array() : $value[$value['type']];
		
		if($appendIndex)
			$variable[$parts[$count - 1]]['sortByIndex'] = $value['index'];
		
		// Take the righternmost part of the key and move it "upwards", iterating like this:
		// $parts = array('a', 'b', 'c')
		// 1. $variable = array('c' => value)
		// 2. $variable = array('b' => array('c' => value))
		// 3. $variable = array('a' => array('b' => array('c' => value)))
		while($count > 1)
		{
			$last = $variable;
			unset($variable);
	
			$variable[$parts[$count - 2]] = $last;
			$count--;
		}

		return $variable;
	}
	
	private function printr($array, &$html)
	{
		foreach($array as $key => $data)
		{
			if(is_array($data))
			{
				$html .= "<li>{$key}</li>";
				$html .= "<ul>";
				$this->printr($data, $html);
				$html .= "</ul>";
			}
			else
			{
				$html .= "<li>{$key} => {$data}</li>";
			}
		}
		
	
	}

	
	/**
	* Fetch variables from config.php that are allowed to be editable in-browser.
	* Convert variables into the following format to accommodate SQL database storing:
	* array
	* (
	*	key1_subkey1_subsubkey1 => value1,
	*	key2_subkey2_subsubkey2 => value2,
	* )
	* from:
	* array
	* (
	*	key1 => subkey1 => subsubkey1 => value1,
	*	key2 => subkey2 => subsubkey2 => value2,
	* )
	*
	* @returns array of variables, suitable for database insertion.
	**/
	public function getVariables()
	{
		$variables = array();
		foreach($this->config['editable_variables'] as $varKey => $variable)
		{
			if($variable['editable'])
			{
				$var = $this->config[$varKey];
				$this->variableToKey($varKey, $var, $variables);
			}
		}
		return $variables;
	}
	
	private function getKeysAndValues($array, &$values, $basePath)
	{
		$index = 0;
		foreach($array as $key => $value)
		{
			$valPath = $basePath . PRETTO_SEPARATOR . $key;
			
			if(is_array($value))
			{
				$values[$valPath] = array
				(
					'value' => $this->keyBasename($valPath),
					'type' => 'array',
					'index' => $index,
				);
				
				
				$this->getKeysAndValues($value, $values, $valPath);
			}
			else
			{
				$values[$valPath] = array
				(
					'value' => $value,
					'type' => gettype($value),
					'index' => $index,
				);
			}
			
			$index++;
		}
	}
	
	
	public function tableExists()
	{
		$this->db->executeQuery(self::SQL('table exists'), array($this->config['database'][0]['db']));
		
		return $this->db->rowCount() == 1;
	}
	
	public function getConfigData()
	{	
		$variables = $this->getAllVariables();
		
		
		$variables = $this->keysToVariable($variables);	
		
		$sortByIndex = function(&$variables, $self)
		{
			
		
			uasort($variables, function($a, $b)
				{
					if($a == $b)
						return 0;
					else
						return $a < $b ? 1 : -1;
				}
			);
		
			foreach($variables as &$variable)
				if(is_array($variable))
					$self($variable, $self);
		};
		
		$sortByIndex($variables, $sortByIndex);
		return $variables;
	}	
	
	
	public function variableToKey($key, $in, &$out)
	{
		if(is_array($in))
		{
			$this->getKeysAndValues($in, $out, $key);
		}
		else
		{
			$out[$key] = array
			(
				'value' => $in,
				'type' => gettype($in),
				'index' => 0,
			);
		}
	}
	
	
	public function keyBasename($key)
	{
		$basename = substr($key, strrpos($key, PRETTO_SEPARATOR) + strlen(PRETTO_SEPARATOR));
		return $basename;
	}
	
	private function sortKeys($keys)
	{
	
	}
}