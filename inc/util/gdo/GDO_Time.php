<?php
class GDO_Time extends GDOType
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIMESTAMP {$this->gdoNullDefine()} {$this->gdoInitialDefine()}";
	}
}
