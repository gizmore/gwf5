<?php
final class GDO_Count extends GDOType
{
	private $n = 1;
	
	public function __construct()
	{
		$this->name('count');
	}
	
	public function renderCell()
	{
		return $n++;
	}
}
