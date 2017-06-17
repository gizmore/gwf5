<?php
/**
 * @author gizmore
 * @since 5.0
 */
class GDO_DeletedAt extends GDO_Time
{
	public function defaultLabel() { return $this->label('deleted_at'); }
}
