<?php
final class GDO_DeletedBy extends GDO_User
{
	public $writable = false;
	public $editable = false;
	
	public function defaultLabel() { return $this->label('deleted_by'); }
}
