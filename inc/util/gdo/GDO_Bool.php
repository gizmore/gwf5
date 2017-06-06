<?php
class GDO_Bool extends GDO_Int
{
	public $bytes = 1;
	public $min = 0;
	public $max = 1;
	public $initial = "0";
	
	public function getGDOValue()
	{
		return $this->getValue() > 0;
	}
}
