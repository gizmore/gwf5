<?php
final class GWF_Filewalker
{
	public static function filewalker_stub($entry, $fullpath, $args=null) {}
	
	public static function traverse($path, $callback_file=false, $callback_dir=false, $recursive=true, $args=null)
	{
		$path = rtrim($path, '/\\');
		
		# Readable?
		if (!($dir = @dir($path)))
		{
			return false;
		}
		
		if (is_bool($callback_file))
		{
			$callback_file = array(__CLASS__, 'filewalker_stub');
		}
		
		if (is_bool($callback_dir))
		{
			$callback_dir = array(__CLASS__, 'filewalker_stub');
		}
		
		$dirstack = array();
		while ($entry = $dir->read())
		{
			$fullpath = $path.'/'.$entry;
			if ( (strpos($entry, '.') === 0) || (!is_readable($fullpath)) )
			{
				continue;
			}
			
			if (is_dir($fullpath))
			{
				$dirstack[] = array($entry, $fullpath);
			}
			elseif (is_file($fullpath))
			{
				call_user_func($callback_file, $entry, $fullpath, $args);
			}
		}
		
		$dir->close();
		
		foreach ($dirstack as $d)
		{
			call_user_func($callback_dir, $d[0], $d[1], $args);
			
			if ($recursive)
			{
				self::filewalker($d[1], $callback_file, $callback_dir, $recursive, $args);
			}
		}
	}
}
