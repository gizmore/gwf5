<?php
class GDO_DateTime extends GDO_Timestamp
{
	public function defaultLabel() { return $this->label('date'); }
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATETIME {$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
}
