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
	
	private static $PATHS = [];
	private static $CACHE;
	
	public static function addPath(string $path)
	{
		self::$PATHS[] = $path;
		self::$CACHE = [];
	}
	
	public static function getCache(string $iso)
	{
		return self::load($iso);
	}
	
	public static function load($iso)
	{
		if (!isset(self::$CACHE[$iso]))
		{
			self::reload($iso);
		}
		return self::$CACHE[$iso];
	}
	
	public static function t(string $key, array $args=null)
	{
		return self::tiso(self::$ISO, $key, $args);
	}
	
	public static function tiso(string $iso, string $key, array $args=null)
	{
		self::load($iso);
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
		$trans = [];
		foreach (self::$PATHS as $path)
		{
			$path .= "_{$iso}.php";
			$trans2 = require($path);
			$trans = array_merge($trans, $trans2);
		}
		self::$CACHE[$iso] = $trans;
	}

}

function t(string $key, array $args=null) { return GWF_Trans::t($key, $args); }
function l(string $key, array $args=null) { echo t($key, $args); }
