<?php
class GWF_Math
{
	public static function clamp($number, $min=null, $max=null)
	{
		if ( ($min !== null) && ($number < $min) ) return $min;
		if ( ($max !== null) && ($number > $max) ) return $max;
		return $number;
	}
}
