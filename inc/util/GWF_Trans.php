<?php
/**
 * Very cheap i18n.
 * 
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
final class GWF_Trans
{
	public static $ISO = 'en';
	
	private static $PATHS = array();
	private static $CACHE;
	
	public static function addPath(string $path)
	{
		self::$PATHS[] = $path;
		self::$CACHE = array();
	}
	
	public static function t(string $key, array $args=null)
	{
		return self::tiso(self::$ISO, $key, $args);
	}
	
	public static function tiso(string $iso, string $key, array $args=null)
	{
		if (!isset(self::$CACHE[$iso]))
		{
			self::reload($iso);
		}
		
		if (isset(self::$CACHE[$iso][$key]))
		{
			$text = self::$CACHE[$iso][$key];
			if ($args)
			{
				$text = vsprintf($text, $args);
			}
		}
		else # Fallback key + printargs
		{
			$text = $key;
			if ($args)
			{
				$text .= ": ";
				$text .= json_encode($args);
			}
		}
		
		return $text;
	}

	private static function reload(string $iso)
	{
		$trans = array();
		foreach (self::$PATHS as $path)
		{
			$path .= "_{$iso}.php";
			$trans = array_merge($trans, require($path));
		}
		self::$CACHE[$iso] = $trans;
	}

}
