<?php
/**
* Helpers for the template file.
*/
$pr->data['header'] = isset($pr->data['header']) ? $pr->data['header'] : null;
$pr->data['main'] = isset($pr->data['main']) ? $pr->data['main'] : null;
$pr->data['footer'] = "<p>Footer: &copy; Pretto | 		<a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></p>";


/**
* Print debug information from the framework.
*/
function get_debug() 
{
	$pr = CPretto::Instance();
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
	return CPretto::Instance()->request->base_url . trim($url, '/');
}

/**
* Return the current url.
*/
function current_url() 
{
	return CPretto::Instance()->request->current_url;
}	