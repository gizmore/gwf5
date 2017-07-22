<?php
/**
 * Default random token is 16 chars alphanumeric.
 * 
 * @author gizmore
 * @since 4.0
 * @version 5.0
 */
class GDO_Token extends GDO_Char
{
	public static $LENGTH = 12;
	
	public function defaultLabel() { return $this->label('token'); }
	
	public function __construct()
	{
		$this->size(self::$LENGTH);
		$this->notNull();
	}
	
	public function size(int $size)
	{
		$this->pattern = '/^[a-zA-Z0-9]{'.$size.'}$/d';
		return parent::size($size);
	}
	
	public function blankData()
	{
		return [$this->name => GWF_Random::randomKey($this->max)];
	}
	
}
