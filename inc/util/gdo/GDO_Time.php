<?php
/**
 * Timestamp column.
 * 
 * @author gizmore
 * @see GDO_CreatedAt
 *
 */
class GDO_Time extends GDOType
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIMESTAMP{$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
}
