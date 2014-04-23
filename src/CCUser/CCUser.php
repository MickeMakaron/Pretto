<?php
/**
* A user controller to manage login and view edit the user profile.
*
* @package PrettoCore
*/
class CCUser extends CObject implements IController 
{
	public function __construct() 
	{
		parent::__construct();
	}


	/**
	* Show profile information of the user.
	*/
	public function index() 
	{
		$this->views->setTitle('User Profile');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php', 
			array
			(
				'is_authenticated'=>$this->user['isAuthenticated'],
				'user'=>$this->user,
			)
		);
	}


	/**
	* User authentication and login
	*/
	public function login() 
	{
		$form = new CFormUserLogin($this);
		if($form->check() === false) 
		{
			$this->addMessage('notice', 'Some fields did not validate and the form could not be processed.');
			$this->redirectToController('login');
		}
		
		$this->views->setTitle('Login');
		$this->views->addInclude
		(
			__DIR__ . '/login.tpl.php', 
			array
			(
				'login_form'=>$form,
				'allow_create_user' => $this->config['create_new_users'],
				'create_user_url' => $this->request->createUrl(null, 'create')
			)
		); 
	}
	
	
	/**
	* View and edit user profile.
	*/
	public function profile() 
	{   
		$form = new CFormUserProfile($this, $this->user);
		if($form->check() === false) 
		{
			$this->addMessage('notice', 'Some fields did not validate and the form could not be processed.');
			$this->redirectToController('profile');
		}

		$this->views->setTitle('User Profile');
		$this->views->addInclude
					(
						__DIR__ . '/profile.tpl.php', 
						array
						(
							'is_authenticated'=>$this->user['isAuthenticated'],
							'user'=>$this->user,
							'profile_form'=>$form->getHTML(),
						)
					);
	}
	  
	  
	/**
	* User logout
	*/
	public function logout() 
	{
		$this->user->logout();
		$this->redirectToController('login');
	}


	/**
	* Initialize database
	*/
	public function init() 
	{
		$this->user->init();
		$this->redirectToController();
	}


	/**
	* Perform a login of the user as callback on a submitted form.
	*/
	public function doLogin($form) 
	{
		if($this->user->login($form['acronym']['value'], $form['password']['value'])) 
		{
			$this->addMessage('success', "Welcome {$this->user['acronym']}.");
			$this->redirectToController('profile');
		} 
		else 
		{
			$this->addMessage('notice', "Failed to login, user does not exist or password does not match.");
			$this->redirectToController('login');
		}  
		
	}
	
	/**
	* Change the password.
	*/
	public function doChangePassword($form) 
	{
		if
		(	
				$form['password']['value'] != $form['password1']['value'] 
			|| 	empty($form['password']['value']) 
			|| 	empty($form['password1']['value'])
		) 
		{
			$this->addMessage('error', 'Password does not match or is empty.');
		} 
		else 
		{
			$ret = $this->user->changePassword($form['password']['value']);
			$this->addMessage($ret, 'Saved new password.', 'Failed updating password.');
		}
		$this->redirectToController('profile');
	}

	/**
	* Save updates to profile information.
	*/
	public function doProfileSave($form) 
	{
		$this->user['name'] = $form['name']['value'];
		$this->user['email'] = $form['email']['value'];
		$ret = $this->user->save();
		$this->addMessage($ret, 'Saved profile.', 'Failed saving profile.');
		$this->redirectToController('profile');
	}
	
	/**
	* Delete profile.
	*/
	public function doProfileDelete($form)
	{
		$user = $this->user['name'];
		$res = $this->user->delete();
		
		$this->addMessage($res, "Deleted user '$user'.", "Failed to delete user '$user'.");

		if($res)
			$this->redirectToController();
		else
			$this->redirectToController('profile');
	}
	
	
	/**
	* Create a new user.
	*/
	public function create() 
	{
		$form = new CFormUserCreate($this);
		if($form->check() === false) 
		{
			$this->addMessage('notice', 'You must fill in all values.');
			$this->redirectToController('create');
		}
		
		$this->views->setTitle('Create user');
		$this->views->addInclude
		(
			__DIR__ . '/create.tpl.php', 
			array
			(
				'form' => $form->getHTML()
			));     
	}
	  
	/**
	* Perform a creation of a user as callback on a submitted form.
	*
	* @param $form CForm the form that was submitted
	*/
	public function doCreate($form) 
	{   
		if
		(
				$form['password']['value'] != $form['password1']['value'] 
			|| 	empty($form['password']['value']) 
			|| 	empty($form['password1']['value'])
		) 
		{
			$this->AddMessage('error', 'Password does not match or is empty.');
			$this->RedirectToController('create');
		} 
		else if
		(
			$this->user->create
			(
				$form['acronym']['value'],
				$form['password']['value'],
				$form['name']['value'],
				$form['email']['value']
			)
		) 
		{
			$this->addMessage('success', "Welcome {$this->user['name']}. Your have successfully created a new account.");
			$this->user->login($form['acronym']['value'], $form['password']['value']);
			$this->redirectToController('profile');
		}
		else 
		{
			$this->addMessage('notice', "Failed to create an account.");
			$this->redirectToController('create');
		}
	}
	
} 