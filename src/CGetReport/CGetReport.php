<?php
/**
* Simple class for report presentation at BTH.
*/
class CGetReport
{
	public static function fromDir($dir)
	{
		$folders = CNav::readDirectoryFolders($dir);
		rsort($folders, SORT_STRING);
		$currentDir = __DIR__;
		$html = null;
		
		$hasReachedThisMom = false;
		foreach($folders as $kmom)
		{
			$base = basename($kmom);
			
			if(strpos($currentDir, $base))
				$hasReachedThisMom = true;
		
			if($hasReachedThisMom && is_file($kmom . "/{$base}_report.php"))
				$html .= file_get_contents($kmom . "/{$base}_report.php");
		}

		return $html;
	}
}