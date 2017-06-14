<?php
final class GDO_EditedAt extends GDO_Timestamp
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATETIME{$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
}
