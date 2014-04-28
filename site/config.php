<?php
/**
* Site configuration, this file is changed by user per site.
*
*/


/*
* Set level of error reporting
*/
error_reporting(-1);
ini_set('display_errors', 1);


/*
* Define session settings
*/
$pr->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
$pr->config['session_key']  = 'pretto';


/**
* How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
*/
$pr->config['hashing_algorithm'] = 'sha1salt';

/**
* Allow creation of new user accounts?
*/
$pr->config['create_new_users'] = true;

/*
* Define server timezone
*/
$pr->config['timezone'] = 'UTC';
date_default_timezone_set($pr->config['timezone']);

/*
* Define internal character encoding
*/
$pr->config['character_encoding'] = 'UTF-8';


/*
* Define language
*/
$pr->config['language'] = 'en';


/**
* Define the controllers, their classname and enable/disable them.
*
* The array-key is matched against the url, for example:
* the url 'developer/dump' would instantiate the controller with the key "developer", that is
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $pr->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/
$pr->config['controllers'] = array
(
	'index'     => array('enabled' => true,'class' => 'CCIndex'),
	'me'		=> array('enabled' => true,'class' => 'CCMe'),
	'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
	'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
	'user'		=> array('enabled' => true,'class' => 'CCUser'),
	'acp'		=> array('enabled' => true,'class' => 'CCAdminControlPanel'),
	'content'	=> array('enabled' => true,'class' => 'CCContent'),
	'page'		=> array('enabled' => true,'class' => 'CCPage'),
	'blog'		=> array('enabled' => true,'class' => 'CCBlog'),
	'theme'		=> array('enabled' => true,'class' => 'CCTheme'),
	'modules'	=> array('enabled' => true,'class' => 'CCModules'),
	'my'		=> array('enabled' => true,'class' => 'CCMyController')
);

/**
* Define menus.
*
* Create hardcoded menus and map them to a theme region through $ly->config['theme'].
*/
$pr->config['menus'] = array
(
	'navbar' => array
	(
		'home'      => array('label'=>'Home', 		'url'=>''),
		'modules'   => array('label'=>'Modules', 	'url'=>'modules'),
		'guestbook' => array('label'=>'Guestbook',	'url'=>'guestbook'),
		'content'   => array('label'=>'Content', 	'url'=>'content'),
		'blog'      => array('label'=>'Blog', 		'url'=>'blog'),
		'theme'		=> array('label'=>'Theme',		'url'=>'theme'),
	),
);

/**
* Settings for the theme. The theme may have a parent theme.
*
* When a parent theme is used the parent's functions.php will be included before the current
* theme's functions.php. The parent stylesheet can be included in the current stylesheet
* by an @import clause. See site/themes/mytheme for an example of a child/parent theme.
* Template files can reside in the parent or current theme, the CPretto::themeEngineRender()
* looks for the template-file in the current theme first, then it looks in the parent theme.
*
* There are two useful theme helpers defined in themes/functions.php.
*  theme_url($url): Prepends the current theme url to $url to make an absolute url.
*  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url.
*
* path: Path to current theme, relativly PRETTO_INSTALL_PATH, for example themes/grid or site/themes/mytheme.
* parent: Path to parent theme, same structure as 'path'. Can be left out or set to null.
* stylesheet: The stylesheet to include, always part of the current theme, use @import to include the parent stylesheet.
* template_file: Set the default template file, defaults to default.tpl.php.
* regions: Array with all regions that the theme supports.
* data: Array with data that is made available to the template file as variables.
*
* The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made
* available to the template files.
*/
$pr->config['theme'] = array
(
	'name'			=> 'mytheme',
	'path'			=> 'themes/mytheme',
	'parent'		=> 'themes/grid',
	'stylesheet'	=> 'style.css',
	'template_file'	=> 'index.tpl.php',

	/*
	* A list of valid theme regions
	*/
	'regions' => array
		(
			'flash',
			'featured-first',
			'featured-middle',
			'featured-last',
			'navbar',
			'primary',
			'sidebar',
			'triptych-first',
			'triptych-middle',
			'triptych-last',
			'footer-column-one',
			'footer-column-two',
			'footer-column-three',
			'footer-column-four',
			'footer',
		),
		
	'menu_to_region' => array('navbar'=>'navbar'),
	  
	/*	
	* Static content
	*/
	'data' => array
		(
			'header' => 'Pretto',
			'slogan' => 'MVC: Mobile Vehicle Construction',
			'favicon' => 'pretto.jpg',
			'logo' => 'pretto.jpg',
			'logo_width'  => 80,
			'logo_height' => 80,
			'footer' => "<p>&copy; Pretto, self | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></p>",
		),
);


/*
* Database
*
**/
$pr->config['database'][0]['dsn'] = 'sqlite:' . PRETTO_SITE_PATH . '/data/.ht.sqlite';

/**
* Debug
*/
$pr->config['debug'] = array
(
	'isOn' => false,
	'dbNumQueries' => true,
	'dbQueries' => true
);

/**
* Set a base_url to use another than the default calculated
*/
$pr->config['base_url'] = null;


/**
* What type of urls should be used?
*
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$pr->config['url_type'] = 1;


/**
* Define a routing table for urls.
*
* Route custom urls to a defined controller/method/arguments
*/
$ly->config['routing'] = array
(
	'home' => array('enabled' => true, 'url' => 'index/index'),
);