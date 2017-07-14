<?php
class GWF_HTML
{
	public static function escape(string $s=null) { return $s === null ? '' : htmlspecialchars($s); }
	
	public static function anchor(string $href, string $text=null)
	{
		$text = $text === null ? $href : $text;
		return sprintf('<a href="%s">%s</a>', htmlspecialchars($href), htmlspecialchars($text));
	}
}
