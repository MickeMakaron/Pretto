<?php
/**
* A model for an authenticated user.
*
* @package PrettoCore
*/
class CMUser extends CObject implements ISQL, ArrayAccess, IModule
{
	public $profile = array();
	
	public function __construct($pr = null) 
	{
		parent::__construct($pr);
		$profile = $this->session->getAuthenticatedUser();
		$this->profile = is_null($profile) ? array() : $profile;
		$this['isAuthenticated'] = is_null($profile) ? false : true;
		
		if(!$this['isAuthenticated']) 
		{
			$this['id'] = 1;
			$this['acronym'] = 'anonymous';
		}
	}

	
	/**
	* Implementing ArrayAccess for $this->profile
	*/
	public function offsetSet($offset, $value)
	{ 
		if (is_null($offset)) 
			$this->profile[] = $value;
		else
			$this->profile[$offset] = $value;
	}
	
	public function offsetExists($offset) 
	{ 
		return isset($this->profile[$offset]); 
	}
	
	public function offsetUnset($offset) 
	{
		unset($this->profile[$offset]); 
	}
	
	public function offsetGet($offset) 
	{ 
		return isset($this->profile[$offset]) ? $this->profile[$offset] : null; 
	}
	
	
	
	/**
	* Implementing interface ISQL. Encapsulate all SQL used by this class.
	*
	* @param string $key the string that is the key of the wanted SQL-entry in the array.
	*/
	public static function sql($key = null) 
	{
		$queries = array
		(
			'drop table user' => "DROP TABLE IF EXISTS User;",
			'drop table group' => "DROP TABLE IF EXISTS Groups;",
			'drop table user2group' => "DROP TABLE IF EXISTS User2Groups;",
			'create table user' => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
			'create table group' => "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
			'create table user2group' => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
			'insert into user' => 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
			'insert into group' => 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
			'insert into user2group' => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
			'check user password' => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
			'get group memberships' => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
			'update profile' => "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
			'update password' => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
			'delete user' => "DELETE FROM User WHERE id=?;",
		);
		
		if(!isset($queries[$key]))
			throw new Exception("No such SQL query, key '$key' was not found.");

		return $queries[$key];
	}

