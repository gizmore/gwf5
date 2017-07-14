<?php
/**
 * Array utility
 * @author gizmore
 */
final class GWF_Array
{
	public static function array($value)
	{
		if (is_array($value))
		{
			return $value;
		}
		$back = [];
		if ($value !== null)
		{
			$back[] = $value;
		}
		return $back;
	}
	
	/**
	 * Recursive implode. Code taken from php.net. Original code by: kromped@yahoo.com
	 * @param string $glue
	 * @param array $pieces
	 * @return string
	 */
	public static function implode($glue, array $pieces, array $retVal=array())
	{
		foreach($pieces as $r_pieces)
		{
			$retVal[] = (true === is_array($r_pieces)) ? '['.self::implode($glue, $r_pieces).']' : $r_pieces;
		}
		return implode($glue, $retVal);
	}
}
