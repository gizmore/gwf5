<?php
/**
 * Drag and drop sorting of modules.
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
final class Admin_ModuleSort extends GWF_MethodSort
{
	/**
	 * Only staff may sort modules for navbar appearance.
	 * {@inheritDoc}
	 * @see GWF_Method::getPermission()
	 */
	public function getPermission() { return 'staff'; }

	public function gdoSortObjects() { return GWF_Module::table(); }

	public function execute()
	{
		$response = parent::execute();
		GDOCache::unset('gwf_modules');
		return $response;
	}
}
