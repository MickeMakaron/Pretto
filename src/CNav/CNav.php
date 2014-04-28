<?php
/**
* Generate nagivation menus for different scenarios.
*/
class CNav
{
	public static function fromDir($dir, $id)
	{
		$kmoms = self::readDirectoryFolders($dir);
		$dir = __DIR__;
		$currentMom = basename($dir);
		$html = null;
		foreach($kmoms as $kmom)
		{
			$base = basename($kmom);
			$html .= strpos($dir, $base) ? "{$base}\n" : "<a href='".CPretto::instance()->request->base_url."../{$base}'>{$base}</a>\n";
		}
		
		return "<nav id='$id'>\n{$html}</nav>\n";
	}

	public static function fromArray($items, $id) 
	{
		$pr = CPretto::instance();
		$p = $pr->request->method;
		foreach($items as $key => $item) 
		{
			$selected = ($p == $key) ? " class='selected'" : null; 
			@$html .= "<a href='{$item['url']}'{$selected}>{$item['text']}</a>\n";
		}
		return "<nav id='$id'>\n{$html}</nav>\n";
	}
	
	
	public static function readDirectoryFolders($path) 
	{
		$list = Array();
		if(is_dir($path) && $dh = opendir($path)) 
		{
			while (($folder = readdir($dh)) !== false) 
				if(is_dir("$path/$folder") && !in_array($folder, array('.htacces','..','.'))) 
					$list[$folder] = "$path/$folder";
				
			closedir($dh);
		}
		sort($list, SORT_STRING);
		return $list;
	}
}