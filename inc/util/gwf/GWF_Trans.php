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
		if ($text = @self::$CACHE[$iso][$key])
		{
			if ($args)
			{
				if (!($text = @vsprintf($text, $args)))
				{
					$text = @self::$CACHE[$iso][$key] . ': ';
					$text .= json_encode($args);
				}
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
			if (GWF_File::isFile("{$path}_{$iso}.php"))
			{
				$trans2 = include("{$path}_{$iso}.php");
			}
			else
			{
				$trans2 = require("{$path}_en.php");
			}
			$trans = array_merge($trans, $trans2);
		}
		self::$CACHE[$iso] = $trans;
	}

}

function t(string $key, array $args=null) { return GWF_Trans::t($key, $args); }
function ten(string $key, array $args=null) { return GWF_Trans::tiso('en', $key, $args); }
function tiso(string $iso, string $key, array $args=null) { return GWF_Trans::tiso($iso, $key, $args); }
function tusr(GWF_User $user, string $key, array $args=null) { return GWF_Trans::tiso($user->getLangISO(), $key, $args); }
function l(string $key, array $args=null) { echo t($key, $args); }
function tt(string $date=null, string $format='short', string $default='---') { return GWF_Time::displayDate($date, $format, $default); }
function lt(string $date=null, string $format='short', string $default='---') { echo tt($date, $format, $default); }
function htmle($s) { return htmlspecialchars($s); }
function html($s) { echo htmle($s); }
