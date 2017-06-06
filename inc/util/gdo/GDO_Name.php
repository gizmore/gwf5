<?php
/**
 * Named identifier
 * @author gizmore
 */
class GDO_Name extends GDO_String
{
	public function __construct()
	{
		$this->min = 2;
		$this->max = 64;
		$this->encoding = self::ASCII;
		$this->caseSensitive = true;
		$this->pattern = "/[a-z][a-z_0-9]{1,63}/";
		$this->notNull();
	}
}
