<?php
/**
 * Named identifier
 * @author gizmore
 */
class GDO_Name extends GDO_String
{
	public function defaultLabel() { return $this->label('name'); }

	const LENGTH = 64;
	
	public $min = 2, $max = self::LENGTH;
	public $encoding = self::ASCII;
	public $caseSensitive = true;
	public $pattern = "/[a-z][a-z_0-9]{1,63}/";
	public $null = false;
}
