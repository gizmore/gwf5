<?php
/**
 * The created at column is not null and filled upon creation.
 * 
 * @author gizmore
 * @since 5.0
 *
 */
class GDO_CreatedBy extends GDO_Object
{
	public function __construct()
	{
		$this->klass = 'GWF_User';
	}

	public function blankData()
	{
		return [$this->name => GWF_User::current()->getID()];
	}
}
