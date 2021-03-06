<?php
/**
 * Timestamp column.
 * 
 * @author gizmore
 * @see GDO_CreatedAt
 *
 */
class GDO_Time extends GDO_Timestamp
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIME {$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
}
