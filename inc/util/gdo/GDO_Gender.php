<?php
class GDO_Gender extends GDO_Char
{
	public function __construct()
	{
		$this->size(1);
	}
	
	public function validate($value)
	{
		$this->error('err_gender');
		return $value === 'm' || $value === 'f' || $value === ' ';
	}
	
	
}
