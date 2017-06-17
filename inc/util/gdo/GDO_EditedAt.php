<?php
/**
 * 
 * @author gizmore
 *
 */
final class GDO_EditedAt extends GDO_Timestamp
{
	public function defaultLabel() { return $this->label('edited_at'); }
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATETIME{$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
}
