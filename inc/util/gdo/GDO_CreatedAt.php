<?php
/**
 * The created at column is not null and filled upon creation.
 * 
 * @author gizmore
 * @since 5.0
 *
 */
class GDO_CreatedAt extends GDO_Timestamp
{
	public $null = false;

	public function defaultLabel() { return $this->label('created_at'); }
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIMESTAMP{$this->gdoNullDefine()} DEFAULT CURRENT_TIMESTAMP";
	}
}
