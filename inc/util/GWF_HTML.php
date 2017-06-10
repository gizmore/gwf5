<?php
class GWF_HTML
{
	public static function escape(string $s=null) { return $s ? htmlspecialchars($s) : ''; }
	
	public static function anchor(string $href, string $text=null)
	{
		$text = $text ? $text : $href;
		return sprintf('<a href="%s">%s</a>', htmlspecialchars($href), htmlspecialchars($text));
	}
}
