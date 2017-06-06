<?php
class GDO_Date extends GDOType
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATETIME {$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
}
