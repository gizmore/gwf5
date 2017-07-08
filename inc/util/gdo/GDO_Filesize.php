<?php
final class GDO_Filesize extends GDO_Int
{
	public $unsigned = true;
	
	public function defaultLabel() { return $this->label('size'); }
	
	public function renderCell()
	{
		return GWF_File::humanFilesize($this->getValue());
	}
}
