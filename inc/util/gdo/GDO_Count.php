<?php
/**
 * Simple row number counter++
 * @author gizmore
 */
class GDO_Count extends GDO_Blank
{
	public $virtual = true;
	
	public $orderable = false;
	
	public function defaultLabel() { return $this; }
	
	private $num = 1;
	public function renderCell()
	{
		return $this->num++;
	}
}
