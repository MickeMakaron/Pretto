<?php
/**
* Print debug information from the framework.
*/
function get_debug() 
{
	$pr = CPretto::instance(); 
	
	if(empty($pr->config['debug']))
		return;
	
	$html = null;
	if(isset($pr->config['debug']['isOn']) && $pr->config['debug']['isOn'])
	{
		$html .= "<hr><h3>Debug information</h3><p>The content of CPretto:</p><pre>" . htmlent(print_r($pr, true)) . "</pre>";
		
		if(isset($pr->config['debug']['dbNumQueries']) && $pr->config['debug']['dbNumQueries'] && isset($pr->db))
		{
		    $flash = $pr->session->getFlash('database_numQueries');
			$flash = $flash ? "$flash + " : null;
			$html .= "<p>Database made $flash" . $pr->db->getNumQueries() . " queries.</p>";  
		}
		if(isset($pr->config['debug']['dbQueries']) && $pr->config['debug']['dbQueries'] && isset($pr->db))
		{
			$flash = $pr->session->getFlash('database_queries');
			$queries = $pr->db->getQueries();
			if($flash) 
				$queries = array_merge($flash, $queries);	

			$html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
		}
		if(isset($pr->config['debug']['timer']) && $pr->config['debug']['timer'])
			$html .= "<p>Page was loaded in " . round(microtime(true) - $pr->timer['first'], 5)*1000 . " msecs.</p>";
			
		if(isset($pr->config['debug']['session']) && $pr->config['debug']['session']) 
		{
			$html .= "<hr><h3>SESSION</h3><p>The content of CPretto->session:</p><pre>" . htmlent(print_r($pr->session, true)) . "</pre>";
			$html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
		}
	}
	return $html;
}

/**
* Render all views.
*
* @param $region string the region to draw the content in.
*/
function render_views($region = 'default') 
{
	return CPretto::instance()->views->render($region);
}

<<<<<<< HEAD

=======
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
/**
* Check if region has views. Accepts variable amount of arguments as regions.
*
* @param $region string the region to draw the content in.
*/
function region_has_content($region = 'default' /*...*/) 
{
	return CPretto::instance()->views->regionHasView(func_get_args());
}


/**
* Get messages stored in flash-session.
*/
function get_messages_from_session() 
{
	$messages = CPretto::instance()->session->getMessages();
	$html = null;
	if(!empty($messages)) 
		foreach($messages as $val) 
		{
			$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
			$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
			$html .= "<div class='$class'>{$val['message']}</div>\n";
		}
		
	return $html;
}

<<<<<<< HEAD
function get_messages()
{
	$data = CPretto::instance()->data;
	
	$messages = isset($data['messages']) ? $data['messages'] : null;
	$html = null;
	if(!empty($messages)) 
		foreach($messages as $val) 
		{
			$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
			$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
			$html .= "<div class='$class'>{$val['message']}</div>\n";
		}
	return $html;

}

=======
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5

/**
* Create a url by prepending the base_url.
*/
function base_url($url = null) 
{
	return CPretto::instance()->request->base_url . trim($url, '/');
}

/**
* Return the current url.
*/
function current_url() 
{
	return CPretto::instance()->request->current_url;
}	

/**
* Create a url to an internal resource.
*
* @param string the whole url or the controller. Leave empty for current controller.
* @param string the method when specifying controller as first argument, else leave empty.
* @param string the extra arguments to the method, leave empty if not using method.
*/
function create_url($urlOrController = null, $method = null, $arguments = null) 
{
	return CPretto::instance()->request->createUrl($urlOrController, $method, $arguments);
}


/**
* Prepend the theme_url, which is the url to the current theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_url($url) 
{
	return create_url(CPretto::instance()->themeUrl . "/{$url}");
}


/**
* Prepend the theme_parent_url, which is the url to the parent theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_parent_url($url) 
{
	return create_url(CPretto::instance()->themeParentUrl . "/{$url}");
}


/*
* Create a url, appended by $arg if $arg is unique.
*/
function argument_unique($arg)
{
	$arg = trim($arg, '/');
	$args = CPretto::instance()->request->arguments;
	
	return in_array($arg, $args) ? current_url() : create_url(current_url() . "/$arg");
}


/**
* Login menu. Creates a menu which reflects if user is logged in or not.
*/
function login_menu() 
{
	$pr = CPretto::instance();
	if($pr->user['isAuthenticated']) 
	{
		$items = "<img src='".get_gravatar(30)."'/> ";
		$items .= "<a href='" . create_url('user/profile') . "'>" . $pr->user['acronym'] . "</a> ";
		if($pr->user['isAdmin'])
			$items .= "<a href='" . create_url('acp') . "'>acp</a> ";
			
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	} 
	else
		$items = "<a href='" . create_url('user/login') . "'>login</a> ";

	return "<nav>$items</nav>";
}


/**
* Get a gravatar based on the user's email.
*/
function get_gravatar($size = null) 
{
	return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CPretto::instance()->user['email']))) . '.jpg?' . ($size ? "s=$size" : null);
}

/**
* Escape data to make it safe to write in the browser.
*/
function esc($str) 
{
	return htmlEnt($str);
}

/**
* Filter data according to a filter. Uses CMContent::Filter()
*
* @param $data string the data-string to filter.
* @param $filter string the filter to use.
* @returns string the filtered string.
*/
function filter_data($data, $filter) 
{
	return CMContent::filter($data, $filter);
}

