<?php
/**
 * The created at column is not null and filled upon creation.
 * 
 * @author gizmore
 * @since 5.0
 *
 */
class GDO_CreatedAt extends GDO_Time
{
	public function __construct()
	{
		$this->notNull();
	}

	public function blankData()
	{
		return [$this->name => time()];
	}
}
