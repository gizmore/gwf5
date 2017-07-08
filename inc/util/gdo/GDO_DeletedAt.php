<?php
/**
 * @author gizmore
 * @since 5.0
 */
class GDO_DeletedAt extends GDO_DateTime
{
	public $writable = false;
	public $editable = false;

	public function defaultLabel() { return $this->label('deleted_at'); }
}
