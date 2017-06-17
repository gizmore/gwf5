<?php
class GDO_Count extends GDO_Blank
{
	private $num;
	
	public function __construct()
	{
		$this->num = 1;
	}
	
	public function renderCell()
	{
		return $this->num++;
	}
}