<?php
class GWF_Button
{
	public static function generic(string $text, string $url)
	{
		return GWF_Template::mainPHP('button.php', []);
	}
}