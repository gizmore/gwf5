<?php
/**
 * Very basic on-the-fly javascript mangler.
 * Changes are detected by md5 only.
 * @author gizmore
 * @since 4.1
 */
final class GWF_Minify
{
	public static function tempDir()
	{
		return GWF_PATH.'temp/minify/';
	}
	
	public static function init()
	{
		GWF_File::createDir(self::tempDir());
	}
	
	public static function minified(array $javascripts)
	{
		self::init();
		return array_map(array(GWF_Minify::class, 'minifiedJavascriptPath'), $javascripts);
	}
	
	public static function minifiedJavascriptPath($path)
	{
		return GWF_String::startsWith($path, '/') ? self::minifiedJavascript(substr($path, 1)) : $path; 
	}
	
	public static function minifiedJavascript($path)
	{
		$src = GWF_PATH . GWF_String::substrTo($path, '?', $path);
		
		if (GWF_File::isFile($src))
		{
			$md5 = md5(file_get_contents($src));
			$dest = self::tempDir() . $md5 . '.js'; 
			if (!GWF_File::isFile($dest))
			{
				if (strpos($src, '.min.js'))
				{
					copy($src, $dest); # Skip minified ones
				}
				else
				{
					$command = "ng-annotate -ar $src | uglifyjs --compress --mangle --screw-ie8 -o $dest";
					exec($command);
					if ($return != 0)
					{
						return $path; # On error, the original file is left. so you notice.
					}
				}
			}
			return "/temp/minify/$md5.js";
		}
		return $path;
	}
}
