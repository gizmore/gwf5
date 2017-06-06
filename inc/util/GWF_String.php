<?php
final class GWF_String
{
	######################
	### Start/End with ###
	######################
	public static function startsWith(string $s, string $with)
	{
		return mb_strstr($s, $with) === 0;
	}
	
	#########################
	### Substring to/from ###
	#########################
	public static function substrTo(string $s, string $to, $default=null)
	{
		if (false !== ($index = mb_strpos($s, $to)))
		{
			return mb_substr($s, 0, $index);
		}
		return $default;
	}
	
	public static function substrFrom(string $s, string $from, $default=null)
	{
		if (false !== ($index = mb_strpos($s, $from)))
		{
			return mb_substr($s, $index + mb_strlen($from));
		}
		return $default;
	}
	

	public static function rsubstrTo(string $s, string $to, $default=null)
	{
		if (false !== ($index = mb_strrpos($s, $to)))
		{
			return mb_substr($s, 0, $index);
		}
		return $default;
	}
	
	public static function rsubstrFrom(string $s, string $from, $default=null)
	{
		if (false !== ($index = mb_strrpos($s, $from)))
		{
			return mb_substr($s, $index + mb_strlen($from));
		}
		return $default;
	}

	################
	### Encoding ###
	################
	/**
	 * Filter non utf8 characters.
	 * @param string $s
	 * @return string $s filtered
	 */
	public static function utf8(string $s)
	{
		$regex = '/((?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3}){1,100})/';
		return preg_replace($regex, '$1', $s);
	}
}