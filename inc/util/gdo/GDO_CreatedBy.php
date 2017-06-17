<?php
/**
 * The created at column is not null and filled upon creation.
 * 
 * @author gizmore
 * @since 5.0
 *
 */
class GDO_CreatedBy extends GDO_User 
{
	public function defaultLabel() { return $this->label('created_by'); }
	
	public function blankData()
	{
		return [$this->name => GWF_User::current()->getID()];
	}
}
