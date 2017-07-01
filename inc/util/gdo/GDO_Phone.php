<?php
final class GDO_Phone extends GDO_String
{
	public function __construct()
	{
		$this->min = 7;
		$this->max = 20;
		$this->pattern = "#^\\+?[-/0-9 ]+$#";
		$this->encoding = self::ASCII;
	}
	
}
