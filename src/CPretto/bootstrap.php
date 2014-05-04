<?php
/**
* Bootstrapping, setting up and loading the core.
*
* @package PrettoCore
*/

/**
* Enable auto-load of class declarations.
*/
function autoload($aClassName) 
{
	$classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = PRETTO_SITE_PATH . $classFile;
	$file2 = PRETTO_INSTALL_PATH . $classFile;
	
	if(is_file($file1))
		require_once($file1);
	elseif(is_file($file2))
		require_once($file2);

}
spl_autoload_register('autoload');


/**
* Set a default exception handler and enable logging in it.
*/
function exception_handler($exception) 
{
	echo "Pretto: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('exception_handler');


/**
* Helper, wrap html_entites with correct character encoding
*/
function htmlent($str, $flags = ENT_COMPAT) 
{
	return htmlentities($str, $flags, CPretto::instance()->config['character_encoding']);
}

/**
* Helper, make clickable links from URLs in text.
* @deprecated since v0.3.0.1, moved to CTextFilter
*/
function makeClickable($text) 
{
	return preg_replace_callback
	(
		'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
		create_function
		(
			'$matches',
			'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
		),
		$text
	);
}

/**
* Helper, BBCode formatting converting to HTML.
*
* @param string text The text to be converted.
* @returns string the formatted text.
*/
function bbcode2html($text) 
{
	$search = array
	(
		'/\[b\](.*?)\[\/b\]/is',
		'/\[i\](.*?)\[\/i\]/is',
		'/\[u\](.*?)\[\/u\]/is',
		'/\[img\](https?.*?)\[\/img\]/is',
		'/\[url\](https?.*?)\[\/url\]/is',
		'/\[url=(https?.*?)\](.*?)\[\/url\]/is'
	);   
	$replace = array
	(
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<u>$1</u>',
		'<img src="$1" />',
		'<a href="$1">$1</a>',
		'<a href="$1">$2</a>'
	);     
	return preg_replace($search, $replace, $text);
}


/**
*
*
*
*
*/
function findAndReplaceKey(&$array, $search, $replace)
{
	if(isset($array[$search]))
	{
		$tmp = $array[$search];
		unset($array[$search]);
		$array[$replace] = $tmp;
		
		return true;
	}
	
	foreach($array as &$i)
	{
		if(is_array($i))
		{
			if(findAndReplaceKey($i, $search, $replace))
				return true;
		}
	}
	
	return false;
}