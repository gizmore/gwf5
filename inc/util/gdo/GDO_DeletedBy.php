<?php
final class GDO_DeletedBy extends GDO_User
{
	public function defaultLabel() { return $this->label('deleted_by'); }
	
}
