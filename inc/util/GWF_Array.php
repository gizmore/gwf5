<?php
final class GWF_Array
{
	public static function array($value)
	{
		if (is_array($value))
		{
			return $value;
		}
		$back = array();
		if ($value !== null)
		{
			$back[] = $value;
		}
		return $back;
	}
}
