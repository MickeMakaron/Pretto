<?php
/**
* HTML-stuff
*/


/**
 * Helpers for the template file.
 */

/**
 * Create HTML for a navbar.
 */
function getHTMLForNavigation($items, $id) 
{
	$p = basename($_SERVER['SCRIPT_NAME'], '.php');
	foreach($items as $key => $item) 
	{
		$selected = ($p == $key) ? " class='selected'" : null; 
		@$html .= "<a href='{$item['url']}'{$selected}>{$item['text']}</a>\n";
	}
	return "<nav id='$id'>\n{$html}</nav>\n";
}


/**
 * Create HTML for navigation links among kmoms.
 */
function getHTMLForKmomNavlinks($dir, $id) 
{
	$kmoms = readDirectoryFolders($dir);
	foreach($kmoms as $key => $kmom)
		@$html .= empty($kmom['url']) ? $kmom['text'] : "<a href='{$kmom['url']}'{$selected}>{$kmom['text']}</a>\n" ;

	return "<nav id='$id'>\n{$html}</nav>\n";
}


/**
* Get all folders in specified directory
*/
function readDirectoryFolders($aPath) 
{
	$list = Array();
	if(is_dir($aPath) && $dh = opendir($aPath)) 
	{
		while (($folder = readdir($dh)) !== false) 
			if(is_dir("$aPath/$folder") && $folder != '.htaccess') 
				$list[$folder] = "$folder";
			
		closedir($dh);
	}
	sort($list, SORT_STRING);
	return $list;
}


/**
 * Get URL to current page.
 */
function getCurrentUrl() 
{
	$url = "http";
	$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
	$url .= "://";
	$serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
		(($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
	$url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
	return $url;
<<<<<<< HEAD
=======
}

/**
* Print debug information from the framework.
*/
function get_debug() 
{
	$pr = CPretto::instance();
	$html 	= "<h2>Debug information</h2><hr><p>The content of the config array:</p><pre>" . htmlentities(print_r($pr->config, true)) . "</pre>";
	$html  .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($pr->data, true)) . "</pre>";
	$html  .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($pr->request, true)) . "</pre>";
	return $html;
}

/**
* Helpers for theming, available for all themes in their template files and functions.php.
* This file is included right before the themes own functions.php
*/

/**
* Create a url by prepending the base_url.
*/
function base_url($url) 
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
* Render all views.
*
* @param $region string the region to draw the content in.
*/
function render_views($region = 'default') 
{
	return CPretto::instance()->views->render($region);
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
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
}