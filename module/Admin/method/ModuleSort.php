<?php
abstract class GWF_MethodSort extends GWF_Method
{
	/**
	 * @return GDO
	 */
	public abstract function gdoSortObjects();
	
	public function execute()
	{
		
	}
}

final class Admin_ModuleSort extends GWF_MethodSort
{
	public function gdoSortObjects() { return GWF_Module::table(); }
	
	public function getPermission() { return 'admin'; }
}
