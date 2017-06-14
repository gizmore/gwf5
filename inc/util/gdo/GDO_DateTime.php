<?php
class GDO_DateTime extends GDO_Timestamp
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATETIME {$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
}
