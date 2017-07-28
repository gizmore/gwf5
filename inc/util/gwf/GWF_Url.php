<?php
final class GWF_Url
{
	public static function host() { return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : GWF_DOMAIN; }
	public static function protocol() { return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== 'off') ? 'https' : 'http'; }
	public static function absolute(string $url) { return sprintf('%s://%s%s%s', self::protocol(), self::host(), GWF_WEB_ROOT, self::relative($url)); }
	public static function relative(string $url)
	{
		return $url;
	}
// 	public static function urlencodeSEO($string)
// 	{
// 		$ch = '_';
// 		$search = array( ' ', '<', '>', '"', "'", '/', '#', '?', '!', ':', ')', '(', '[', ']', ',', '+', '_', '@',	        '&',	       '%');
// 		$replace = array($ch, $ch, $ch, $ch, $ch, $ch, $ch, '',  '',  '',  '',  '',  '',  '',  '',  '',  $ch, $ch.'at'.$ch, $ch.'and'.$ch, $ch);
// 		$back = str_replace($search, $replace, $string);
// 		$back = preg_replace('/[^a-z0-9]/i', $ch, $back);
// 		$back = preg_replace('/'.$ch.'{2,}/', $ch, $back);
// 		$back = trim($back, $ch);
// 		return $back === '' ? '_title_' : $back;
// 	}
}