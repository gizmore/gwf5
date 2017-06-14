<?php
final class GDO_UpdatedAt extends GDO_Timestamp
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIMESTAMP{$this->gdoNullDefine()}{$this->gdoInitialDefine()} ON UPDATE CURRENT_TIMESTAMP";
	}
	
}
