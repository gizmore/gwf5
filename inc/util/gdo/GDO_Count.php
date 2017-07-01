<?php
/**
 * Simple row number counter++
 * @author gizmore
 */
class GDO_Count extends GDO_Blank
{
	public $virtual = true;
	
	private $num = 1;
	public function renderCell()
	{
		return $this->num++;
	}
}