	/**
	* Implementing interface IModule. Manage install/update/deinstall and equal actions.
	*
	* @param string $action what to do.
	*/
	public function manage($action = null) 
	{
		switch($action) 
		{
			case 'install':
				try 
				{
					$this->db->executeQuery(self::sql('drop table user2group'));
					$this->db->executeQuery(self::sql('drop table group'));
					$this->db->executeQuery(self::sql('drop table user'));
					$this->db->executeQuery(self::sql('create table user'));
					$this->db->executeQuery(self::sql('create table group'));

					$this->db->executeQuery(self::sql('create table user2group'));
					$password = $this->createPassword('root');

					$this->db->executeQuery(self::sql('insert into user'), array('anonymous', 'Anonymous, not authenticated', null, 'plain', null, null));

					$this->db->executeQuery(self::sql('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
					$idRootUser = $this->db->lastInsertId();
					$password = $this->createPassword('doe');

					$this->db->executeQuery(self::sql('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
					$idDoeUser = $this->db->lastInsertId();

					$this->db->executeQuery(self::sql('insert into group'), array('admin', 'The Administrator Group'));
					$idAdminGroup = $this->db->lastInsertId();

					$this->db->executeQuery(self::sql('insert into group'), array('user', 'The User Group'));
					$idUserGroup = $this->db->lastInsertId();

					$this->db->executeQuery(self::sql('insert into user2group'), array($idRootUser, $idAdminGroup));
					$this->db->executeQuery(self::sql('insert into user2group'), array($idRootUser, $idUserGroup));
					$this->db->executeQuery(self::sql('insert into user2group'), array($idDoeUser, $idUserGroup));

					return array('success', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.');
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
	* Login by autenticate the user and password. Store user information in session if success.
	*
	* @param string $acronymOrEmail the emailadress or user akronym.
	* @param string $password the password that should match the akronym or emailadress.
	* @returns booelan true if match else false.
	*/
	public function login($acronymOrEmail, $password) 
	{
		$user = $this->db->selectAndFetchAll(self::sql('check user password'), array($acronymOrEmail, $acronymOrEmail));
		$user = (isset($user[0])) ? $user[0] : null;
		
		if(!$user)
			return false;
		else if(!$this->checkPassword($password, $user['algorithm'], $user['salt'], $user['password']))
			return false;

		unset($user['algorithm']);
		unset($user['salt']);
		unset($user['password']);
			
		if($user) 
		{
			$user['isAuthenticated'] = true;
			$user['groups'] = $this->db->selectAndFetchAll(self::sql('get group memberships'), array($user['id']));
			foreach($user['groups'] as $val) 
			{
				if($val['id'] == 1)
					$user['isAdmin'] = true;
				if($val['id'] == 2)
					$user['isUser'] = true;
			}
			
			$this->profile = $user;
			$this->session->SetAuthenticatedUser($this->profile);
		}
		return ($user != null);
	}

	/**
	* Logout.
	*/
	public function logout() 
	{
		$this->session->unsetAuthenticatedUser();
		$this->profile = array();
		$this->session->addMessage('success', "You have logged out.");
	}


	
	/**
	* Save user profile to database and update user profile in session.
	*
	* @returns boolean true if success else false.
	*/
	public function save() 
	{
		$this->db->executeQuery(self::sql('update profile'), array($this['name'], $this['email'], $this['id']));
		$this->session->setAuthenticatedUser($this->profile);
		return $this->db->rowCount() === 1;
	}
	
	/**
	*
	*
	*/
	public function delete()
	{
		$id = $this['id'];
		
		$this->session->unsetAuthenticatedUser();
		$this->profile = array();
		
		$this->db->executeQuery(self::sql('delete user'), array($id));
		
		return $this->db->rowCount() === 1;
	}
	
	

	/**
	* Create new user.
	*
	* @param $acronym string the acronym.
	* @param $password string the password plain text to use as base.
	* @param $name string the user full name.
	* @param $email string the user email.
	* @returns boolean true if user was created or else false and sets failure message in session.
	*/
	public function create($acronym, $password, $name, $email) 
	{
		$password = $this->createPassword($password);
		$this->db->executeQuery(self::sql('insert into user'), array($acronym, $name, $email, $password['algorithm'], $password['salt'], $password['password']));
		if($this->db->rowCount() == 0) 
		{
			$this->addMessage('error', "Failed to create user.");
			return false;
		}
		
		return true;
	}
	
	
	/**
	* Change user password.
	*
	* @param $password string the new password
	* @returns boolean true if success else false.
	*/
	public function changePassword($password) 
	{
		$password = $this->createPassword($password);
		$this->db->executeQuery(self::SQL('update password'), array($password['algorithm'], $password['salt'], $password['password'], $this['id']));
		return $this->db->rowCount() === 1;
	}
	
	
	/**
	* Create password.
	*
	* @param $plain string the password plain text to use as base.
	* @param $algorithm string stating what algorithm to use, plain, md5, md5salt, sha1, sha1salt.
	* defaults to the settings of site/config.php.
	* @returns array with 'salt' and 'password'.
	*/
	public function createPassword($plain, $algorithm = null) 
	{
		$password = array
		(
			'algorithm'	=>($algorithm ? $algoritm : CPretto::instance()->config['hashing_algorithm']),
			'salt'		=>null
		);
		
		switch($password['algorithm']) 
		{
			case 'sha1salt': 
				$password['salt'] = sha1(microtime()); 
				$password['password'] = sha1($password['salt'].$plain); 
				break;
			case 'md5salt': 
				$password['salt'] = md5(microtime()); 
				$password['password'] = md5($password['salt'].$plain); 
				break;
			case 'sha1': 
				$password['password'] = sha1($plain); 
				break;
			case 'md5': 
				$password['password'] = md5($plain); 
				break;
			case 'plain': 
				$password['password'] = $plain; 
				break;
			default: 
				throw new Exception('Unknown hashing algorithm');
		}
		
		return $password;
	}
	
	/**
	* Check if password matches.
	*
	* @param $plain string the password plain text to use as base.
	* @param $algorithm string the algorithm mused to hash the user salt/password.
	* @param $salt string the user salted string to use to hash the password.
	* @param $password string the hashed user password that should match.
	* @returns boolean true if match, else false.
	*/
	public function checkPassword($plain, $algorithm, $salt, $password) 
	{
		switch($algorithm) 
		{
			case 'sha1salt': 
				return $password === sha1($salt.$plain); 
				
			case 'md5salt': 
				return $password === md5($salt.$plain); 
				
			case 'sha1': 
				return $password === sha1($plain); 
				
			case 'md5': 
				return $password === md5($plain); 
				
			case 'plain': 
				return $password === $plain; 
				
			default: 
				throw new Exception('Unknown hashing algorithm');
		}
	}
}